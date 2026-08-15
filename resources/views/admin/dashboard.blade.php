@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Main Overview Dashboard')

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #00a884 0%, #008f70 100%);
        color: #ffffff;
        padding: 22px 28px;
        border-radius: var(--radius);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.25);
    }
    .welcome-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
    .welcome-subtitle { font-size: 0.875rem; color: #dbeafe; }

    .work-session-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: var(--shadow);
    }
    .session-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }
    .session-metric-box {
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 12px;
        border-radius: 10px;
        text-align: center;
    }
    .metric-label { font-size: 0.72rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
    .metric-val { font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-top: 4px; }

    .clock-btn-lg {
        padding: 11px 24px;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .clock-in-btn { background-color: #059669; color: #ffffff; }
    .clock-in-btn:hover { background-color: #047857; }
    .clock-out-btn { background-color: #ef4444; color: #ffffff; }
    .clock-out-btn:hover { background-color: #dc2626; }
    .break-btn { background-color: #f59e0b; color: #ffffff; }
    .break-btn:hover { background-color: #d97706; }
</style>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner">
    <div>
        <div class="welcome-title">
            <i class="fa-solid fa-hand-wave"></i> Welcome back, {{ $user->name }}!
        </div>
        <div class="welcome-subtitle">
            Role: <strong>{{ auth()->user()->roles->pluck('name')->first() ?? 'Administrator / HR' }}</strong> | Employee Code: <strong>{{ $employee->employee_code }}</strong>
        </div>
    </div>
    <div>
        @if($activeBreak)
            <span class="badge badge-warning" style="font-size: 0.85rem; padding: 8px 16px;">
                <i class="fa-solid fa-mug-hot"></i> Status: On Break
            </span>
        @elseif($activeSession)
            <span class="badge badge-success" style="font-size: 0.85rem; padding: 8px 16px;">
                <i class="fa-solid fa-circle-play"></i> Status: Currently Working
            </span>
        @elseif($attendance && $attendance->last_logout_at)
            <span class="badge badge-danger" style="font-size: 0.85rem; padding: 8px 16px;">
                <i class="fa-solid fa-flag-checkered"></i> Status: Shift Completed / Paused
            </span>
        @else
            <span class="badge badge-secondary" style="font-size: 0.85rem; padding: 8px 16px;">
                <i class="fa-solid fa-circle-stop"></i> Status: Logged Out
            </span>
        @endif
    </div>
</div>

<!-- Personal Daily Work Session Widget for HR / Admin -->
<div class="work-session-card">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
        <h3 class="card-title" style="font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-clock-rotate-left" style="color: #2563eb;"></i> My Today's Attendance & Work Session
        </h3>
        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
            <span>{{ \Carbon\Carbon::now('Asia/Kolkata')->format('l, d M Y') }}</span>
            <span style="margin: 0 6px;">|</span>
            <strong style="color: #2563eb;">
                <i class="fa-regular fa-clock"></i> Current Time: <span id="admin_current_live_time">--:--:-- --</span> (IST)
            </strong>
        </div>
    </div>

    <div class="session-metrics">
        <div class="session-metric-box">
            <div class="metric-label">Clock In</div>
            <div class="metric-val" style="color: #059669;">
                {{ $attendance && $attendance->first_login_at ? \Carbon\Carbon::parse($attendance->first_login_at)->timezone('Asia/Kolkata')->format('h:i A') : '--:--' }}
            </div>
        </div>

        <div class="session-metric-box">
            <div class="metric-label">Clock Out</div>
            <div class="metric-val" style="color: #ef4444;">
                {{ $attendance && $attendance->last_logout_at ? \Carbon\Carbon::parse($attendance->last_logout_at)->timezone('Asia/Kolkata')->format('h:i A') : '--:--' }}
            </div>
        </div>

        <div class="session-metric-box">
            <div class="metric-label">Total Working</div>
            <div id="admin_live_total_working" class="metric-val" style="color: #2563eb;">
                @if($attendance)
                    {{ floor($attendance->effective_working_minutes / 60) }}h {{ $attendance->effective_working_minutes % 60 }}m
                @else
                    0h 0m
                @endif
            </div>
        </div>

        <div class="session-metric-box">
            <div class="metric-label">Total Break</div>
            <div id="admin_live_total_break" class="metric-val" style="color: #d97706;">
                {{ $attendance ? $attendance->total_break_minutes : 0 }} mins
            </div>
        </div>
    </div>

    <!-- Clock Action Controls -->
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        @if($activeBreak)
            <form action="{{ route('employee.break.end') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="clock-btn-lg clock-in-btn">
                    <i class="fa-solid fa-play"></i> End Break
                </button>
            </form>
            <form action="{{ route('employee.clock_out') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="clock-btn-lg clock-out-btn" onclick="return confirm('End work session for today?');">
                    <i class="fa-solid fa-door-open"></i> End Session (Clock Out)
                </button>
            </form>
        @elseif($activeSession)
            <form action="{{ route('employee.break.start') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="clock-btn-lg break-btn">
                    <i class="fa-solid fa-mug-hot"></i> Start Break
                </button>
            </form>
            <form action="{{ route('employee.clock_out') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="clock-btn-lg clock-out-btn" onclick="return confirm('End work session for today?');">
                    <i class="fa-solid fa-door-open"></i> End Session (Clock Out)
                </button>
            </form>
        @else
            <form action="{{ route('employee.clock_in') }}" method="POST">
                @csrf
                <button type="submit" class="clock-btn-lg clock-in-btn">
                    <i class="fa-solid fa-rocket"></i> {{ $attendance && $attendance->first_login_at ? 'Resume Work Session (Clock In)' : 'Start Work Session (Clock In)' }}
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Top Metric Summary Cards (Matching Screenshot 1 Row 1) -->
<div class="stats-grid">
    <div class="stat-card stat-card-blue">
        <div class="stat-header">
            <div class="stat-icon-circle stat-icon-blue">
                <i class="fa-solid fa-users"></i>
            </div>
            <span class="stat-badge stat-badge-danger">↓ 100%</span>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total System Users</div>
    </div>

    <div class="stat-card stat-card-green">
        <div class="stat-header">
            <div class="stat-icon-circle stat-icon-green">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <span class="stat-badge stat-badge-danger">↓ 84.6%</span>
        </div>
        <div class="stat-value">{{ $activeUsers }}</div>
        <div class="stat-label">Active Employees</div>
    </div>

    <div class="stat-card stat-card-orange">
        <div class="stat-header">
            <div class="stat-icon-circle stat-icon-orange">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <span class="stat-badge stat-badge-danger">↓ 100%</span>
        </div>
        <div class="stat-value">{{ $totalRoles }}</div>
        <div class="stat-label">Roles & Access</div>
    </div>

    <div class="stat-card stat-card-red">
        <div class="stat-header">
            <div class="stat-icon-circle stat-icon-red">
                <i class="fa-solid fa-key"></i>
            </div>
            <span class="stat-badge stat-badge-neutral">{{ $totalPermissions }} active</span>
        </div>
        <div class="stat-value">{{ $totalPermissions }}</div>
        <div class="stat-label">Permissions</div>
    </div>
</div>

<!-- Status Indicator Row Cards (Matching Screenshot 1 Row 2) -->
<div class="status-row-grid">
    <div class="status-mini-card">
        <div class="dot-indicator dot-pending"></div>
        <div>
            <div class="status-card-num">1</div>
            <div class="status-card-lbl">Pending</div>
        </div>
    </div>

    <div class="status-mini-card">
        <div class="dot-indicator dot-confirmed"></div>
        <div>
            <div class="status-card-num">{{ $activeUsers }}</div>
            <div class="status-card-lbl">Confirmed</div>
        </div>
    </div>

    <div class="status-mini-card">
        <div class="dot-indicator dot-completed"></div>
        <div>
            <div class="status-card-num">21</div>
            <div class="status-card-lbl">Completed</div>
        </div>
    </div>

    <div class="status-mini-card">
        <div class="dot-indicator dot-cancelled"></div>
        <div>
            <div class="status-card-num">16</div>
            <div class="status-card-lbl">Cancelled</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">
    <!-- Recent Users Directory Table (Matching Screenshot 2 & 3) -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-user-plus" style="color: #2563eb;"></i> User Directory
            </h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All Users</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $index => $u)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 0.8rem;">{{ $index + 1 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a;">{{ $u->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->mobile ?? '9876543210' }}</td>
                        <td>
                            @foreach($u->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge {{ $u->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ $u->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.users.index') }}" class="btn-table-action">
                                View User
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Audit Activity Log -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-list-check" style="color: #2563eb;"></i> Recent System Activity
            </h3>
            <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-secondary btn-sm">View All Logs</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Performed By</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $act)
                    <tr>
                        <td>
                            <strong>{{ $act->user ? $act->user->name : 'System' }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $act->action }}</span>
                        </td>
                        <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $act->description ?? '-' }}
                        </td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">
                            {{ $act->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">No recent activities logged</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 1. Live IST Clock
    function updateAdminLiveClock() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Kolkata',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const timeString = new Intl.DateTimeFormat('en-US', options).format(now);
        const clockEl = document.getElementById('admin_current_live_time');
        if (clockEl) {
            clockEl.innerText = timeString;
        }
    }

    updateAdminLiveClock();
    setInterval(updateAdminLiveClock, 1000);

    // 2. Real-Time Working Hours & Minutes Ticking Counter
    const adminActiveSessionStart = @json($activeSession ? \Carbon\Carbon::parse($activeSession->login_at)->timezone('Asia/Kolkata')->toIso8601String() : null);
    const adminActiveBreakStart = @json($activeBreak ? \Carbon\Carbon::parse($activeBreak->started_at)->timezone('Asia/Kolkata')->toIso8601String() : null);
    const adminPrevWorkingMins = @json($attendance ? max(0, $attendance->effective_working_minutes) : 0);
    const adminPrevBreakMins = @json($attendance ? max(0, $attendance->total_break_minutes) : 0);

    function updateAdminLiveCounters() {
        const now = new Date();

        // Working Counter
        if (adminActiveSessionStart && !adminActiveBreakStart) {
            const sessionStart = new Date(adminActiveSessionStart);
            const elapsedSecs = Math.max(0, Math.floor((now - sessionStart) / 1000));
            const totalSecs = (adminPrevWorkingMins * 60) + elapsedSecs;

            const h = Math.floor(totalSecs / 3600);
            const m = Math.floor((totalSecs % 3600) / 60);
            const s = totalSecs % 60;

            const workEl = document.getElementById('admin_live_total_working');
            if (workEl) {
                workEl.innerText = `${h}h ${m}m ${s}s`;
            }
        }

        // Break Counter
        if (adminActiveBreakStart) {
            const breakStart = new Date(adminActiveBreakStart);
            const elapsedSecs = Math.max(0, Math.floor((now - breakStart) / 1000));
            const totalSecs = (adminPrevBreakMins * 60) + elapsedSecs;

            const m = Math.floor(totalSecs / 60);
            const s = totalSecs % 60;

            const breakEl = document.getElementById('admin_live_total_break');
            if (breakEl) {
                breakEl.innerText = `${m}m ${s}s`;
            }
        }
    }

    updateAdminLiveCounters();
    setInterval(updateAdminLiveCounters, 1000);
</script>
@endsection
