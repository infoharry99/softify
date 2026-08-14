@extends('layouts.employee')

@section('title', 'My Attendance Logs')
@section('page_title', 'My Daily Attendance & Session History')

@section('content')
<!-- Monthly Summary Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-calendar-days" style="color: #0284c7;"></i> Total Days Logged</div>
        <div class="stat-value">{{ $totalWorkingDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-user-check" style="color: #10b981;"></i> Days Present</div>
        <div class="stat-value" style="color: var(--success);">{{ $presentDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-plane-departure" style="color: #f59e0b;"></i> Days On Leave</div>
        <div class="stat-value" style="color: var(--warning);">{{ $leaveDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-clock-rotate-left" style="color: #ef4444;"></i> Late Logins</div>
        <div class="stat-value" style="color: var(--danger);">{{ $lateDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-business-time" style="color: #0284c7;"></i> Effective Hours</div>
        <div class="stat-value" style="color: #0284c7;">{{ floor($totalWorkingMins / 60) }}h {{ $totalWorkingMins % 60 }}m</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-list-check" style="color: #0284c7;"></i> Attendance Logbook
        </h3>
        <form action="{{ route('employee.attendance') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()" style="padding: 6px 12px; font-weight: 500;">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Breaks Duration</th>
                    <th>Effective Hours</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td><strong>{{ $att->date->format('M d, Y') }}</strong> <small style="color: var(--text-muted);">({{ $att->date->format('l') }})</small></td>
                    <td>{{ $att->first_login_at ? $att->first_login_at->timezone('Asia/Kolkata')->format('h:i A') : '--:--' }}</td>
                    <td>{{ $att->last_logout_at ? $att->last_logout_at->timezone('Asia/Kolkata')->format('h:i A') : '--:--' }}</td>
                    <td>{{ $att->total_break_minutes }} mins</td>
                    <td><strong>{{ floor($att->effective_working_minutes / 60) }}h {{ $att->effective_working_minutes % 60 }}m</strong></td>
                    <td>
                        <span class="badge {{ $att->status === 'Present' ? 'badge-success' : ($att->status === 'Late' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $att->status }}
                        </span>
                        @if($att->is_admin_adjusted)
                            <small style="color: var(--secondary); display: block;">(Admin Adjusted)</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 35px;">
                        No attendance records found for {{ $month }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-footer">
        {{ $attendances->withQueryString()->links() }}
    </div>
</div>
@endsection
