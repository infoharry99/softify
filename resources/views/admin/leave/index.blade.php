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

<!-- Company Leave Policy & Quotas Configurator (Admin Dynamic Settings) -->
<div class="card" style="margin-bottom: 25px; border-top: 4px solid var(--primary);">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 class="card-title" style="margin-bottom: 2px;">⚙️ Company Leave Policy & Annual Quotas</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Configure annual allowed leave days per type according to company policy. Updating quotas automatically syncs active employee balances.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openAddLeaveTypeModal()" style="font-weight: 600;">
            <i class="fa-solid fa-plus-circle"></i> Add Custom Leave Type
        </button>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.leave.policy.update') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px;">
                @foreach($leaveTypes as $lt)
                    <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 15px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin: 0;">Leave Type Name</label>
                            <span class="badge {{ $lt->is_paid ? 'badge-success' : 'badge-secondary' }}" style="font-size: 0.7rem;">
                                {{ $lt->is_paid ? 'Paid Leave' : 'Unpaid' }}
                            </span>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <input type="text" name="types[{{ $lt->id }}][name]" value="{{ $lt->name }}" class="form-control" required style="font-size: 0.88rem; font-weight: 600;">
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 4px;">
                                <div style="flex: 1;">
                                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; margin-bottom: 2px;">Allowed Days / Year</label>
                                    <input type="number" step="0.5" min="0" max="365" name="types[{{ $lt->id }}][days_allowed_per_year]" value="{{ $lt->days_allowed_per_year }}" class="form-control" required style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; justify-content: flex-end;">
                                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; margin-bottom: 4px;">Paid Status</label>
                                    <label style="display: inline-flex; align-items: center; gap: 4px; cursor: pointer; font-size: 0.78rem; font-weight: 600;">
                                        <input type="checkbox" name="types[{{ $lt->id }}][is_paid]" value="1" {{ $lt->is_paid ? 'checked' : '' }}>
                                        Is Paid
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if(!in_array($lt->slug, ['casual-leave', 'sick-leave', 'earned-leave']))
                            <div style="margin-top: 10px; text-align: right;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="confirmDeleteLeaveType({{ $lt->id }}, '{{ addslashes($lt->name) }}')" style="color: #ef4444; font-size: 0.72rem; padding: 2px 8px;">
                                    🗑️ Remove
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 20px; text-align: right; pt: 15px; border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-success" onclick="return confirmSwalAction(event, this.form, 'Save & Sync Leave Policy?', 'This will update annual allowed leave quotas for all employees.', '✅ Yes, Update Policy', '#00a884', 'question')" style="font-weight: 700;">
                    💾 Save Policy & Sync All Employee Quotas
                </button>
            </div>
        </form>
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
                    <th>Reason & Evidence</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                @if(!$app->employee || !$app->employee->user) @continue @endif
                <tr>
                    <td>
                        <strong>{{ $app->employee->user->name ?? 'N/A' }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $app->employee->employee_code ?? 'N/A' }}</div>
                    </td>
                    <td><strong>{{ $app->leaveType->name ?? 'Leave' }}</strong></td>
                    <td>
                        <div>{{ $app->from_date->format('M d, Y') }} - {{ $app->to_date->format('M d, Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--primary); font-weight: 600;">{{ $app->total_days }} day(s) {{ $app->is_half_day ? '(Half Day)' : '' }}</div>
                    </td>
                    <td style="max-width: 220px;">
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $app->reason }}">
                            {{ $app->reason }}
                        </div>
                        @if($app->attachment)
                            <div style="margin-top: 4px;">
                                <span class="badge" style="font-size: 0.7rem; background-color: #e0f2fe; color: #0284c7; border: 1px solid #7dd3fc; font-weight: 600;">
                                    📎 Attachment Included
                                </span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $app->status === 'Approved' ? 'badge-success' : ($app->status === 'Pending' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $app->status }}
                        </span>
                        @if($app->admin_remark)
                            <small style="display: block; color: var(--text-muted); margin-top: 3px;">{{ $app->admin_remark }}</small>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="showLeaveDetails({{ json_encode($app) }}, '{{ addslashes($app->employee->user->name ?? 'N/A') }}', '{{ addslashes($app->employee->employee_code ?? 'N/A') }}', '{{ addslashes($app->leaveType->name ?? 'Leave') }}', '{{ addslashes($app->approver->name ?? '') }}')" style="font-weight: 600; border-radius: 8px; font-size: 0.78rem;">
                                👁️ View
                            </button>

                            @if($app->status === 'Pending')
                                <form action="{{ route('admin.leave.approve', $app->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirmSwalAction(event, this.form, 'Approve Leave Application?', 'Are you sure you want to approve this employee leave request?', '✅ Yes, Approve', '#00a884', 'question')">
                                        ✅ Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.leave.reject', $app->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmSwalAction(event, this.form, 'Reject Leave Application?', 'Are you sure you want to reject this employee leave request?', '❌ Yes, Reject', '#ef4444', 'warning')">
                                        ❌ Reject
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.78rem; color: var(--text-muted);">Handled</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 35px;">
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

