<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\AttendanceBreak;
use App\Models\LeaveBalance;
use App\Models\Announcement;
use App\Models\AppNotification;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    /**
     * Display the Employee Dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            // Auto-create employee record if user has no employee model yet
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

        $announcements = Announcement::latest()->take(5)->get();
        $notifications = AppNotification::where('user_id', $user->id)->latest()->take(5)->get();
        $unreadCount = AppNotification::where('user_id', $user->id)->where('is_read', false)->count();

        return view('employee.dashboard', compact(
            'user',
            'employee',
            'attendance',
            'activeSession',
            'activeBreak',
            'leaveBalances',
            'announcements',
            'notifications',
            'unreadCount'
        ));
    }

    /**
     * Employee Clock-In action handler.
     */
    public function clockIn()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        AttendanceService::recordLogin($employee);

        return back()->with('success', 'You have clocked in. Status updated to Working.');
    }

    /**
     * Employee Clock-Out action handler.
     */
    public function clockOut()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        AttendanceService::recordLogout($employee);

        return back()->with('success', 'You have logged out. Work session ended.');
    }

    /**
     * Start Break action handler.
     */
    public function startBreak()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $result = AttendanceService::startBreak($employee);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * End Break action handler.
     */
    public function endBreak()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $result = AttendanceService::endBreak($employee);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        if ($result['is_exceeded']) {
            return back()->with('warning', 'Break ended. Note: Your break exceeded the allowed duration.');
        }

        return back()->with('success', $result['message']);
    }
}
