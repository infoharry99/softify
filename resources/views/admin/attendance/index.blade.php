@extends('layouts.admin')

@section('title', 'Attendance Dashboard')
@section('page_title', 'Today\'s Attendance & Work Sessions')

@section('styles')
<style>
    /* Attendance Navigation Tabs */
    .attendance-tabs-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 2px;
        flex-wrap: wrap;
    }

    .attendance-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        border-radius: 12px 12px 0 0;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
        margin-bottom: -4px;
        background: transparent;
    }

    .attendance-tab-btn:hover {
        color: #00a884;
        background: #f8fafc;
    }

    .attendance-tab-btn.active {
        color: #00a884;
        background: #ffffff;
        border-bottom: 3px solid #00a884;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.02);
    }

    .attendance-tab-btn .tab-count-pill {
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #475569;
    }

    .attendance-tab-btn.active .tab-count-pill {
        background: #00a884;
        color: #ffffff;
    }

    .attendance-tab-btn.active.tab-absent-active {
        color: #ef4444;
        border-bottom-color: #ef4444;
    }

    .attendance-tab-btn.active.tab-absent-active .tab-count-pill {
        background: #ef4444;
        color: #ffffff;
    }

    /* KPI Clickable Cards */
    .stat-card.clickable-card {
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .stat-card.clickable-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: #9ee5d4;
    }

    /* Range Summary Banner */
    .range-summary-strip {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 12px 20px;
        margin: 16px 20px 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        font-size: 0.88rem;
    }

    .range-metrics-group {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .range-metric-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
        color: #1e293b;
    }

    .range-metric-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Quick Preset Date Buttons */
    .preset-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .preset-btn:hover {
        background: #e6f7f3;
        border-color: #00a884;
        color: #00a884;
    }
</style>
@endsection

