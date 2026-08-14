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
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Payment Status</th>
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
                            {{ $p->present_days }} / {{ $p->working_days }} Days
                        @else
                            <span style="color: var(--text-muted);">Not processed</span>
                        @endif
                    </td>
                    <td style="color: var(--danger);">
                        ₹{{ number_format($p ? $p->total_deductions : ($s ? $s->pf_deduction + $s->other_deductions : 0), 2) }}
                    </td>
                    <td>
                        <strong style="color: var(--primary);">₹{{ number_format($p ? $p->net_salary : ($s ? $s->net_salary : 0), 2) }}</strong>
                    </td>
                    <td>
                        @if($p)
                            <span class="badge badge-success">Paid</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 5px;">
                            @if($p)
                                <a href="{{ route('admin.payroll.slip', $p->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                    🖨️ Payslip
                                </a>
                            @endif
                            <form action="{{ route('admin.payroll.process', $emp->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="month" value="{{ $month }}">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    {{ $p ? '🔄 Re-calculate' : '⚡ Process Payroll' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
