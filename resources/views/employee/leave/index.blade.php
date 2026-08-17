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
                        <th>Reason & Evidence</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td><strong>{{ $app->leaveType->name }}</strong></td>
                        <td>
                            <div>{{ $app->from_date->format('M d, Y') }} - {{ $app->to_date->format('M d, Y') }}</div>
                        </td>
                        <td>{{ $app->total_days }} day(s) {{ $app->is_half_day ? '(Half)' : '' }}</td>
                        <td style="max-width: 180px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $app->reason }}">
                                {{ $app->reason }}
                            </div>
                            @if($app->attachment)
                                <div style="margin-top: 3px;">
                                    <span class="badge" style="font-size: 0.68rem; background-color: #e0f2fe; color: #0284c7; border: 1px solid #7dd3fc; font-weight: 600;">
                                        📎 Attachment
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $app->status === 'Approved' ? 'badge-success' : ($app->status === 'Pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ $app->status }}
                            </span>
                            @if($app->admin_remark)
                                <small style="display: block; color: var(--text-muted); margin-top: 2px;">{{ $app->admin_remark }}</small>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="showEmpLeaveDetails({{ json_encode($app) }}, '{{ addslashes($app->leaveType->name) }}')" style="font-weight: 600; border-radius: 8px; font-size: 0.78rem;">
                                👁️ View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
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
                    <label class="checkbox-container">
                        <input type="checkbox" name="is_half_day" value="1">
                        Half Day Leave
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Reason for Leave *</label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="State reason clearly..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Attachment (Optional - Medical certificate, tickets, etc.)</label>
                    <input type="file" name="attachment_file" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Application</button>
            </form>
        </div>
    </div>
</div>

<!-- Employee Leave Details Modal -->
<div class="modal fade" id="empLeaveDetailsModal" tabindex="-1" aria-labelledby="empLeaveDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title" id="empLeaveDetailsModalLabel" style="font-weight: 700; color: #0f172a; font-size: 1.05rem;">
                    📋 My Leave Application Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 8px;">
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Leave Type</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 2px;" id="empLeaveType">-</div>
                    </div>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 8px;">
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Duration & Dates</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #00a884; margin-top: 2px;" id="empDates">-</div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 0.82rem; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">📝 My Applied Reason:</label>
                    <div id="empReason" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 14px; border-radius: 8px; font-size: 0.9rem; color: #1e293b; min-height: 80px; max-height: 200px; overflow-y: auto; white-space: pre-wrap; word-break: break-word; line-height: 1.5;">-</div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 0.82rem; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">📎 Uploaded Document:</label>
                    <div id="empAttachmentContainer">
                        <div style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">No attachment uploaded.</div>
                    </div>
                </div>

                <div id="empHrRemarkSection" style="display: none; background: #fffbe6; border: 1px solid #ffe58f; padding: 14px; border-radius: 8px;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #d48806; text-transform: uppercase; margin-bottom: 4px;">🛡️ HR Feedback / Remarks:</div>
                    <div id="empHrRemark" style="font-size: 0.88rem; color: #595959;"></div>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showEmpLeaveDetails(app, leaveTypeName) {
    document.getElementById('empLeaveType').innerText = leaveTypeName;
    
    var totalDays = app.total_days + ' day(s)' + (app.is_half_day ? ' (Half Day)' : '');
    var fromStr = new Date(app.from_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    var toStr = new Date(app.to_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('empDates').innerText = fromStr + ' - ' + toStr + ' (' + totalDays + ')';
    
    document.getElementById('empReason').innerText = app.reason || 'No specific reason entered.';
    
    // Attachment section
    var attachContainer = document.getElementById('empAttachmentContainer');
    if (app.attachment) {
        var downloadUrl = '{{ url("/admin/leave") }}/' + app.id + '/attachment';
        var previewUrl = '{{ url("/admin/leave") }}/' + app.id + '/attachment-preview';
        attachContainer.innerHTML = 
            '<div style="display: flex; gap: 10px; align-items: center; background: #f0f9ff; border: 1px solid #bae6fd; padding: 12px 16px; border-radius: 8px;">' +
                '<div style="font-size: 1.4rem; color: #0284c7;"><i class="fa-solid fa-file-pdf"></i></div>' +
                '<div style="flex: 1;">' +
                    '<div style="font-size: 0.85rem; font-weight: 700; color: #0369a1;">Leave Support Attachment</div>' +
                    '<div style="font-size: 0.75rem; color: #0284c7;">Document uploaded</div>' +
                '</div>' +
                '<div style="display: flex; gap: 6px;">' +
                    '<a href="' + previewUrl + '" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.78rem; font-weight: 600; color: #0284c7; border-color: #38bdf8;">👁️ Preview</a>' +
                    '<a href="' + downloadUrl + '" class="btn btn-primary btn-sm" style="font-size: 0.78rem; font-weight: 700; background-color: #0284c7; border-color: #0284c7;">📥 Download</a>' +
                '</div>' +
            '</div>';
    } else {
        attachContainer.innerHTML = '<div style="font-size: 0.85rem; color: #94a3b8; font-style: italic; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">No attachment document uploaded.</div>';
    }
    
    // HR Remarks Section
    var hrSection = document.getElementById('empHrRemarkSection');
    if (app.admin_remark) {
        hrSection.style.display = 'block';
        document.getElementById('empHrRemark').innerText = app.admin_remark;
    } else {
        hrSection.style.display = 'none';
    }
    
    $('#empLeaveDetailsModal').modal('show');
}
</script>
@endsection