@section('content')
<!-- Today's Attendance Overview Stat Widgets (Clickable Cards) -->
<div class="stats-grid">
    <div class="stat-card clickable-card" onclick="location.href='{{ route('admin.attendance.index', ['tab' => 'all', 'start_date' => $startDate, 'end_date' => $endDate]) }}'" title="View All History in Date Range">
        <div class="stat-label">Total Employees</div>
        <div class="stat-value">{{ $totalEmployees }}</div>
    </div>

    <div class="stat-card clickable-card" onclick="location.href='{{ route('admin.attendance.index', ['tab' => 'present', 'date' => $date]) }}'" title="View Present List for Selected Date">
        <div class="stat-label">Present Today</div>
        <div class="stat-value" style="color: var(--success);">{{ $presentCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Late Logins</div>
        <div class="stat-value" style="color: var(--warning);">{{ $lateCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">On Leave Today</div>
        <div class="stat-value" style="color: var(--primary);">{{ $leaveCount }}</div>
    </div>

    <div class="stat-card clickable-card" onclick="location.href='{{ route('admin.attendance.index', ['tab' => 'absent', 'date' => $date]) }}'" title="View Absent List for Selected Date">
        <div class="stat-label">Absent Today</div>
        <div class="stat-value" style="color: var(--danger);">{{ $absentCount }}</div>
    </div>
</div>

<!-- Attendance Navigation Tabs Bar -->
<div class="attendance-tabs-bar">
    <a href="{{ route('admin.attendance.index', ['tab' => 'present', 'date' => $date]) }}" class="attendance-tab-btn {{ $activeTab === 'present' ? 'active' : '' }}">
        <i class="fa-solid fa-user-check" style="color: #00a884;"></i>
        <span>Present List</span>
        <span class="tab-count-pill">{{ $presentCount }}</span>
    </a>

    <a href="{{ route('admin.attendance.index', ['tab' => 'absent', 'date' => $date]) }}" class="attendance-tab-btn {{ $activeTab === 'absent' ? 'active tab-absent-active' : '' }}">
        <i class="fa-solid fa-user-xmark" style="color: #ef4444;"></i>
        <span>Absent List</span>
        <span class="tab-count-pill" style="{{ $activeTab === 'absent' ? 'background: #ef4444;' : '' }}">{{ $absentCount }}</span>
    </a>

    <a href="{{ route('admin.attendance.index', ['tab' => 'all', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="attendance-tab-btn {{ $activeTab === 'all' ? 'active' : '' }}">
        <i class="fa-solid fa-calendar-days" style="color: #0284c7;"></i>
        <span>All / Date Range Attendance</span>
        <span class="tab-count-pill">Range</span>
    </a>

    <div style="margin-left: auto;">
        <a href="{{ route('admin.attendance.break_violations') }}" class="btn btn-warning btn-sm" style="font-weight: 700; border-radius: 8px;">
            🚨 View Break Violations
        </a>
    </div>
</div>

<!-- Main Attendance Tracking Matrix Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($activeTab === 'absent')
                <i class="fa-solid fa-user-xmark" style="color: #ef4444;"></i> Absent Employees List ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})
            @elseif($activeTab === 'all')
                <i class="fa-solid fa-calendar-week" style="color: #0284c7;"></i> Comprehensive Attendance Matrix ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})
            @else
                <i class="fa-solid fa-user-check" style="color: #00a884;"></i> Present Attendance Matrix ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})
            @endif
        </h3>

        <div style="font-size: 0.84rem; color: #64748b; font-weight: 600;">
            @if($activeTab === 'absent')
                Showing <strong style="color: #ef4444;">{{ $absentList ? $absentList->total() : $absentCount }}</strong> Absent Employees
            @elseif($activeTab === 'all')
                Showing <strong style="color: #0284c7;">{{ $attendances ? $attendances->total() : 0 }}</strong> Records in Range
            @else
                Showing <strong style="color: #00a884;">{{ $attendances ? $attendances->total() : $presentCount }}</strong> Present Employees
            @endif
        </div>
    </div>

    <!-- Filter Bar Header -->
    <div style="padding: 16px 20px; background-color: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('admin.attendance.index') }}" method="GET" id="attendanceFilterForm">
            <input type="hidden" name="tab" value="{{ $activeTab }}">

            @if($activeTab === 'all')
                <!-- 1. Filters for ALL / DATE RANGE Tab -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- Date Range Inputs + Quick Presets -->
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">Start Date:</label>
                            <input type="date" name="start_date" id="inputStartDate" class="form-control" value="{{ $startDate }}" style="width: 155px; padding: 7px 10px;">
                        </div>

                        <div style="display: flex; align-items: center; gap: 6px;">
                            <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">End Date:</label>
                            <input type="date" name="end_date" id="inputEndDate" class="form-control" value="{{ $endDate }}" style="width: 155px; padding: 7px 10px;">
                        </div>

                        <!-- Quick Presets -->
                        <div style="display: flex; gap: 6px; align-items: center; margin-left: 6px;">
                            <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Presets:</span>
                            <button type="button" class="preset-btn" onclick="setPresetDates('today')">Today</button>
                            <button type="button" class="preset-btn" onclick="setPresetDates('week')">Last 7 Days</button>
                            <button type="button" class="preset-btn" onclick="setPresetDates('this_month')">This Month</button>
                            <button type="button" class="preset-btn" onclick="setPresetDates('last_month')">Last Month</button>
                        </div>
                    </div>

                    <!-- Search + Employee Dropdown + Status Filter -->
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                        <div style="width: 200px;">
                            <select name="employee_id" class="form-control" style="padding: 7px 10px; font-size: 0.88rem;">
                                <option value="">-- All Employees --</option>
                                @foreach($allEmployees as $emp)
                                    <option value="{{ $emp->id }}" {{ (string)$selectedEmployeeId === (string)$emp->id ? 'selected' : '' }}>
                                        {{ $emp->user->name ?? 'Emp' }} ({{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="flex: 1; min-width: 200px;">
                            <input type="text" name="search" class="form-control" placeholder="Search by employee name, email..." value="{{ $search }}" style="padding: 7px 12px; font-size: 0.88rem;">
                        </div>

                        <div style="width: 160px;">
                            <select name="status" class="form-control" style="padding: 7px 10px; font-size: 0.88rem;">
                                <option value="">-- All Status --</option>
                                <option value="Present" {{ $statusFilter === 'Present' ? 'selected' : '' }}>Present</option>
                                <option value="Late" {{ $statusFilter === 'Late' ? 'selected' : '' }}>Late</option>
                                <option value="Half Day" {{ $statusFilter === 'Half Day' ? 'selected' : '' }}>Half Day</option>
                                <option value="Leave" {{ $statusFilter === 'Leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="Absent" {{ $statusFilter === 'Absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm" style="background-color: #00a884; border-color: #00a884; padding: 7px 16px; font-weight: 700; border-radius: 8px;">
                            <i class="fa-solid fa-filter"></i> Apply Filter
                        </button>

                        <a href="{{ route('admin.attendance.index', ['tab' => 'all']) }}" class="btn btn-secondary btn-sm" style="padding: 7px 14px; border-radius: 8px;">
                            Reset
                        </a>
                    </div>
                </div>

            @else
                <!-- 2. Filters for PRESENT & ABSENT Tabs (Single Date Filter) -->
                <div style="display: flex; flex-wrap: wrap; gap: 14px; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">Date:</label>
                        <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()" style="width: 160px; padding: 7px 10px;">
                    </div>

                    <div style="flex: 1; min-width: 220px;">
                        <input type="text" name="search" class="form-control" placeholder="Search by employee name, email, code..." value="{{ $search }}" style="padding: 7px 12px; font-size: 0.88rem;">
                    </div>

                    @if($activeTab === 'present')
                        <div style="width: 160px;">
                            <select name="status" class="form-control" style="padding: 7px 10px; font-size: 0.88rem;">
                                <option value="">-- All Status --</option>
                                <option value="Present" {{ $statusFilter === 'Present' ? 'selected' : '' }}>Present</option>
                                <option value="Late" {{ $statusFilter === 'Late' ? 'selected' : '' }}>Late</option>
                                <option value="Half Day" {{ $statusFilter === 'Half Day' ? 'selected' : '' }}>Half Day</option>
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #00a884; border-color: #00a884; padding: 7px 16px; font-weight: 700; border-radius: 8px;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>

                    <a href="{{ route('admin.attendance.index', ['tab' => $activeTab, 'date' => now()->toDateString()]) }}" class="btn btn-secondary btn-sm" style="padding: 7px 14px; border-radius: 8px;">
                        Today
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Range Statistics Strip (Only in All Tab) -->
    @if($activeTab === 'all')
        <div class="range-summary-strip">
            <span style="font-weight: 800; color: #0f172a;">
                <i class="fa-solid fa-chart-line" style="color: #00a884;"></i> Range Summary ({{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}):
            </span>

            <div class="range-metrics-group">
                <span class="range-metric-item">
                    <span class="range-metric-dot" style="background: #0284c7;"></span>
                    Total Records: {{ $rangeTotal }}
                </span>
                <span class="range-metric-item">
                    <span class="range-metric-dot" style="background: #00a884;"></span>
                    Present: {{ $rangePresent }}
                </span>
                <span class="range-metric-item">
                    <span class="range-metric-dot" style="background: #f59e0b;"></span>
                    Late: {{ $rangeLate }}
                </span>
                <span class="range-metric-item">
                    <span class="range-metric-dot" style="background: #6366f1;"></span>
                    On Leave: {{ $rangeLeave }}
                </span>
                <span class="range-metric-item">
                    <span class="range-metric-dot" style="background: #ef4444;"></span>
                    Absent: {{ $rangeAbsent }}
                </span>
            </div>
        </div>
    @endif

    <!-- TABLE AREA -->
    <div class="table-responsive" style="margin-top: 10px;">
        <table class="table">
            <thead>
                <tr>
                    @if($activeTab === 'all')
                        <th style="width: 140px;">Date</th>
                    @endif
                    <th>Employee Details</th>
                    <th>Department / Role</th>
                    <th>First Login</th>
                    <th>Last Logout</th>
                    <th>Breaks</th>
                    <th>Effective Hours</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. TAB: PRESENT LIST -->
                @if($activeTab === 'present')
                    @forelse($attendances as $att)
                    <tr>
                        <td>
                            <strong>{{ optional(optional($att->employee)->user)->name ?? 'Unknown Employee' }}</strong>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ optional($att->employee)->employee_code ?? '' }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 600; font-size: 0.85rem; color: #334155;">{{ optional(optional($att->employee)->joiningDetail)->department ?? 'General' }}</span>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ optional(optional($att->employee)->joiningDetail)->designation ?? '' }}</div>
                        </td>
                        <td>{{ $att->first_login_at ? $att->first_login_at->format('h:i A') : '--:--' }}</td>
                        <td>{{ $att->last_logout_at ? $att->last_logout_at->format('h:i A') : '--:--' }}</td>
                        <td>{{ $att->total_break_minutes }} mins</td>
                        <td><strong>{{ floor($att->effective_working_minutes / 60) }}h {{ $att->effective_working_minutes % 60 }}m</strong></td>
                        <td>
                            <span class="badge {{ $att->status === 'Present' ? 'badge-success' : ($att->status === 'Late' ? 'badge-warning' : 'badge-danger') }}">
                                {{ $att->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openAdjustModal('{{ optional(optional($att->employee)->user)->name }}', '{{ $att->employee_id }}', '{{ $att->date instanceof \Carbon\Carbon ? $att->date->format('Y-m-d') : $att->date }}', '{{ $att->status }}', '{{ $att->first_login_at ? $att->first_login_at->format('H:i') : '10:00' }}', '{{ $att->last_logout_at ? $att->last_logout_at->format('H:i') : '19:00' }}', '{{ addslashes($att->admin_remarks ?? '') }}')">
                                ✏️ Adjust
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-solid fa-user-slash" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                            No employees logged present for date: <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>.
                        </td>
                    </tr>
                    @endforelse

                <!-- 2. TAB: ABSENT LIST -->
                @elseif($activeTab === 'absent')
                    @forelse($absentList as $item)
                    @php
                        $emp = $item['employee'];
                        $att = $item['attendance'];
                        $isLeave = $item['status'] === 'Leave';
                    @endphp
                    <tr>
                        <td>
                            <strong style="color: #0f172a;">{{ optional($emp->user)->name ?? 'Employee' }}</strong>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ $emp->employee_code ?? '' }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 600; font-size: 0.85rem; color: #334155;">{{ optional($emp->joiningDetail)->department ?? 'General' }}</span>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ optional($emp->joiningDetail)->designation ?? '' }}</div>
                        </td>
                        <td style="color: #94a3b8;">--:--</td>
                        <td style="color: #94a3b8;">--:--</td>
                        <td style="color: #94a3b8;">0 mins</td>
                        <td style="color: #94a3b8;"><strong>0h 0m</strong></td>
                        <td>
                            @if($isLeave)
                                <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-weight: 700;">
                                    <i class="fa-solid fa-umbrella-beach"></i> On Leave
                                </span>
                            @else
                                <span class="badge badge-danger" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-weight: 700;">
                                    <i class="fa-solid fa-circle-xmark"></i> Absent
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-sm" style="background: #00a884; color: #fff; font-weight: 700; border: none; border-radius: 8px; padding: 5px 12px;" onclick="openAdjustModal('{{ optional($emp->user)->name }}', '{{ $emp->id }}', '{{ $date }}', 'Present', '10:00', '19:00', '')">
                                ✏️ Mark Present
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #047857; padding: 40px; background: #f0fdf4;">
                            <i class="fa-solid fa-circle-check" style="font-size: 2.2rem; color: #00a884; margin-bottom: 10px; display: block;"></i>
                            <strong>All employees are accounted for!</strong> No absentees detected for date: <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>.
                        </td>
                    </tr>
                    @endforelse

                <!-- 3. TAB: ALL / DATE RANGE HISTORY -->
                @else
                    @forelse($attendances as $att)
                    <tr>
                        <td>
                            <strong style="color: #0f172a;">{{ $att->date instanceof \Carbon\Carbon ? $att->date->format('d M Y') : \Carbon\Carbon::parse($att->date)->format('d M Y') }}</strong>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ \Carbon\Carbon::parse($att->date)->format('l') }}</div>
                        </td>
                        <td>
                            <strong>{{ optional(optional($att->employee)->user)->name ?? 'Unknown Employee' }}</strong>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ optional($att->employee)->employee_code ?? '' }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 600; font-size: 0.85rem; color: #334155;">{{ optional(optional($att->employee)->joiningDetail)->department ?? 'General' }}</span>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ optional(optional($att->employee)->joiningDetail)->designation ?? '' }}</div>
                        </td>
                        <td>{{ $att->first_login_at ? $att->first_login_at->format('h:i A') : '--:--' }}</td>
                        <td>{{ $att->last_logout_at ? $att->last_logout_at->format('h:i A') : '--:--' }}</td>
                        <td>{{ $att->total_break_minutes }} mins</td>
                        <td><strong>{{ floor($att->effective_working_minutes / 60) }}h {{ $att->effective_working_minutes % 60 }}m</strong></td>
                        <td>
                            <span class="badge {{ $att->status === 'Present' ? 'badge-success' : ($att->status === 'Late' ? 'badge-warning' : ($att->status === 'Leave' ? 'badge-info' : 'badge-danger')) }}">
                                {{ $att->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openAdjustModal('{{ optional(optional($att->employee)->user)->name }}', '{{ $att->employee_id }}', '{{ $att->date instanceof \Carbon\Carbon ? $att->date->format('Y-m-d') : $att->date }}', '{{ $att->status }}', '{{ $att->first_login_at ? $att->first_login_at->format('H:i') : '10:00' }}', '{{ $att->last_logout_at ? $att->last_logout_at->format('H:i') : '19:00' }}', '{{ addslashes($att->admin_remarks ?? '') }}')">
                                ✏️ Adjust
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-regular fa-calendar-xmark" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                            No attendance logs match the selected date range & filters.
                        </td>
                    </tr>
                    @endforelse
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
        @if($activeTab === 'absent' && $absentList)
            {{ $absentList->links() }}
        @elseif($attendances)
            {{ $attendances->links() }}
        @endif
    </div>
</div>

<!-- Unified Attendance Adjustment / Mark Attendance Modal -->
<div id="adjustModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 18px; padding: 28px 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #00a884;"></i> Adjust / Mark Attendance
            </h3>
            <button type="button" onclick="closeAdjustModal()" style="background:none; border:none; font-size:1.3rem; color:#94a3b8; cursor:pointer;">&times;</button>
        </div>

        <form id="adjustForm" action="{{ route('admin.attendance.adjust_or_create') }}" method="POST">
            @csrf
            <input type="hidden" name="employee_id" id="modalEmployeeId">
            <input type="hidden" name="date" id="modalDate">

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; margin-bottom: 16px;">
                <div style="font-size: 0.88rem; font-weight: 700; color: #0f172a;" id="modalEmployeeName">Employee Name</div>
                <div style="font-size: 0.8rem; color: #64748b;" id="modalDateDisplay">Date</div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Attendance Status *</label>
                <select name="status" id="modalStatus" class="form-control" required style="border-radius: 8px;">
                    <option value="Present">Present (Full Day)</option>
                    <option value="Late">Late Login</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Leave">On Leave</option>
                    <option value="Absent">Absent</option>
                    <option value="Holiday">Holiday</option>
                    <option value="Week Off">Week Off</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">First Login Time</label>
                    <input type="time" name="first_login_at" id="modalFirstLogin" class="form-control" value="10:00" style="border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Last Logout Time</label>
                    <input type="time" name="last_logout_at" id="modalLastLogout" class="form-control" value="19:00" style="border-radius: 8px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Correction Reason / Admin Remarks *</label>
                <textarea name="admin_remarks" id="modalRemarks" class="form-control" rows="2" required placeholder="Reason for attendance mark or correction" style="border-radius: 8px;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeAdjustModal()" style="border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background-color: #00a884; border-color: #00a884; font-weight: 700; border-radius: 8px;">
                    Save Adjustment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAdjustModal(empName, empId, dateStr, status, firstLogin, lastLogout, remarks) {
        document.getElementById('modalEmployeeName').innerText = empName;
        document.getElementById('modalDateDisplay').innerText = 'Date: ' + dateStr;
        document.getElementById('modalEmployeeId').value = empId;
        document.getElementById('modalDate').value = dateStr;
        document.getElementById('modalStatus').value = status || 'Present';
        document.getElementById('modalFirstLogin').value = firstLogin || '10:00';
        document.getElementById('modalLastLogout').value = lastLogout || '19:00';
        document.getElementById('modalRemarks').value = remarks || 'Marked by Administrator';

        document.getElementById('adjustModal').style.display = 'flex';
    }

    function closeAdjustModal() {
        document.getElementById('adjustModal').style.display = 'none';
    }

    // Quick presets for Date Range tab
    function setPresetDates(type) {
        const startInput = document.getElementById('inputStartDate');
        const endInput = document.getElementById('inputEndDate');
        const today = new Date();
        const formatDate = (d) => d.toISOString().split('T')[0];

        if (type === 'today') {
            startInput.value = formatDate(today);
            endInput.value = formatDate(today);
        } else if (type === 'week') {
            const past7 = new Date();
            past7.setDate(today.getDate() - 7);
            startInput.value = formatDate(past7);
            endInput.value = formatDate(today);
        } else if (type === 'this_month') {
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            startInput.value = formatDate(firstDay);
            endInput.value = formatDate(today);
        } else if (type === 'last_month') {
            const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            startInput.value = formatDate(firstDayLastMonth);
            endInput.value = formatDate(lastDayLastMonth);
        }

        document.getElementById('attendanceFilterForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdjustModal();
        }
    });
</script>
@endsection
