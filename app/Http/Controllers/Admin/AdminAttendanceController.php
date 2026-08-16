<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\Employee;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    /**
     * Display today's attendance dashboard and search filter matrix.
     */
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $totalEmployees = Employee::count();
        $todayAttendances = Attendance::where('date', $date)->get();

        $presentCount = $todayAttendances->whereIn('status', ['Present', 'Late', 'Half Day'])->count();
        $lateCount = $todayAttendances->where('status', 'Late')->count();
        $leaveCount = $todayAttendances->where('status', 'Leave')->count();
        $absentCount = max(0, $totalEmployees - ($presentCount + $leaveCount));

        $query = Attendance::with(['employee.user', 'sessions', 'breaks'])
            ->where('date', $date);

        if (!auth()->user()->hasRole('super-admin')) {
            $query->whereHas('employee.user', function ($q) {
                $q->whereDoesntHave('roles', function ($rq) {
                    $rq->where('slug', 'super-admin');
                });
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(15)->withQueryString();

        return view('admin.attendance.index', compact(
            'date',
            'totalEmployees',
            'presentCount',
            'lateCount',
            'leaveCount',
            'absentCount',
            'attendances'
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
            'first_login_at' => 'nullable|date',
            'last_logout_at' => 'nullable|date',
            'admin_remarks' => 'required|string|max:255',
        ]);

        $attendance->update([
            'status' => $validated['status'],
            'first_login_at' => $validated['first_login_at'] ?? $attendance->first_login_at,
            'last_logout_at' => $validated['last_logout_at'] ?? $attendance->last_logout_at,
            'is_admin_adjusted' => true,
            'admin_remarks' => $validated['admin_remarks'],
        ]);

        ActivityLogger::log('Attendance Adjusted', "Admin corrected attendance for {$attendance->employee->user->name} on {$attendance->date->format('Y-m-d')}", Attendance::class, $attendance->id);

        return back()->with('success', 'Attendance record updated successfully.');
    }
}
