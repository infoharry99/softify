@extends('layouts.admin')

@section('title', 'Attendance Dashboard')
@section('page_title', 'Today\'s Attendance & Work Sessions')

@section('content')
<!-- Today's Attendance Overview Stat Widgets -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Employees</div>
        <div class="stat-value">{{ $totalEmployees }}</div>
    </div>
    <div class="stat-card">
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
    <div class="stat-card">
        <div class="stat-label">Absent Today</div>
        <div class="stat-value" style="color: var(--danger);">{{ $absentCount }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Attendance Tracking Matrix</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.attendance.break_violations') }}" class="btn btn-warning btn-sm">
                🚨 View Break Violations
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div style="padding: 15px 20px; background-color: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('admin.attendance.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
            <div style="width: 170px;">
                <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
            </div>

            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email..." value="{{ request('search') }}">
            </div>

            <div style="width: 160px;">
                <select name="status" class="form-control">
                    <option value="">-- All Status --</option>
                    <option value="Present" {{ request('status') === 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Late" {{ request('status') === 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Leave" {{ request('status') === 'Leave' ? 'selected' : '' }}>Leave</option>
                    <option value="Absent" {{ request('status') === 'Absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>First Login</th>
                    <th>Last Logout</th>
                    <th>Breaks Duration</th>
                    <th>Effective Hours</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>
                        <strong>{{ $att->employee->user->name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $att->employee->employee_code }}</div>
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
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openAdjustModal({{ $att->id }}, '{{ $att->status }}', '{{ $att->admin_remarks }}')">
                            ✏️ Adjust
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No attendance records logged for date: {{ $date }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $attendances->links() }}
    </div>
</div>

<!-- Admin Attendance Adjustment Form Modal -->
<div id="adjustModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 450px; border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow-lg);">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 15px;">Correct Attendance Record</h3>
        <form id="adjustForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Attendance Status *</label>
                <select name="status" id="modalStatus" class="form-control" required>
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Leave">Leave</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Correction Reason / Remarks *</label>
                <textarea name="admin_remarks" id="modalRemarks" class="form-control" rows="3" required placeholder="Mandatory explanation for attendance correction"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeAdjustModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Adjustment</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAdjustModal(attendanceId, status, remarks) {
        document.getElementById('adjustForm').action = '/admin/attendance/' + attendanceId + '/adjust';
        document.getElementById('modalStatus').value = status;
        document.getElementById('modalRemarks').value = remarks || '';
        document.getElementById('adjustModal').style.display = 'flex';
    }

    function closeAdjustModal() {
        document.getElementById('adjustModal').style.display = 'none';
    }
</script>
@endsection