<!-- Standalone Custom Popup Modal (100% Hidden by default, ONLY opens on clicking View) -->
<div id="leaveDetailsModal" onclick="closeLeaveModalOnOverlay(event)" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); z-index: 99999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; width: 100%; max-width: 620px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; animation: fadeInModal 0.2s ease-out;">
        <!-- Header -->
        <div style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 700; color: #0f172a; font-size: 1.15rem; margin: 0; display: flex; align-items: center; gap: 8px;">
                📋 Leave Application Details
            </h3>
            <button type="button" onclick="closeLeaveModal()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; line-height: 1; outline: none; padding: 0 6px;">&times;</button>
        </div>

        <!-- Body -->
        <div style="padding: 24px; overflow-y: auto;">
            <!-- Employee Summary Header -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 15px 18px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700; color: #166534;" id="detailEmpName">-</div>
                    <div style="font-size: 0.8rem; color: #15803d;" id="detailEmpCode">-</div>
                </div>
                <div id="detailStatusBadge"></div>
            </div>

            <!-- Key Metrics Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Leave Type</div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 2px;" id="detailLeaveType">-</div>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Duration & Dates</div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #00a884; margin-top: 2px;" id="detailDates">-</div>
                </div>
            </div>

            <!-- Full Reason Box -->
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.82rem; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">📝 Applied Reason / Details:</label>
                <div id="detailReason" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 14px; border-radius: 8px; font-size: 0.9rem; color: #1e293b; min-height: 80px; max-height: 200px; overflow-y: auto; white-space: pre-wrap; word-break: break-word; line-height: 1.5;">-</div>
            </div>

            <!-- Attachment Document Section -->
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.82rem; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">📎 Attachment / Medical Certificate / Document:</label>
                <div id="detailAttachmentContainer">
                    <div style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">No attachment uploaded.</div>
                </div>
            </div>

            <!-- HR Remark Section -->
            <div id="detailHrRemarkSection" style="display: none; background: #fffbe6; border: 1px solid #ffe58f; padding: 14px; border-radius: 8px; margin-bottom: 10px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #d48806; text-transform: uppercase; margin-bottom: 4px;">🛡️ HR Approval Remark:</div>
                <div id="detailHrRemark" style="font-size: 0.88rem; color: #595959;"></div>
                <div id="detailApproverInfo" style="font-size: 0.78rem; color: #8c8c8c; margin-top: 4px;"></div>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div id="modalActionContainer"></div>
            <button type="button" onclick="closeLeaveModal()" class="btn btn-secondary" style="border-radius: 8px; font-weight: 600; padding: 8px 18px;">Close</button>
        </div>
    </div>
</div>

