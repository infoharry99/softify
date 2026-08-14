@extends('layouts.employee')

@section('title', 'Employee Dashboard')
@section('page_title', 'My Work Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff;">
    <div class="card-body" style="padding: 26px 30px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 5px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-hand-wave"></i> Welcome back, {{ $user->name }}!
            </h2>
            <div style="font-size: 0.9rem; color: #e0f2fe;">
                Employee Code: <strong>{{ $employee->employee_code }}</strong> | Department: <strong>{{ $user->department ?? 'General' }}</strong>
            </div>
        </div>
        <div>
            @if($activeBreak)
                <span class="badge badge-warning" style="font-size: 0.88rem; padding: 8px 16px; background-color: #f59e0b; color: #ffffff;">
                    <i class="fa-solid fa-mug-hot"></i> Status: On Break
                </span>
            @elseif($activeSession)
                <span class="badge badge-success" style="font-size: 0.88rem; padding: 8px 16px; background-color: #10b981; color: #ffffff;">
                    <i class="fa-solid fa-circle-play"></i> Status: Currently Working
                </span>
            @elseif($attendance && $attendance->last_logout_at)
                <span class="badge badge-danger" style="font-size: 0.88rem; padding: 8px 16px; background-color: #ef4444; color: #ffffff;">
                    <i class="fa-solid fa-flag-checkered"></i> Status: Shift Completed / Paused
                </span>
            @else
                <span class="badge badge-secondary" style="font-size: 0.88rem; padding: 8px 16px; background-color: #64748b; color: #ffffff;">
                    <i class="fa-solid fa-circle-stop"></i> Status: Logged Out
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Attendance & Action Buttons -->
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 25px;">
    <div>
        <!-- Today's Attendance Widget -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7;"></i> Today's Attendance & Work Session
                </h3>
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                    <span>{{ \Carbon\Carbon::now('Asia/Kolkata')->format('l, d M Y') }}</span>
                    <span style="margin: 0 6px;">|</span>
                    <strong style="color: #0284c7;">
                        <i class="fa-regular fa-clock"></i> Current Time: <span id="current_live_time">--:--:-- --</span> (IST)
                    </strong>
                </div>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin-bottom: 25px; text-align: center;">
                    <div style="background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Clock In</div>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #059669; margin-top: 4px;">
                            {{ $attendance && $attendance->first_login_at ? \Carbon\Carbon::parse($attendance->first_login_at)->timezone('Asia/Kolkata')->format('h:i A') : '--:--' }}
                        </div>
                    </div>
                    <div style="background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Clock Out</div>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #ef4444; margin-top: 4px;">
                            {{ $attendance && $attendance->last_logout_at ? \Carbon\Carbon::parse($attendance->last_logout_at)->timezone('Asia/Kolkata')->format('h:i A') : '--:--' }}
                        </div>
                    </div>
                    <div style="background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Total Working</div>
                        <div id="live_total_working" style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin-top: 4px;">
                            {{ $attendance ? floor($attendance->effective_working_minutes / 60) . 'h ' . ($attendance->effective_working_minutes % 60) . 'm' : '0h 0m' }}
                        </div>
                    </div>
                    <div style="background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Total Break</div>
                        <div id="live_total_break" style="font-size: 1.15rem; font-weight: 700; color: var(--warning); margin-top: 4px;">
                            {{ $attendance ? $attendance->total_break_minutes . ' mins' : '0 mins' }}
                        </div>
                    </div>
                </div>

                <!-- Clock Controls -->
                <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; background: #f8fafc; padding: 20px; border-radius: var(--radius); border: 1px dashed var(--border-color);">
                    @if($activeBreak)
                        <form action="{{ route('employee.break.end') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning" style="padding: 12px 24px; font-size: 1rem;">
                                <i class="fa-solid fa-play"></i> End Break (Started {{ \Carbon\Carbon::parse($activeBreak->started_at)->timezone('Asia/Kolkata')->format('h:i A') }})
                            </button>
                        </form>
                        <form action="{{ route('employee.clock_out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="padding: 12px 24px; font-size: 1rem;" onclick="return confirm('End work session for today?')">
                                <i class="fa-solid fa-door-open"></i> End Session (Clock Out)
                            </button>
                        </form>
                    @elseif($activeSession)
                        <form action="{{ route('employee.break.start') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary" style="padding: 12px 24px; font-size: 1rem;">
                                <i class="fa-solid fa-mug-hot"></i> Start Break
                            </button>
                        </form>
                        <form action="{{ route('employee.clock_out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="padding: 12px 24px; font-size: 1rem;" onclick="return confirm('End work session for today?')">
                                <i class="fa-solid fa-door-open"></i> End Session (Clock Out)
                            </button>
                        </form>
                    @else
                        <form action="{{ route('employee.clock_in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 1rem;">
                                <i class="fa-solid fa-rocket"></i> {{ $attendance && $attendance->first_login_at ? 'Resume Work Session (Clock In)' : 'Start Work Session (Clock In)' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Leave Balances -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plane-departure" style="color: #0284c7;"></i> My Leave Balances
                </h3>
                <a href="{{ route('employee.leave.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-plus"></i> Apply Leave
                </a>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 15px;">
                    @forelse($leaveBalances as $lb)
                    <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 15px; text-align: center;">
                        <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">{{ $lb->leaveType->name }}</div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-top: 4px;">{{ $lb->remaining_days }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">of {{ $lb->allowed_days }} allowed</div>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted);">No leave balances configured.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar Widgets -->
    <div>
        <!-- Announcements Widget -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-bullhorn" style="color: #0284c7;"></i> Company Announcements
                </h3>
            </div>
            <div class="card-body">
                @forelse($announcements as $ann)
                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-bottom: 12px;">
                    <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main);">{{ $ann->title }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 3px;">{{ Str::limit($ann->content, 90) }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px;">{{ $ann->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem;">No announcements.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Notifications Widget -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-bell" style="color: #0284c7;"></i> Recent Notifications
                </h3>
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body">
                @forelse($notifications as $notif)
                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 10px;">
                    <div style="font-weight: 600; font-size: 0.85rem;">{{ $notif->title }}</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $notif->message }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 4px;">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem;">No new notifications.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 1. Live IST Clock
    function updateLiveClock() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Kolkata',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const timeString = new Intl.DateTimeFormat('en-US', options).format(now);
        const clockEl = document.getElementById('current_live_time');
        if (clockEl) {
            clockEl.innerText = timeString;
        }
    }

    updateLiveClock();
    setInterval(updateLiveClock, 1000);

    // 2. Real-Time Working Hours & Minutes Ticking Counter
    const activeSessionStart = @json($activeSession ? \Carbon\Carbon::parse($activeSession->login_at)->timezone('Asia/Kolkata')->toIso8601String() : null);
    const activeBreakStart = @json($activeBreak ? \Carbon\Carbon::parse($activeBreak->started_at)->timezone('Asia/Kolkata')->toIso8601String() : null);
    const prevWorkingMins = @json($attendance ? max(0, $attendance->effective_working_minutes) : 0);
    const prevBreakMins = @json($attendance ? max(0, $attendance->total_break_minutes) : 0);

    function updateLiveCounters() {
        const now = new Date();

        // Working Counter
        if (activeSessionStart && !activeBreakStart) {
            const sessionStart = new Date(activeSessionStart);
            const elapsedSecs = Math.max(0, Math.floor((now - sessionStart) / 1000));
            const totalSecs = (prevWorkingMins * 60) + elapsedSecs;

            const h = Math.floor(totalSecs / 3600);
            const m = Math.floor((totalSecs % 3600) / 60);
            const s = totalSecs % 60;

            const workEl = document.getElementById('live_total_working');
            if (workEl) {
                workEl.innerText = `${h}h ${m}m ${s}s`;
            }
        }

        // Break Counter
        if (activeBreakStart) {
            const breakStart = new Date(activeBreakStart);
            const elapsedSecs = Math.max(0, Math.floor((now - breakStart) / 1000));
            const totalSecs = (prevBreakMins * 60) + elapsedSecs;

            const m = Math.floor(totalSecs / 60);
            const s = totalSecs % 60;

            const breakEl = document.getElementById('live_total_break');
            if (breakEl) {
                breakEl.innerText = `${m}m ${s}s`;
            }
        }
    }

    updateLiveCounters();
    setInterval(updateLiveCounters, 1000);
</script>
@endsection
