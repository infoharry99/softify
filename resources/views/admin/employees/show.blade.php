@extends('layouts.admin')

@section('title', 'Employee 360° - ' . $employee->user->name)
@section('page_title', 'Employee 360° View: ' . $employee->user->name)

@section('content')
<!-- Top Header Card -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="user-avatar" style="width: 64px; height: 64px; font-size: 1.5rem;">
                {{ strtoupper(substr($employee->user->name, 0, 1)) }}
            </div>
            <div>
                <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main);">
                    {{ $employee->user->name }}
                    <code style="font-size: 0.9rem; font-weight: 600; color: var(--primary);">({{ $employee->employee_code }})</code>
                </h2>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 3px;">
                    {{ $employee->user->designation ?? 'Employee' }} | Dept: <strong>{{ $employee->user->department ?? 'General' }}</strong>
                </div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
            <span class="badge {{ ($employee->joiningDetail->employment_status ?? 'Active') === 'Active' ? 'badge-success' : 'badge-warning' }}" style="font-size: 0.85rem; padding: 6px 12px;">
                Status: {{ $employee->joiningDetail->employment_status ?? 'Active' }}
            </span>
            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-secondary btn-sm">
                ✏️ Edit Employee
            </a>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-sm">
                ⬅️ Back to Directory
            </a>
        </div>
    </div>
</div>

<!-- 360° Sub-Tab Navigation Bar -->
<div class="tab-nav">
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'overview']) }}" class="tab-item {{ $activeTab === 'overview' ? 'active' : '' }}">
        <i class="fa-solid fa-chart-pie"></i> Overview
    </a>
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'joining']) }}" class="tab-item {{ $activeTab === 'joining' ? 'active' : '' }}">
        <i class="fa-solid fa-id-card"></i> Joining & Profile
    </a>
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'attendance']) }}" class="tab-item {{ $activeTab === 'attendance' ? 'active' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i> Attendance
    </a>
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'leave']) }}" class="tab-item {{ $activeTab === 'leave' ? 'active' : '' }}">
        <i class="fa-solid fa-plane-departure"></i> Leave
    </a>
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'salary']) }}" class="tab-item {{ $activeTab === 'salary' ? 'active' : '' }}">
        <i class="fa-solid fa-wallet"></i> Salary & Revision History
    </a>
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'payroll']) }}" class="tab-item {{ $activeTab === 'payroll' ? 'active' : '' }}">
        <i class="fa-solid fa-file-invoice-dollar"></i> Monthly Payroll Slips
    </a>
    <a href="{{ route('admin.employees.show', [$employee->id, 'tab' => 'documents']) }}" class="tab-item {{ $activeTab === 'documents' ? 'active' : '' }}">
        <i class="fa-solid fa-folder-open"></i> Documents
    </a>
</div>