<style>
@keyframes fadeInModal {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
function showLeaveDetails(app, empName, empCode, leaveTypeName, approverName) {
    document.getElementById('detailEmpName').innerText = empName;
    document.getElementById('detailEmpCode').innerText = empCode ? 'ID: ' + empCode : '';
    document.getElementById('detailLeaveType').innerText = leaveTypeName;
    
    var totalDays = app.total_days + ' day(s)' + (app.is_half_day ? ' (Half Day)' : '');
    var fromStr = new Date(app.from_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    var toStr = new Date(app.to_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('detailDates').innerText = fromStr + ' - ' + toStr + ' (' + totalDays + ')';
    
    document.getElementById('detailReason').innerText = app.reason || 'No specific reason entered.';
    
    // Status Badge
    var statusBadgeHtml = '';
    if (app.status === 'Approved') {
        statusBadgeHtml = '<span class="badge badge-success" style="font-size: 0.9rem; padding: 6px 14px;">Approved</span>';
    } else if (app.status === 'Pending') {
        statusBadgeHtml = '<span class="badge badge-warning" style="font-size: 0.9rem; padding: 6px 14px;">Pending Approval</span>';
    } else {
        statusBadgeHtml = '<span class="badge badge-danger" style="font-size: 0.9rem; padding: 6px 14px;">Rejected</span>';
    }
    document.getElementById('detailStatusBadge').innerHTML = statusBadgeHtml;
    
    // Attachment section
    var attachContainer = document.getElementById('detailAttachmentContainer');
    if (app.attachment) {
        var downloadUrl = '{{ url("/admin/leave") }}/' + app.id + '/attachment';
        var previewUrl = '{{ url("/admin/leave") }}/' + app.id + '/attachment-preview';
        attachContainer.innerHTML = 
            '<div style="display: flex; gap: 10px; align-items: center; background: #f0f9ff; border: 1px solid #bae6fd; padding: 12px 16px; border-radius: 8px;">' +
                '<div style="font-size: 1.4rem; color: #0284c7;"><i class="fa-solid fa-file-pdf"></i></div>' +
                '<div style="flex: 1;">' +
                    '<div style="font-size: 0.85rem; font-weight: 700; color: #0369a1;">Leave Support Attachment</div>' +
                    '<div style="font-size: 0.75rem; color: #0284c7;">Document uploaded by employee</div>' +
                '</div>' +
                '<div style="display: flex; gap: 6px;">' +
                    '<a href="' + previewUrl + '" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.78rem; font-weight: 600; color: #0284c7; border-color: #38bdf8;">👁️ Preview Document</a>' +
                    '<a href="' + downloadUrl + '" class="btn btn-primary btn-sm" style="font-size: 0.78rem; font-weight: 700; background-color: #0284c7; border-color: #0284c7;">📥 Download</a>' +
                '</div>' +
            '</div>';
    } else {
        attachContainer.innerHTML = '<div style="font-size: 0.85rem; color: #94a3b8; font-style: italic; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">No attachment document uploaded.</div>';
    }
    
    // HR Remarks Section
    var hrSection = document.getElementById('detailHrRemarkSection');
    if (app.admin_remark || approverName) {
        hrSection.style.display = 'block';
        document.getElementById('detailHrRemark').innerText = app.admin_remark || 'No remark entered.';
        document.getElementById('detailApproverInfo').innerText = approverName ? 'Action handled by: ' + approverName : '';
    } else {
        hrSection.style.display = 'none';
    }

    // Modal Actions (Approve / Reject forms if pending)
    var actionContainer = document.getElementById('modalActionContainer');
    if (app.status === 'Pending') {
        var approveUrl = '{{ url("/admin/leave") }}/' + app.id + '/approve';
        var rejectUrl = '{{ url("/admin/leave") }}/' + app.id + '/reject';
        actionContainer.innerHTML = 
            '<div style="display: flex; gap: 8px;">' +
                '<form action="' + approveUrl + '" method="POST" style="display:inline;">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '<button type="submit" class="btn btn-success btn-sm" onclick="return confirmSwalAction(event, this.form, \'Approve Leave Application?\', \'Are you sure you want to approve this employee leave request?\', \'✅ Yes, Approve\', \'#00a884\', \'question\')">✅ Approve Leave</button>' +
                '</form>' +
                '<form action="' + rejectUrl + '" method="POST" style="display:inline;">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirmSwalAction(event, this.form, \'Reject Leave Application?\', \'Are you sure you want to reject this employee leave request?\', \'❌ Yes, Reject\', \'#ef4444\', \'warning\')">❌ Reject Leave</button>' +
                '</form>' +
            '</div>';
    } else {
        actionContainer.innerHTML = '';
    }
    
    var modal = document.getElementById('leaveDetailsModal');
    modal.style.display = 'flex';
}

<!-- Modal: Add New Custom Leave Type -->
<div id="addLeaveTypeModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;" onclick="closeAddLeaveTypeModalOnOverlay(event)">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; animation: modalSlideUp 0.25s ease-out;" onclick="event.stopPropagation();">
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #0f172a;">➕ Add Custom Leave Type</h3>
            <button type="button" onclick="closeAddLeaveTypeModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer;">✕</button>
        </div>
        <form action="{{ route('admin.leave.type.store') }}" method="POST" style="padding: 24px;">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: 600;">Leave Type Name *</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Maternity Leave, Comp-Off, Marriage Leave">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: 600;">Allowed Days Per Year *</label>
                <input type="number" step="0.5" min="0" max="365" name="days_allowed_per_year" class="form-control" required value="10" placeholder="10">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 600;">
                    <input type="checkbox" name="is_paid" value="1" checked style="width: 16px; height: 16px;">
                    Is Paid Leave (Salary not deducted)
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeAddLeaveTypeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700;">Create & Apply Policy</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteLeaveTypeForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openAddLeaveTypeModal() {
    document.getElementById('addLeaveTypeModal').style.display = 'flex';
}
function closeAddLeaveTypeModal() {
    document.getElementById('addLeaveTypeModal').style.display = 'none';
}
function closeAddLeaveTypeModalOnOverlay(event) {
    if (event.target.id === 'addLeaveTypeModal') {
        closeAddLeaveTypeModal();
    }
}
function confirmDeleteLeaveType(id, name) {
    Swal.fire({
        title: 'Delete Leave Type?',
        text: "Are you sure you want to remove '" + name + "' from company leave policy?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.getElementById('deleteLeaveTypeForm');
            form.action = '/admin/leave/type/' + id;
            form.submit();
        }
    });
}

function closeLeaveModal() {
    var modal = document.getElementById('leaveDetailsModal');
    modal.style.display = 'none';
}

function closeLeaveModalOnOverlay(event) {
    if (event.target.id === 'leaveDetailsModal') {
        closeLeaveModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLeaveModal();
        closeAddLeaveTypeModal();
    }
});
</script>
@endsection
