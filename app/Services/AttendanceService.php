<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\AttendanceBreak;
use App\Models\AppNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class AttendanceService
{
    /**
     * Default configured start time (09:30 AM).
     */
    protected const START_TIME = '09:30:00';
    protected const ALLOWED_BREAK_MINUTES = 30;

    /**
     * Record employee login and start work session.
     */
    public static function recordLogin(Employee $employee)
    {
        $today = Carbon::today('Asia/Kolkata')->toDateString();
        $now = Carbon::now('Asia/Kolkata');

        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();
        if (!$attendance) {
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'status' => 'Present',
                'first_login_at' => $now,
            ]);
        } else {
            if (!$attendance->first_login_at) {
                $attendance->update([
                    'first_login_at' => $now,
                    'status' => 'Present',
                ]);
            }
        }

        // Check if late (login after 09:30 AM)
        $standardStart = Carbon::parse($today . ' ' . self::START_TIME, 'Asia/Kolkata');
        if ($now->gt($standardStart) && $attendance->late_minutes === 0) {
            $lateMins = (int) $now->diffInMinutes($standardStart);
            $attendance->update([
                'status' => 'Late',
                'late_minutes' => $lateMins,
            ]);
        }

        // Close any orphaned active session
        AttendanceSession::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->update([
                'logout_at' => $now,
                'status' => 'Auto Closed',
            ]);

        // Start new active session
        $session = AttendanceSession::create([
            'attendance_id' => $attendance->id,
            'employee_id' => $employee->id,
            'login_at' => $now,
            'status' => 'Active',
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);

        return ['attendance' => $attendance, 'session' => $session];
    }

    /**
     * Start a break for an employee.
     */
    public static function startBreak(Employee $employee)
    {
        $today = Carbon::today('Asia/Kolkata')->toDateString();
        $now = Carbon::now('Asia/Kolkata');
        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();

        if (!$attendance || !$attendance->first_login_at) {
            return ['success' => false, 'message' => 'You must log in before starting a break.'];
        }

        // Check if an active break already exists
        $activeBreak = AttendanceBreak::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->first();

        if ($activeBreak) {
            return ['success' => false, 'message' => 'You already have an active break running.'];
        }

        $break = AttendanceBreak::create([
            'attendance_id' => $attendance->id,
            'employee_id' => $employee->id,
            'started_at' => $now,
            'status' => 'Active',
        ]);

        return ['success' => true, 'break' => $break, 'message' => 'Break started successfully.'];
    }

    /**
     * End an active break.
     */
    public static function endBreak(Employee $employee)
    {
        $activeBreak = AttendanceBreak::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->first();

        if (!$activeBreak) {
            return ['success' => false, 'message' => 'No active break found to end.'];
        }

        $now = Carbon::now('Asia/Kolkata');
        $durationMins = (int) $now->diffInMinutes($activeBreak->started_at);
        $isExceeded = $durationMins > self::ALLOWED_BREAK_MINUTES;
        $exceededMins = $isExceeded ? ($durationMins - self::ALLOWED_BREAK_MINUTES) : 0;

        $activeBreak->update([
            'ended_at' => $now,
            'duration_minutes' => $durationMins,
            'is_exceeded' => $isExceeded,
            'exceeded_minutes' => $exceededMins,
            'status' => 'Ended',
        ]);

        // Update attendance aggregate break minutes
        $attendance = $activeBreak->attendance;
        $totalBreakMins = AttendanceBreak::where('attendance_id', $attendance->id)->sum('duration_minutes');
        $attendance->update(['total_break_minutes' => $totalBreakMins]);

        // Send notifications if break exceeded
        if ($isExceeded) {
            $user = $employee->user;
            $msg = "Employee {$user->name} has exceeded the allowed break time by {$exceededMins} minutes.";

            // Notify Employee
            AppNotification::create([
                'user_id' => $user->id,
                'type' => 'break_exceeded',
                'title' => 'Break Time Exceeded',
                'message' => "Your break exceeded the allowed duration by {$exceededMins} minutes.",
            ]);

            // Notify Admins & HR
            $admins = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', ['super-admin', 'admin', 'hr']);
            })->get();

            foreach ($admins as $admin) {
                AppNotification::create([
                    'user_id' => $admin->id,
                    'type' => 'break_exceeded',
                    'title' => 'Break Violation Alert',
                    'message' => $msg,
                ]);
            }
        }

        return ['success' => true, 'break' => $activeBreak, 'is_exceeded' => $isExceeded, 'message' => 'Break ended successfully.'];
    }

    /**
     * Record employee logout.
     */
    public static function recordLogout(Employee $employee)
    {
        $now = Carbon::now('Asia/Kolkata');
        $today = Carbon::today('Asia/Kolkata')->toDateString();

        // End any active break first
        self::endBreak($employee);

        // Close active session
        $activeSession = AttendanceSession::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->first();

        if ($activeSession) {
            $sessDuration = (int) $now->diffInMinutes($activeSession->login_at);
            $activeSession->update([
                'logout_at' => $now,
                'duration_minutes' => $sessDuration,
                'status' => 'Logged Out',
            ]);
        }

        // Aggregate today's attendance stats
        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();
        if ($attendance) {
            $totalSessMins = AttendanceSession::where('attendance_id', $attendance->id)->sum('duration_minutes');
            $totalBreakMins = AttendanceBreak::where('attendance_id', $attendance->id)->sum('duration_minutes');
            $effectiveMins = max(0, $totalSessMins - $totalBreakMins);

            $attendance->update([
                'last_logout_at' => $now,
                'total_working_minutes' => $totalSessMins,
                'total_break_minutes' => $totalBreakMins,
                'effective_working_minutes' => $effectiveMins,
            ]);
        }

        return $attendance;
    }
}
