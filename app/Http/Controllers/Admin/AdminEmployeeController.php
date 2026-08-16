<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\EmployeeJoiningDetail;
use App\Models\User;
use App\Models\Role;
use App\Services\ActivityLogger;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    /**
     * Display listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['user.roles', 'joiningDetail', 'profile']);

        if (!auth()->user()->hasRole('super-admin')) {
            $query->whereHas('user', function ($q) {
                $q->whereDoesntHave('roles', function ($rq) {
                    $rq->where('slug', 'super-admin');
                });
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('department', 'like', "%{$search}%")
                         ->orWhere('designation', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('joiningDetail', function ($q) use ($request) {
                $q->where('employment_status', $request->status);
            });
        }

        $employees = $query->latest()->paginate(10)->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show form for creating a new employee.
     */
    public function create()
    {
        $managers = Employee::with('user')->get();
        $roles = Role::where('status', 'active')->whereNotIn('slug', ['super-admin', 'admin'])->get();

        return view('admin.employees.create', compact('managers', 'roles'));
    }

    /**
     * Store new employee record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => ['nullable', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'password' => 'required|string|min:8|confirmed',
            'employee_code' => 'required|string|max:50|unique:employees,employee_code',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'reporting_manager_id' => 'nullable|exists:employees,id',
            'joining_date' => 'required|date',
            'employment_type' => 'required|in:Full Time,Part Time,Contract,Intern,Freelancer',
            'employment_status' => 'required|in:Active,Probation,Notice Period,Resigned,Terminated,Inactive',
            'work_location' => 'nullable|string|max:150',
            'role_id' => 'required|exists:roles,id',
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210).',
        ]);

        // Create User account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'department' => $validated['department'],
            'designation' => $validated['designation'],
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync([$validated['role_id']]);

        // Create Employee
        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_code' => $validated['employee_code'],
            'reporting_manager_id' => $validated['reporting_manager_id'],
        ]);

        // Create Profile & Joining details
        EmployeeProfile::create(['employee_id' => $employee->id]);

        EmployeeJoiningDetail::create([
            'employee_id' => $employee->id,
            'joining_date' => $validated['joining_date'],
            'employment_type' => $validated['employment_type'],
            'employment_status' => $validated['employment_status'],
            'work_location' => $validated['work_location'] ?? 'Office',
        ]);

        // Initialize annual leave balances
        LeaveService::initializeBalances($employee);

        ActivityLogger::log('Employee Created', "Created employee {$user->name} ({$employee->employee_code})", Employee::class, $employee->id);

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee profile created successfully.');
    }

    /**
     * Show 360° Employee Detail Tabs page.
     */
    public function show(Employee $employee, Request $request)
    {
        if ($employee->user && $employee->user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'You do not have permission to view or edit Super Admin details.');
        }

        $employee->load([
            'user.roles',
            'profile',
            'joiningDetail',
            'reportingManager.user',
            'documents.uploader',
            'attendances' => fn($q) => $q->latest()->take(10),
            'leaveApplications' => fn($q) => $q->latest()->take(10),
            'leaveBalances.leaveType',
            'salaryStructure',
            'salaryHistories.updater',
            'monthlyPayrolls' => fn($q) => $q->latest()->take(12),
        ]);

        $activeTab = $request->get('tab', 'overview');

        return view('admin.employees.show', compact('employee', 'activeTab'));
    }

    /**
     * Show form for editing an employee.
     */
    public function edit(Employee $employee)
    {
        if ($employee->user && $employee->user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'You do not have permission to view or edit Super Admin details.');
        }

        $employee->load(['user.roles', 'profile', 'joiningDetail']);
        $managers = Employee::where('id', '!=', $employee->id)->with('user')->get();
        $roles = Role::where('status', 'active')->whereNotIn('slug', ['super-admin', 'admin'])->get();

        return view('admin.employees.edit', compact('employee', 'managers', 'roles'));
    }

    /**
     * Update employee record.
     */
    public function update(Request $request, Employee $employee)
    {
        if ($employee->user && $employee->user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'You do not have permission to view or edit Super Admin details.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($employee->user_id)],
            'mobile' => ['nullable', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'reporting_manager_id' => 'nullable|exists:employees,id',
            'joining_date' => 'required|date',
            'employment_type' => 'required|in:Full Time,Part Time,Contract,Intern,Freelancer',
            'employment_status' => 'required|in:Active,Probation,Notice Period,Resigned,Terminated,Inactive',
            'work_location' => 'nullable|string|max:150',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Update User
        $employee->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'department' => $validated['department'],
            'designation' => $validated['designation'],
        ]);

        $employee->user->roles()->sync([$validated['role_id']]);

        // Update Employee
        $employee->update([
            'reporting_manager_id' => $validated['reporting_manager_id'],
        ]);

        // Update Joining details
        $employee->joiningDetail()->update([
            'joining_date' => $validated['joining_date'],
            'employment_type' => $validated['employment_type'],
            'employment_status' => $validated['employment_status'],
            'work_location' => $validated['work_location'] ?? 'Office',
        ]);

        ActivityLogger::log('Employee Updated', "Updated details for employee {$employee->user->name}", Employee::class, $employee->id);

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee record updated successfully.');
    }
}
