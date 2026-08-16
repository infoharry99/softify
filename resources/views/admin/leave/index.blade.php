@extends('layouts.admin')

@section('title', 'Leave Management')
@section('page_title', 'Leave Applications & Approval Workflow')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Pending Approval</div>
        <div class="stat-value" style="color: var(--warning);">{{ $pendingCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Approved Applications</div>
        <div class="stat-value" style="color: var(--success);">{{ $approvedCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Rejected Applications</div>
        <div class="stat-value" style="color: var(--danger);">{{ $rejectedCount }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Employee Leave Applications</h3>
    </div>

    <!-- Filters -->
    <div style="padding: 15px 20px; background-color: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('admin.leave.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
            <div style="width: 170px;">
                <select name="status" class="form-control">
                    <option value="">-- All Status --</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div style="width: 180px;">
                <select name="leave_type_id" class="form-control">
                    <option value="">-- All Leave Types --</option>
                    @foreach($leaveTypes as $lt)
                        <option value="{{ $lt->id }}" {{ request('leave_type_id') == $lt->id ? 'selected' : '' }}>{{ $lt->name }}</option>
                    @endforeach
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
                    <th>Leave Type</th>
                    <th>Dates & Duration</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <strong>{{ $app->employee->user->name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $app->employee->employee_code }}</div>
                    </td>
                    <td><strong>{{ $app->leaveType->name }}</strong></td>
                    <td>
                        <div>{{ $app->from_date->format('M d, Y') }} - {{ $app->to_date->format('M d, Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--primary);">{{ $app->total_days }} day(s)</div>
                    </td>
                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $app->reason }}
                    </td>
                    <td>
                        <span class="badge {{ $app->status === 'Approved' ? 'badge-success' : ($app->status === 'Pending' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $app->status }}
                        </span>
                        @if($app->admin_remark)
                            <small style="display: block; color: var(--text-muted);">{{ $app->admin_remark }}</small>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($app->status === 'Pending')
                            <div style="display: inline-flex; gap: 5px;">
                                <form action="{{ route('admin.leave.approve', $app->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirmSwalDelete(event, this.form, 'Approve Leave Application?', 'Are you sure you want to approve this employee leave request?')">
                                        ✅ Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.leave.reject', $app->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmSwalDelete(event, this.form, 'Reject Leave Application?', 'Are you sure you want to reject this employee leave request?')">
                                        ❌ Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span style="font-size: 0.8rem; color: var(--text-muted);">Handled by {{ $app->approver->name ?? 'Admin' }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No leave applications found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $applications->links() }}
    </div>
</div>
@endsection
