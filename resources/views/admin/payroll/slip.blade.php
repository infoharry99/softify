<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - {{ $payroll->employee->user->name }} ({{ $payroll->month }})</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Helvetica Neue', Arial, sans-serif; }
        body { background: #f8fafc; padding: 40px; color: #1e293b; }
        .slip-box { max-width: 800px; margin: 0 auto; background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #00a884; padding-bottom: 20px; margin-bottom: 25px; }
        .company-name { font-size: 1.6rem; font-weight: 800; color: #00a884; letter-spacing: -0.3px; }
        .slip-title { font-size: 1.15rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; font-size: 0.9rem; }
        .info-group div { margin-bottom: 6px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 0.9rem; }
        .table th { background: #f0faf7; padding: 10px; border: 1px solid #cbd5e1; text-align: left; color: #0f172a; }
        .table td { padding: 10px; border: 1px solid #cbd5e1; }
        .total-row { background: #e6f7f3; font-weight: 700; font-size: 1rem; color: #00a884; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; align-items: flex-end; font-size: 0.85rem; color: #64748b; }
        @media print {
            body { background: #ffffff; padding: 0; }
            .slip-box { box-shadow: none; border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 800px; margin: 0 auto 20px auto; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 22px; background: #00a884; color: #fff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0,168,132,0.25);">
            🖨️ Print Payslip
        </button>
    </div>

    <div class="slip-box">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ asset('images/logo.png') }}" alt="Talentifyy" style="height: 42px; width: auto; object-fit: contain;">
                <div>
                    <div class="company-name">TALENTIFYY</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Enterprise Employee Management System</div>
                </div>
            </div>
            <div class="slip-title">
                Salary Slip: {{ \Carbon\Carbon::parse($payroll->month . '-01')->format('F Y') }}
            </div>
        </div>

        <div class="grid-2">
            <div class="info-group">
                <div><strong>Employee Name:</strong> {{ $payroll->employee->user->name }}</div>
                <div><strong>Employee ID:</strong> {{ $payroll->employee->employee_code }}</div>
                <div><strong>Department:</strong> {{ $payroll->employee->user->department ?? 'General' }}</div>
                <div><strong>Designation:</strong> {{ $payroll->employee->user->designation ?? 'Staff' }}</div>
            </div>
            <div class="info-group">
                <div><strong>Joining Date:</strong> {{ $payroll->employee->joiningDetail ? $payroll->employee->joiningDetail->joining_date->format('M d, Y') : '-' }}</div>
                <div><strong>Working Days:</strong> {{ $payroll->working_days }} Days</div>
                <div><strong>Present Days:</strong> {{ $payroll->present_days }} Days</div>
                <div><strong>Total Leaves Taken:</strong> {{ $payroll->paid_leave_days + $payroll->unpaid_leave_days }} Days ({{ $payroll->paid_leave_days }} Paid, {{ $payroll->unpaid_leave_days }} LOP)</div>
                <div><strong>Payment Status:</strong> <span style="color: #10b981; font-weight: 700;">{{ $payroll->payment_status }}</span></div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Earnings</th>
                    <th>Amount (₹)</th>
                    <th>Deductions</th>
                    <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td>₹{{ number_format($salary ? $salary->basic_salary : 0, 2) }}</td>
                    <td>Unpaid Leave (LOP Deductions)</td>
                    <td>₹{{ number_format($payroll->leave_deductions, 2) }}</td>
                </tr>
                <tr>
                    <td>House Rent Allowance (HRA)</td>
                    <td>₹{{ number_format($salary ? $salary->hra : 0, 2) }}</td>
                    <td>Other Deductions</td>
                    <td>₹{{ number_format($salary ? $salary->other_deductions : 0, 2) }}</td>
                </tr>
                <tr>
                    <td>Conveyance & Allowances</td>
                    <td>₹{{ number_format($salary ? $salary->allowances : 0, 2) }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Bonus / Incentives</td>
                    <td>₹{{ number_format($payroll->bonus_amount, 2) }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr class="total-row">
                    <td>Total Gross Earnings</td>
                    <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                    <td>Total Deductions</td>
                    <td>₹{{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="background: #e6f7f3; border: 1px solid #9ee5d4; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px;">
            <div style="font-size: 0.9rem; color: #00a884; font-weight: 700; text-transform: uppercase;">Net Payable Amount</div>
            <div style="font-size: 2rem; font-weight: 800; color: #008f70;">₹{{ number_format($payroll->net_salary, 2) }}</div>
        </div>

        <div class="footer">
            <div>
                <div>Processed by: {{ $payroll->processor->name ?? 'HR Admin' }}</div>
                <div>Generated on: {{ $payroll->updated_at->format('M d, Y') }}</div>
            </div>
            <div style="text-align: right;">
                <div style="border-top: 1px solid #000; width: 180px; text-align: center; padding-top: 5px;">
                    Authorized Signature
                </div>
            </div>
        </div>
    @if(isset($autoPrint) || request()->has('download'))
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
    @endif
</body>
</html>
