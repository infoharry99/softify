<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\AttendanceBreak;
use App\Models\LeaveBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard metrics, attendance work session, and activity.
     */
    public function index()
    {
        $user = auth()->user();

        // Fetch or auto-create Employee model for logged-in Admin/HR user
        $employee = Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            ]);
        }

        $today = Carbon::today('Asia/Kolkata')->toDateString();
        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();

        $activeSession = AttendanceSession::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->first();

        $activeBreak = AttendanceBreak::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->first();

        $leaveBalances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employee->id)
            ->where('year', Carbon::now('Asia/Kolkata')->year)
            ->get();

        // Metrics for Admin System
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();

        $recentUsers = User::with('roles')->latest()->take(5)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'user',
            'employee',
            'attendance',
            'activeSession',
            'activeBreak',
            'leaveBalances',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'totalRoles',
            'totalPermissions',
            'recentUsers',
            'recentActivities'
        ));
    }
}