<!-- Tab 1: OVERVIEW -->
@if($activeTab === 'overview')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Personal & Employment Details</h3>
        </div>
        <div class="card-body" style="font-size: 0.9rem; display: flex; flex-direction: column; gap: 12px;">
            <div><strong>Email:</strong> {{ $employee->user->email }}</div>
            <div><strong>Mobile:</strong> {{ $employee->user->mobile ?? 'Not set' }}</div>
            <div><strong>Reporting Manager:</strong> {{ $employee->reportingManager ? $employee->reportingManager->user->name : 'None (Top Level)' }}</div>
            <div><strong>Joining Date:</strong> {{ $employee->joiningDetail ? $employee->joiningDetail->joining_date->format('M d, Y') : '-' }}</div>
            <div><strong>Employment Type:</strong> {{ $employee->joiningDetail->employment_type ?? 'Full Time' }}</div>
            <div><strong>Work Location:</strong> {{ $employee->joiningDetail->work_location ?? 'Office' }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Current Salary Summary</h3>
        </div>
        <div class="card-body">
            @if($employee->salaryStructure)
                <div style="font-size: 1.8rem; font-weight: 700; color: var(--primary); margin-bottom: 15px;">
                    ₹{{ number_format($employee->salaryStructure->net_salary, 2) }} <small style="font-size: 0.8rem; color: var(--text-muted);">/ month</small>
                </div>
                <div style="font-size: 0.85rem; display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; justify-content: space-between;"><span>Basic:</span> <strong>₹{{ number_format($employee->salaryStructure->basic_salary, 2) }}</strong></div>
                    <div style="display: flex; justify-content: space-between;"><span>HRA:</span> <strong>₹{{ number_format($employee->salaryStructure->hra, 2) }}</strong></div>
                    <div style="display: flex; justify-content: space-between;"><span>Allowances:</span> <strong>₹{{ number_format($employee->salaryStructure->allowances, 2) }}</strong></div>
                </div>
            @else
                <div style="color: var(--text-muted);">Salary structure not defined yet.</div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Tab 2: JOINING & PROFILE -->
@if($activeTab === 'joining')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Joining & Probation Details</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; font-size: 0.9rem;">
            <div><strong>Joining Date:</strong> {{ $employee->joiningDetail ? $employee->joiningDetail->joining_date->format('M d, Y') : '-' }}</div>
            <div><strong>Employment Type:</strong> {{ $employee->joiningDetail->employment_type ?? 'Full Time' }}</div>
            <div><strong>Employment Status:</strong> {{ $employee->joiningDetail->employment_status ?? 'Active' }}</div>
            <div><strong>Notice Period:</strong> {{ $employee->joiningDetail->notice_period_days ?? 30 }} Days</div>
            <div><strong>Work Location:</strong> {{ $employee->joiningDetail->work_location ?? 'Office' }}</div>
        </div>
    </div>
</div>
@endif

<!-- Tab 3: ATTENDANCE -->
@if($activeTab === 'attendance')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Attendance Log (Last 10 Days)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Break Duration</th>
                    <th>Effective Working</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->attendances as $att)
                <tr>
                    <td><strong>{{ $att->date->format('M d, Y') }}</strong></td>
                    <td>{{ $att->first_login_at ? $att->first_login_at->format('h:i A') : '--' }}</td>
                    <td>{{ $att->last_logout_at ? $att->last_logout_at->format('h:i A') : '--' }}</td>
                    <td>{{ $att->total_break_minutes }} mins</td>
                    <td>{{ floor($att->effective_working_minutes / 60) }}h {{ $att->effective_working_minutes % 60 }}m</td>
                    <td><span class="badge {{ $att->status === 'Present' ? 'badge-success' : 'badge-warning' }}">{{ $att->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; color: var(--text-muted);">No attendance logged.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Tab 4: LEAVE -->
@if($activeTab === 'leave')
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 25px;">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title"><i class="fa-solid fa-umbrella-beach" style="color: #00a884;"></i> Leave Balances</h3>
            <button type="button" onclick="openEditLeaveBalancesModal()" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 4px 10px;" title="Edit Leave Quotas">
                <i class="fa-solid fa-pen-to-square" style="color: #00a884;"></i> Edit Quotas
            </button>
        </div>
        <div class="card-body">
            @foreach($employee->leaveBalances as $bal)
                <div style="margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong>{{ $bal->leaveType->name }}</strong>
                        <span class="badge badge-secondary" style="font-size: 0.72rem;">{{ $bal->allowed_days }} Days Total</span>
                    </div>
                    <div style="font-size: 1.2rem; font-weight: 700; color: #00a884; margin-top: 2px;">{{ $bal->remaining_days }} Days Remaining</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Used: {{ $bal->used_days }} / {{ $bal->allowed_days }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal: Edit Leave Balances -->
    <div class="modal fade" id="editLeaveBalancesModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:#fff; width:90%; max-width:650px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
                <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Employee Leave Quotas
                </h4>
                <button type="button" onclick="closeEditLeaveBalancesModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.leave.balances.update', $employee->id) }}">
                @csrf
                <div style="display:flex; flex-direction:column; gap:14px; margin-bottom:20px;">
                    @foreach($employee->leaveBalances as $bal)
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:12px 16px; border-radius:10px; display:grid; grid-template-columns: 1.5fr 1fr 1fr; gap:12px; align-items:center;">
                            <div>
                                <strong style="color:#0f172a;">{{ $bal->leaveType->name }}</strong>
                                <div style="font-size:0.75rem; color:#64748b;">Year: {{ date('Y') }}</div>
                            </div>
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#475569;">Allowed Quota</label>
                                <input type="number" name="balances[{{ $bal->leave_type_id }}][allowed_days]" class="form-control" value="{{ $bal->allowed_days }}" min="0" required style="padding:4px 8px; font-size:0.88rem;">
                            </div>
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#ef4444;">Used Days</label>
                                <input type="number" name="balances[{{ $bal->leave_type_id }}][used_days]" class="form-control" value="{{ $bal->used_days }}" min="0" required style="padding:4px 8px; font-size:0.88rem;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="closeEditLeaveBalancesModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Save Quotas</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditLeaveBalancesModal() {
        document.getElementById('editLeaveBalancesModal').style.display = 'flex';
    }
    function closeEditLeaveBalancesModal() {
        document.getElementById('editLeaveBalancesModal').style.display = 'none';
    }
    </script>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Leave History</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->leaveApplications as $l)
                    <tr>
                        <td><strong>{{ $l->leaveType->name }}</strong></td>
                        <td>{{ $l->from_date->format('M d') }} - {{ $l->to_date->format('M d') }}</td>
                        <td>{{ $l->total_days }} day(s)</td>
                        <td>{{ $l->reason }}</td>
                        <td><span class="badge {{ $l->status === 'Approved' ? 'badge-success' : 'badge-warning' }}">{{ $l->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No leave records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Tab 5: SALARY & REVISION HISTORY -->
@if($activeTab === 'salary')
<div style="display: grid; grid-template-columns: 1fr 380px; gap: 25px;">
    <!-- Update Salary Structure Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">⚙️ Configure Salary Structure</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payroll.structure', $employee->id) }}" method="POST">
                @csrf

                @php $s = $employee->salaryStructure; @endphp
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Basic Salary *</label>
                        <input type="number" step="0.01" name="basic_salary" class="form-control" value="{{ old('basic_salary', $s->basic_salary ?? 30000) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">HRA *</label>
                        <input type="number" step="0.01" name="hra" class="form-control" value="{{ old('hra', $s->hra ?? 10000) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Conveyance / Allowances</label>
                        <input type="number" step="0.01" name="allowances" class="form-control" value="{{ old('allowances', $s->allowances ?? 5000) }}">
                        <input type="hidden" name="pf_deduction" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Effective Date *</label>
                        <input type="date" name="effective_date" class="form-control" value="{{ old('effective_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Revision Reason</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g. Annual Increment 2026">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save & Record Salary Revision</button>
            </form>
        </div>
    </div>

    <!-- Revision History Timeline -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📈 Revision History Log</h3>
        </div>
        <div class="card-body">
            @forelse($employee->salaryHistories as $h)
                <div style="border-left: 2px solid var(--primary); padding-left: 12px; margin-bottom: 15px;">
                    <strong>₹{{ number_format($h->previous_net_salary, 2) }} ➔ ₹{{ number_format($h->new_net_salary, 2) }}</strong>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Effective: {{ $h->effective_date->format('M d, Y') }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Reason: {{ $h->reason }}</div>
                </div>
            @empty
                <div style="color: var(--text-muted); text-align: center;">No revisions logged yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endif

<!-- Tab 6: PAYROLL SLIPS -->
@if($activeTab === 'payroll')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title"><i class="fa-solid fa-file-invoice-dollar" style="color: #00a884;"></i> Monthly Payroll Slips History</h3>
        <form action="{{ route('admin.payroll.process', $employee->id) }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ date('Y-m') }}">
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 8px; font-weight: 700;">
                <i class="fa-solid fa-calculator"></i> Process / Recalculate Current Month
            </button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Working / Present</th>
                    <th>Leaves Taken</th>
                    <th>Gross Salary</th>
                    <th>Deductions</th>
                    <th>Net Paid</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->monthlyPayrolls as $p)
                <tr>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($p->month . '-01')->format('F Y') }}</strong>
                        <div style="font-size: 0.76rem; color: #00a884; font-weight: 600; margin-top: 2px;">
                            Processed: {{ $p->payment_date ? $p->payment_date->format('d M, Y') : $p->updated_at->format('d M, Y') }}
                        </div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $p->present_days }} / {{ $p->working_days }} Days</span></td>
                    <td>
                        <span class="badge badge-warning" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                            {{ $p->paid_leave_days + $p->unpaid_leave_days }} Days
                            <small>({{ $p->paid_leave_days }} Paid, {{ $p->unpaid_leave_days }} LOP)</small>
                        </span>
                    </td>
                    <td>₹{{ number_format($p->gross_salary, 2) }}</td>
                    <td style="color: var(--danger); font-weight: 600;">₹{{ number_format($p->total_deductions, 2) }}</td>
                    <td><strong style="color: #00a884; font-size: 1rem;">₹{{ number_format($p->net_salary, 2) }}</strong></td>
                    <td><span class="badge badge-success">{{ $p->payment_status }}</span></td>
                    <td style="text-align: right; white-space: nowrap;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openEditPayrollModal({{ json_encode($p) }})" style="padding: 6px 10px; border-radius: 6px; margin-right: 2px;" title="Edit & Calculate Custom Salary">
                            <i class="fa-solid fa-pen-to-square" style="color: #00a884;"></i>
                        </button>
                        <a href="{{ route('admin.payroll.slip', $p->id) }}" class="btn btn-secondary btn-sm" target="_blank" style="padding: 6px 10px; border-radius: 6px; margin-right: 2px;" title="View Payslip">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.payroll.download', $p->id) }}" class="btn btn-primary btn-sm" target="_blank" style="padding: 6px 10px; border-radius: 6px;" title="Download PDF Payslip">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">No payroll slips processed yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Edit & Calculate Custom Salary -->
