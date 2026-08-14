@extends('layouts.employee')

@section('title', 'My Leave Management')
@section('page_title', 'My Leave Balances & Applications')

@section('content')
<!-- Leave Balances -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">🏖️ My Annual Leave Balances ({{ \Carbon\Carbon::now()->year }})</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            @foreach($balances as $b)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 15px; text-align: center;">
                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ $b->leaveType->name }}</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin: 6px 0;">{{ $b->remaining_days }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Used: {{ $b->used_days }} / Allowed: {{ $b->allowed_days }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 360px; gap: 25px;">
    <!-- My Leave Applications Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">My Leave History</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>From - To</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td><strong>{{ $app->leaveType->name }}</strong></td>
                        <td>
                            <div>{{ $app->from_date->format('M d, Y') }} - {{ $app->to_date->format('M d, Y') }}</div>
                        </td>
                        <td>{{ $app->total_days }} day(s)</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            You have not applied for any leave yet.
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

    <!-- Apply Leave Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📝 Apply for Leave</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('employee.leave.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Leave Type *</label>
                    <select name="leave_type_id" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">From Date *</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">To Date *</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; cursor: pointer;">
                        <input type="checkbox" name="is_half_day" value="1"> Is Half Day?
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Reason *</label>
                    <textarea name="reason" class="form-control" rows="3" required placeholder="Specify reason for leave application"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Attachment (Optional)</label>
                    <input type="file" name="attachment_file" class="form-control" accept="image/*,.pdf">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Leave Application</button>
            </form>
        </div>
    </div>
</div>
@endsection
