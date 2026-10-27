<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\Employee;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminAttendanceController extends Controller
{
    /**
     * Display attendance dashboard with Present, Absent, and Date Range All tabs.
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'present'); // 'present', 'absent', 'all'
        $date = $request->get('date', Carbon::today()->toDateString());
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        $search = trim($request->get('search', ''));
        $statusFilter = $request->get('status', '');
        $selectedEmployeeId = $request->get('employee_id', '');

        // 1. Fetch valid active employees
        $employeeQuery = Employee::has('user')->with(['user', 'joiningDetail']);
        if (!auth()->user()->hasRole('super-admin')) {
            $employeeQuery->whereHas('user', function ($q) {
                $q->whereDoesntHave('roles', function ($rq) {
                    $rq->where('slug', 'super-admin');
                });
            });
        }
        $allEmployees = $employeeQuery->get();
        $totalEmployees = $allEmployees->count();

        // 2. Fetch day-specific attendance records for KPI cards (on $date)
        $dayAttendances = Attendance::whereHas('employee.user')
            ->whereDate('date', $date)
            ->with(['employee.user', 'sessions', 'breaks'])
            ->get()
            ->keyBy('employee_id');

        $presentCount = $dayAttendances->whereIn('status', ['Present', 'Late', 'Half Day'])->count();
        $lateCount = $dayAttendances->where('status', 'Late')->count();
        $leaveCount = $dayAttendances->where('status', 'Leave')->count();
        $absentCount = max(0, $totalEmployees - ($presentCount + $leaveCount));

        $attendances = null;
        $absentList = null;
        $rangeTotal = 0;
        $rangePresent = 0;
        $rangeLate = 0;
        $rangeLeave = 0;
        $rangeAbsent = 0;

        // 3. Tab: ABSENT LIST
        if ($activeTab === 'absent') {
            $presentEmployeeIds = $dayAttendances->whereIn('status', ['Present', 'Late', 'Half Day'])->pluck('employee_id')->toArray();

            $absentCollection = $allEmployees->filter(function ($emp) use ($presentEmployeeIds, $search, $dayAttendances) {
                // Must not be present on $date
                if (in_array($emp->id, $presentEmployeeIds)) {
                    return false;
                }

                // Search by name, email, or employee code
                if (!empty($search)) {
                    $name = strtolower($emp->user->name ?? '');
                    $email = strtolower($emp->user->email ?? '');
                    $code = strtolower($emp->employee_code ?? '');
                    $term = strtolower($search);
                    if (!str_contains($name, $term) && !str_contains($email, $term) && !str_contains($code, $term)) {
                        return false;
                    }
                }

                return true;
            })->map(function ($emp) use ($dayAttendances, $date) {
                $att = $dayAttendances->get($emp->id);
                return [
                    'employee' => $emp,
                    'attendance' => $att,
                    'status' => $att ? $att->status : 'Absent',
                    'date' => $date,
                ];
            })->values();

            // Paginate absent list
            $page = (int) $request->get('page', 1);
            $perPage = 15;
            $absentList = new LengthAwarePaginator(
                $absentCollection->forPage($page, $perPage),
                $absentCollection->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        // 4. Tab: ALL / DATE RANGE HISTORY (Start Date to End Date)
        elseif ($activeTab === 'all') {
            $rangeQuery = Attendance::whereHas('employee.user')
                ->with(['employee.user', 'sessions', 'breaks'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate);

            if (!auth()->user()->hasRole('super-admin')) {
                $rangeQuery->whereHas('employee.user', function ($q) {
                    $q->whereDoesntHave('roles', function ($rq) {
                        $rq->where('slug', 'super-admin');
                    });
                });
            }

            if (!empty($search)) {
                $rangeQuery->whereHas('employee.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if (!empty($selectedEmployeeId)) {
                $rangeQuery->where('employee_id', $selectedEmployeeId);
            }

            if (!empty($statusFilter)) {
                $rangeQuery->where('status', $statusFilter);
            }

            // Summary metrics for the filtered date range
            $rangeTotal = (clone $rangeQuery)->count();
            $rangePresent = (clone $rangeQuery)->whereIn('status', ['Present', 'Late', 'Half Day'])->count();
            $rangeLate = (clone $rangeQuery)->where('status', 'Late')->count();
            $rangeLeave = (clone $rangeQuery)->where('status', 'Leave')->count();
            $rangeAbsent = (clone $rangeQuery)->where('status', 'Absent')->count();

            $attendances = $rangeQuery->orderByDesc('date')
                ->orderByDesc('first_login_at')
                ->paginate(20)
                ->withQueryString();
        }

        // 5. Tab: PRESENT LIST (Default)
        else {
            $query = Attendance::whereHas('employee.user')
                ->with(['employee.user', 'sessions', 'breaks'])
                ->whereDate('date', $date)
                ->whereIn('status', ['Present', 'Late', 'Half Day']);

            if (!auth()->user()->hasRole('super-admin')) {
                $query->whereHas('employee.user', function ($q) {
                    $q->whereDoesntHave('roles', function ($rq) {
                        $rq->where('slug', 'super-admin');
                    });
                });
            }

            if (!empty($search)) {
                $query->whereHas('employee.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if (!empty($statusFilter)) {
                $query->where('status', $statusFilter);
            }

            $attendances = $query->orderBy('first_login_at', 'asc')->paginate(15)->withQueryString();
        }

        return view('admin.attendance.index', compact(
            'activeTab',
            'date',
            'startDate',
            'endDate',
            'search',
            'statusFilter',
            'selectedEmployeeId',
            'allEmployees',
            'totalEmployees',
            'presentCount',
            'lateCount',
            'leaveCount',
            'absentCount',
            'attendances',
            'absentList',
            'rangeTotal',
            'rangePresent',
            'rangeLate',
            'rangeLeave',
            'rangeAbsent',
            'dayAttendances'
        ));
    }

    /**
     * View break violations log.
     */
    public function breakViolations()
    {
        $violations = AttendanceBreak::with(['employee.user', 'attendance'])
            ->where('is_exceeded', true)
            ->latest()
            ->paginate(15);

        return view('admin.attendance.break_violations', compact('violations'));
    }

    /**
     * Admin manual correction/adjustment of attendance record.
     */
    public function adjust(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:Present,Absent,Half Day,Leave,Holiday,Week Off,Late,Early Logout,Missing Logout',
            'first_login_at' => 'nullable|string',
            'last_logout_at' => 'nullable|string',
            'admin_remarks' => 'required|string|max:255',
        ]);

        $dateStr = $attendance->date instanceof Carbon ? $attendance->date->format('Y-m-d') : (string) $attendance->date;

        $firstLogin = null;
        if (!empty($validated['first_login_at'])) {
            $firstLogin = Carbon::parse($dateStr . ' ' . $validated['first_login_at']);
        }

        $lastLogout = null;
        if (!empty($validated['last_logout_at'])) {
            $lastLogout = Carbon::parse($dateStr . ' ' . $validated['last_logout_at']);
        }

        $effectiveMinutes = $attendance->effective_working_minutes;
        if ($firstLogin && $lastLogout) {
            $effectiveMinutes = max(0, $lastLogout->diffInMinutes($firstLogin) - $attendance->total_break_minutes);
        }

        $attendance->update([
            'status' => $validated['status'],
            'first_login_at' => $firstLogin ?? $attendance->first_login_at,
            'last_logout_at' => $lastLogout ?? $attendance->last_logout_at,
            'effective_working_minutes' => $effectiveMinutes,
            'is_admin_adjusted' => true,
            'admin_remarks' => $validated['admin_remarks'],
        ]);

        ActivityLogger::log(
            'Attendance Adjusted',
            "Admin corrected attendance for {$attendance->employee->user->name} on {$dateStr}",
            Attendance::class,
            $attendance->id
        );

        return back()->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Adjust or create attendance record (for present or absent employees).
     */
    public function adjustOrCreate(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Half Day,Leave,Holiday,Week Off,Late,Early Logout,Missing Logout',
            'first_login_at' => 'nullable|string',
            'last_logout_at' => 'nullable|string',
            'admin_remarks' => 'required|string|max:255',
        ]);

        $emp = Employee::with('user')->findOrFail($validated['employee_id']);
        $dateStr = Carbon::parse($validated['date'])->toDateString();

        $attendance = Attendance::where('employee_id', $emp->id)
            ->whereDate('date', $dateStr)
            ->first();

        if (!$attendance) {
            $attendance = new Attendance([
                'employee_id' => $emp->id,
                'date' => $dateStr,
            ]);
        }

        $firstLogin = null;
        if (!empty($validated['first_login_at'])) {
            $firstLogin = Carbon::parse($dateStr . ' ' . $validated['first_login_at']);
        } elseif (!$attendance->first_login_at && in_array($validated['status'], ['Present', 'Late', 'Half Day'])) {
            $firstLogin = Carbon::parse($dateStr . ' 10:00:00');
        }

        $lastLogout = null;
        if (!empty($validated['last_logout_at'])) {
            $lastLogout = Carbon::parse($dateStr . ' ' . $validated['last_logout_at']);
        } elseif (!$attendance->last_logout_at && in_array($validated['status'], ['Present', 'Late', 'Half Day'])) {
            $lastLogout = Carbon::parse($dateStr . ' 19:00:00');
        }

        $effectiveMinutes = $attendance->effective_working_minutes ?? 0;
        if ($firstLogin && $lastLogout) {
            $effectiveMinutes = max(0, $lastLogout->diffInMinutes($firstLogin) - ($attendance->total_break_minutes ?? 0));
        }

        $attendance->status = $validated['status'];
        if ($firstLogin) $attendance->first_login_at = $firstLogin;
        if ($lastLogout) $attendance->last_logout_at = $lastLogout;
        $attendance->effective_working_minutes = $effectiveMinutes;
        $attendance->is_admin_adjusted = true;
        $attendance->admin_remarks = $validated['admin_remarks'];
        $attendance->save();

        ActivityLogger::log(
            'Attendance Adjusted',
            "Admin marked/adjusted attendance for {$emp->user->name} on {$dateStr} as {$validated['status']}",
            Attendance::class,
            $attendance->id
        );

        return back()->with('success', "Attendance updated successfully for {$emp->user->name} on {$dateStr}.");
    }
}
