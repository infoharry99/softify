@extends('layouts.admin')

@section('title', 'Monthly Payroll')
@section('page_title', 'Monthly Payroll & Salary Processing')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Monthly Payroll Cost</div>
        <div class="stat-value" style="color: var(--primary);">₹{{ number_format($totalPayrollCost, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Paid Payslips</div>
        <div class="stat-value" style="color: var(--success);">{{ $paidCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Unprocessed Employees</div>
        <div class="stat-value" style="color: var(--warning);">{{ $pendingCount }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payroll Runs</h3>
        <form action="{{ route('admin.payroll.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <label class="form-label" style="margin: 0;">Select Month:</label>
            <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()" style="padding: 5px 12px; width: 170px;">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Emp Code</th>
                    <th>Employee Name</th>
                    <th>Gross Salary</th>
                    <th>Present / Working</th>
                    <th>Leaves Taken</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php
                    $p = $payrolls->where('employee_id', $emp->id)->first();
                    $s = $emp->salaryStructure;
                @endphp
                <tr>
                    <td><code>{{ $emp->employee_code }}</code></td>
                    <td>
                        <strong>{{ $emp->user->name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $emp->user->email }}</div>
                    </td>
                    <td>₹{{ number_format($p ? $p->gross_salary : ($s ? $s->gross_salary : 0), 2) }}</td>
                    <td>
                        @if($p)
                            <span class="badge badge-secondary">{{ $p->present_days }} / {{ $p->working_days }} Days</span>
                        @else
                            <span style="color: var(--text-muted);">Not processed</span>
                        @endif
                    </td>
                    <td>
                        @if($p)
                            <span class="badge badge-warning" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                                {{ $p->paid_leave_days + $p->unpaid_leave_days }} Days
                                <small>({{ $p->paid_leave_days }} Paid, {{ $p->unpaid_leave_days }} LOP)</small>
                            </span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td style="color: var(--danger); font-weight: 600;">
                        ₹{{ number_format($p ? $p->total_deductions : ($s ? $s->other_deductions : 0), 2) }}
                    </td>
                    <td>
                        <strong style="color: #00a884; font-size: 1rem;">₹{{ number_format($p ? $p->net_salary : ($s ? $s->net_salary : 0), 2) }}</strong>
                    </td>
                    <td>
                        @if($p)
                            <span class="badge badge-success">Paid</span>
                            <div style="font-size: 0.74rem; color: #64748b; font-weight: 600; margin-top: 2px;">
                                {{ $p->payment_date ? $p->payment_date->format('d M, Y') : $p->updated_at->format('d M, Y') }}
                            </div>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <div style="display: inline-flex; gap: 4px;">
                            <form action="{{ route('admin.payroll.process', $emp->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="month" value="{{ $month }}">
                                <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 6px;" title="Auto-Calculate Payroll">
                                    {{ $p ? '🔄 Recalculate' : '⚡ Process' }}
                                </button>
                            </form>
                            @if($p)
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openEditPayrollIndexModal({{ json_encode($p) }})" style="padding: 6px 10px; border-radius: 6px;" title="Edit & Calculate Custom Salary">
                                    <i class="fa-solid fa-pen-to-square" style="color: #00a884;"></i>
                                </button>
                                <a href="{{ route('admin.payroll.slip', $p->id) }}" class="btn btn-secondary btn-sm" target="_blank" style="padding: 6px 10px; border-radius: 6px;" title="View Payslip">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.payroll.download', $p->id) }}" class="btn btn-primary btn-sm" target="_blank" style="padding: 6px 10px; border-radius: 6px;" title="Download PDF Payslip">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Edit & Calculate Custom Salary (Index Page) -->
<div class="modal fade" id="editPayrollIndexModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:90%; max-width:600px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-calculator"></i> Edit & Calculate Monthly Salary
            </h4>
            <button type="button" onclick="closeEditPayrollIndexModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form id="editPayrollIndexForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Working Days in Month</label>
                    <input type="number" id="idx_working_days" name="working_days" class="form-control" oninput="recalculateSalaryIndexModal()" required min="1" max="31">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Present Days Worked</label>
                    <input type="number" id="idx_present_days" name="present_days" class="form-control" oninput="recalculateSalaryIndexModal()" required min="0" max="31">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Paid Leaves Allowed</label>
                    <input type="number" id="idx_paid_leave_days" name="paid_leave_days" class="form-control" oninput="recalculateSalaryIndexModal()" required min="0" max="31">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#ef4444;">Unpaid Leaves (LOP Days)</label>
                    <input type="number" id="idx_unpaid_leave_days" name="unpaid_leave_days" class="form-control" oninput="recalculateSalaryIndexModal()" required min="0" max="31">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Base Gross Salary (₹)</label>
                    <input type="number" step="0.01" id="idx_gross_salary" name="gross_salary" class="form-control" oninput="recalculateSalaryIndexModal()" required>
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#00a884;">Bonus / Allowance (+ ₹)</label>
                    <input type="number" step="0.01" id="idx_bonus_amount" name="bonus_amount" class="form-control" oninput="recalculateSalaryIndexModal()">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#ef4444;">Other Deductions (- ₹)</label>
                    <input type="number" step="0.01" id="idx_other_deductions" name="other_deductions" class="form-control" oninput="recalculateSalaryIndexModal()">
                </div>
                <div>
                    <label style="font-size:0.82rem; font-weight:700; color:#475569;">Payment Status</label>
                    <select name="payment_status" id="idx_payment_status" class="form-control">
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                    </select>
                </div>
            </div>

            <!-- Calculated Summary Card -->
            <div style="background:#f0faf7; border:1px solid #9ee5d4; padding:15px; border-radius:10px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:0.78rem; color:#64748b;">Calculated LOP Deduction: <strong id="idx_calc_lop" style="color:#ef4444;">₹0.00</strong></div>
                    <div style="font-size:0.78rem; color:#64748b;">Total Days Accounted: <strong id="idx_calc_total_days">0 / 0 Days</strong></div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.78rem; color:#00a884; font-weight:700; text-transform:uppercase;">Final Net Salary</div>
                    <div id="idx_calc_net" style="font-size:1.5rem; font-weight:800; color:#008f70;">₹0.00</div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeEditPayrollIndexModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Save & Update Salary</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditPayrollIndexModal(payroll) {
    var modal = document.getElementById('editPayrollIndexModal');
    var form = document.getElementById('editPayrollIndexForm');
    form.action = '/admin/payroll/' + payroll.id;

    document.getElementById('idx_working_days').value = payroll.working_days || 30;
    document.getElementById('idx_present_days').value = payroll.present_days || 0;
    document.getElementById('idx_paid_leave_days').value = payroll.paid_leave_days || 0;
    document.getElementById('idx_unpaid_leave_days').value = payroll.unpaid_leave_days || 0;
    document.getElementById('idx_gross_salary').value = payroll.gross_salary || 0;
    document.getElementById('idx_bonus_amount').value = payroll.bonus_amount || 0;
    document.getElementById('idx_other_deductions').value = (payroll.total_deductions - payroll.leave_deductions) || 0;
    document.getElementById('idx_payment_status').value = payroll.payment_status || 'Paid';

    modal.style.display = 'flex';
    recalculateSalaryIndexModal();
}

function closeEditPayrollIndexModal() {
    document.getElementById('editPayrollIndexModal').style.display = 'none';
}

function recalculateSalaryIndexModal() {
    var workingDays = parseInt(document.getElementById('idx_working_days').value) || 30;
    var presentDays = parseInt(document.getElementById('idx_present_days').value) || 0;
    var paidLeaves = parseInt(document.getElementById('idx_paid_leave_days').value) || 0;
    var unpaidLeaves = parseInt(document.getElementById('idx_unpaid_leave_days').value) || 0;
    var gross = parseFloat(document.getElementById('idx_gross_salary').value) || 0;
    var bonus = parseFloat(document.getElementById('idx_bonus_amount').value) || 0;
    var otherDed = parseFloat(document.getElementById('idx_other_deductions').value) || 0;

    var perDay = workingDays > 0 ? (gross / workingDays) : 0;
    var lopDeduction = Math.round(unpaidLeaves * perDay * 100) / 100;
    var totalDeductions = Math.round((lopDeduction + otherDed) * 100) / 100;
    var netSalary = Math.max(0, Math.round((gross + bonus - totalDeductions) * 100) / 100);

    document.getElementById('idx_calc_lop').innerText = '₹' + lopDeduction.toFixed(2);
    document.getElementById('idx_calc_total_days').innerText = (presentDays + paidLeaves + unpaidLeaves) + ' / ' + workingDays + ' Days';
    document.getElementById('idx_calc_net').innerText = '₹' + netSalary.toFixed(2);
}
</script>
@endsection