<div class="modal fade" id="editPayrollModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:90%; max-width:600px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-calculator"></i> Edit & Calculate Monthly Salary
            </h4>
            <button type="button" onclick="closeEditPayrollModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form id="editPayrollForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Working Days in Month</label>
                    <input type="number" id="edit_working_days" name="working_days" class="form-control" oninput="recalculateSalaryModal()" required min="1" max="31">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Present Days Worked</label>
                    <input type="number" id="edit_present_days" name="present_days" class="form-control" oninput="recalculateSalaryModal()" required min="0" max="31">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Paid Leaves Allowed</label>
                    <input type="number" id="edit_paid_leave_days" name="paid_leave_days" class="form-control" oninput="recalculateSalaryModal()" required min="0" max="31">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#ef4444;">Unpaid Leaves (LOP Days)</label>
                    <input type="number" id="edit_unpaid_leave_days" name="unpaid_leave_days" class="form-control" oninput="recalculateSalaryModal()" required min="0" max="31">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Base Gross Salary (₹)</label>
                    <input type="number" step="0.01" id="edit_gross_salary" name="gross_salary" class="form-control" oninput="recalculateSalaryModal()" required>
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#00a884;">Bonus / Allowance (+ ₹)</label>
                    <input type="number" step="0.01" id="edit_bonus_amount" name="bonus_amount" class="form-control" oninput="recalculateSalaryModal()">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#ef4444;">Other Deductions (- ₹)</label>
                    <input type="number" step="0.01" id="edit_other_deductions" name="other_deductions" class="form-control" oninput="recalculateSalaryModal()">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Payment Status</label>
                    <select name="payment_status" id="edit_payment_status" class="form-control">
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                    </select>
                </div>
            </div>

            <!-- Calculated Summary Card -->
            <div style="background:#f0faf7; border:1px solid #9ee5d4; padding:15px; border-radius:10px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:0.78rem; color:#64748b;">Calculated LOP Deduction: <strong id="edit_calc_lop" style="color:#ef4444;">₹0.00</strong></div>
                    <div style="font-size:0.78rem; color:#64748b;">Total Days Accounted: <strong id="edit_calc_total_days">0 / 0 Days</strong></div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.78rem; color:#00a884; font-weight:700; text-transform:uppercase;">Final Net Salary</div>
                    <div id="edit_calc_net" style="font-size:1.5rem; font-weight:800; color:#008f70;">₹0.00</div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeEditPayrollModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Save & Update Salary</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditPayrollModal(payroll) {
    var modal = document.getElementById('editPayrollModal');
    var form = document.getElementById('editPayrollForm');
    form.action = '/admin/payroll/' + payroll.id;

    document.getElementById('edit_working_days').value = payroll.working_days || 30;
    document.getElementById('edit_present_days').value = payroll.present_days || 0;
    document.getElementById('edit_paid_leave_days').value = payroll.paid_leave_days || 0;
    document.getElementById('edit_unpaid_leave_days').value = payroll.unpaid_leave_days || 0;
    document.getElementById('edit_gross_salary').value = payroll.gross_salary || 0;
    document.getElementById('edit_bonus_amount').value = payroll.bonus_amount || 0;
    document.getElementById('edit_other_deductions').value = (payroll.total_deductions - payroll.leave_deductions) || 0;
    document.getElementById('edit_payment_status').value = payroll.payment_status || 'Paid';

    modal.style.display = 'flex';
    recalculateSalaryModal();
}

