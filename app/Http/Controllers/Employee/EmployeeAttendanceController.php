<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display my attendance logs and monthly summary.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $yearMonth = Carbon::parse($month . '-01');

        $attendances = Attendance::with(['sessions', 'breaks'])
            ->where('employee_id', $employee->id)
            ->whereYear('date', $yearMonth->year)
            ->whereMonth('date', $yearMonth->month)
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $totalWorkingDays = $attendances->count();
        $presentDays = $attendances->whereIn('status', ['Present', 'Late', 'Half Day'])->count();
        $leaveDays = $attendances->where('status', 'Leave')->count();
        $lateDays = $attendances->where('status', 'Late')->count();

        $totalWorkingMins = $attendances->sum('effective_working_minutes');
        $totalBreakMins = $attendances->sum('total_break_minutes');

        return view('employee.attendance.index', compact(
            'month',
            'attendances',
            'totalWorkingDays',
            'presentDays',
            'leaveDays',
            'lateDays',
            'totalWorkingMins',
            'totalBreakMins'
        ));
    }
}
