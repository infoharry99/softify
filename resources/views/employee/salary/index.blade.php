@extends('layouts.employee')

@section('title', 'My Salary & Slips')
@section('page_title', 'My Salary & Monthly Payslips')

@section('content')
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 25px;">
    <!-- Salary Structure Overview -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">💵 Current Salary Breakdown</h3>
            </div>
            <div class="card-body">
                @if($structure)
                    @php
                        $structureNet = max(0, round($structure->gross_salary - $structure->other_deductions - $structure->pf_deduction - $structure->esi_deduction - $structure->pt_deduction - $structure->tds_deduction));
                    @endphp
                    <div style="text-align: center; background: #ecfdf5; padding: 15px; border-radius: var(--radius); margin-bottom: 20px; border: 1px solid #a7f3d0;">
                        <div style="font-size: 0.8rem; color: #065f46; text-transform: uppercase; font-weight: 600;">Net Monthly Salary</div>
                        <div style="font-size: 1.8rem; font-weight: 700; color: #047857;">₹{{ number_format($structureNet) }}</div>
                    </div>

                    <div style="font-size: 0.85rem; display: flex; flex-direction: column; gap: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Basic Salary:</span> <strong>₹{{ number_format(round($structure->basic_salary)) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>HRA:</span> <strong>₹{{ number_format(round($structure->hra)) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Allowances:</span> <strong>₹{{ number_format(round($structure->allowances)) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 8px;">
                            <span>Gross Salary:</span> <strong>₹{{ number_format(round($structure->gross_salary)) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: var(--danger);">
                            <span>Other Deductions:</span> <strong>- ₹{{ number_format(round($structure->other_deductions)) }}</strong>
                        </div>
                    </div>
                @else
                    <div style="color: var(--text-muted); text-align: center;">Salary structure not set by Admin yet.</div>
                @endif
            </div>
        </div>

        <!-- Salary Revision History Timeline -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📈 Salary Revision History</h3>
            </div>
            <div class="card-body">
                @forelse($histories as $hist)
                    <div style="border-left: 2px solid var(--primary); padding-left: 12px; margin-bottom: 15px;">
                        <div style="font-size: 0.85rem; font-weight: 700;">
                            ₹{{ number_format(round($hist->previous_net_salary)) }} ➔ ₹{{ number_format(round($hist->new_net_salary)) }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            Effective: {{ $hist->effective_date->format('M d, Y') }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            Reason: {{ $hist->reason ?? 'Annual Revision' }}
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-muted); font-size: 0.85rem; text-align: center;">No salary revisions recorded.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Monthly Salary Slips List -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📄 Monthly Salary Slips</h3>
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
                        @forelse($payrolls as $pay)
                        @php
                            $payLopDed = round($pay->leave_deductions);
                            $payOtherDed = round($structure ? $structure->other_deductions : 0);
                            $payTotalDed = round($payLopDed + $payOtherDed);
                            $payGross = round($pay->gross_salary);
                            $payNet = max(0, round($payGross - $payTotalDed));
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($pay->month . '-01')->format('F Y') }}</strong>
                                <div style="font-size: 0.76rem; color: #00a884; font-weight: 600; margin-top: 2px;">
                                    Processed: {{ $pay->payment_date ? $pay->payment_date->format('d M, Y') : $pay->updated_at->format('d M, Y') }}
                                </div>
                            </td>
                            <td><span class="badge badge-secondary">{{ $pay->present_days }} / {{ $pay->working_days }} Days</span></td>
                            <td>
                                <span class="badge badge-warning" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;">
                                    {{ $pay->paid_leave_days + $pay->unpaid_leave_days }} Days
                                    <small>({{ $pay->paid_leave_days }} Paid, {{ $pay->unpaid_leave_days }} LOP)</small>
                                </span>
                            </td>
                            <td>₹{{ number_format($payGross) }}</td>
                            <td style="color: var(--danger); font-weight: 600;">₹{{ number_format($payTotalDed) }}</td>
                            <td><strong style="color: #00a884; font-size: 1rem;">₹{{ number_format($payNet) }}</strong></td>
                            <td>
                                <span class="badge badge-success">
                                    Paid ({{ $pay->payment_date ? $pay->payment_date->format('M d') : 'Done' }})
                                </span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('employee.salary.slip', $pay->id) }}" class="btn btn-secondary btn-sm" target="_blank" style="padding: 6px 10px; border-radius: 6px; margin-right: 2px;" title="View Payslip">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('employee.salary.download', $pay->id) }}" class="btn btn-primary btn-sm" target="_blank" style="padding: 6px 10px; border-radius: 6px;" title="Download PDF Payslip">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                No payslips generated yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 15px 20px;">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