function closeEditPayrollModal() {
    document.getElementById('editPayrollModal').style.display = 'none';
}

function recalculateSalaryModal() {
    var workingDays = parseInt(document.getElementById('edit_working_days').value) || 30;
    var presentDays = parseInt(document.getElementById('edit_present_days').value) || 0;
    var paidLeaves = parseInt(document.getElementById('edit_paid_leave_days').value) || 0;
    var unpaidLeaves = parseInt(document.getElementById('edit_unpaid_leave_days').value) || 0;
    var gross = parseFloat(document.getElementById('edit_gross_salary').value) || 0;
    var bonus = parseFloat(document.getElementById('edit_bonus_amount').value) || 0;
    var otherDed = parseFloat(document.getElementById('edit_other_deductions').value) || 0;

    var perDay = workingDays > 0 ? (gross / workingDays) : 0;
    var lopDeduction = Math.round(unpaidLeaves * perDay * 100) / 100;
    var totalDeductions = Math.round((lopDeduction + otherDed) * 100) / 100;
    var netSalary = Math.max(0, Math.round((gross + bonus - totalDeductions) * 100) / 100);

    document.getElementById('edit_calc_lop').innerText = '₹' + lopDeduction.toFixed(2);
    document.getElementById('edit_calc_total_days').innerText = (presentDays + paidLeaves + unpaidLeaves) + ' / ' + workingDays + ' Days';
    document.getElementById('edit_calc_net').innerText = '₹' + netSalary.toFixed(2);
}
</script>
@endif

<!-- Tab 7: DOCUMENTS -->
@if($activeTab === 'documents')
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 25px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Uploaded Employee Documents</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Document Name</th>
                        <th>Type</th>
                        <th>Version</th>
                        <th>Uploaded Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->documents as $doc)
                    <tr>
                        <td><strong>{{ $doc->document_name }}</strong></td>
                        <td><span class="badge badge-primary">{{ $doc->document_type }}</span></td>
                        <td>v{{ $doc->version }}</td>
                        <td>{{ $doc->created_at->format('M d, Y') }}</td>
                        <td style="text-align: right;">
                            <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmSwalDelete(event, this.form, 'Delete Document?', 'Are you sure you want to delete this official document?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No documents uploaded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upload Document Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📤 Upload Document</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employees.documents.upload', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Document Name *</label>
                    <input type="text" name="document_name" class="form-control" required placeholder="e.g. Signed Offer Letter 2026">
                </div>

                <div class="form-group">
                    <label class="form-label">Document Type *</label>
                    <select name="document_type" class="form-control" required>
                        <option value="Offer Letter">Offer Letter</option>
                        <option value="Joining Letter">Joining Letter</option>
                        <option value="Appointment Letter">Appointment Letter</option>
                        <option value="Salary Letter">Salary Letter</option>
                        <option value="Experience Letter">Experience Letter</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">File * (PDF/Doc/JPG)</label>
                    <input type="file" name="document_file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Upload Document</button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
