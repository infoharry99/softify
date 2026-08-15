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
                    <div style="display: flex; justify-content: space-between; color: var(--danger);"><span>PF Deduction:</span> <strong>- ₹{{ number_format($employee->salaryStructure->pf_deduction, 2) }}</strong></div>
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
<div style="display: grid; grid-template-columns: 300px 1fr; gap: 25px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Leave Balances</h3>
        </div>
        <div class="card-body">
            @foreach($employee->leaveBalances as $bal)
                <div style="margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color);">
                    <strong>{{ $bal->leaveType->name }}</strong>
                    <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">{{ $bal->remaining_days }} Remaining</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Used: {{ $bal->used_days }} / {{ $bal->allowed_days }}</div>
                </div>
            @endforeach
        </div>
    </div>

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
                    </div>

                    <div class="form-group">
                        <label class="form-label">PF Deduction</label>
                        <input type="number" step="0.01" name="pf_deduction" class="form-control" value="{{ old('pf_deduction', $s->pf_deduction ?? 1800) }}">
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
    <div class="card-header">
        <h3 class="card-title">Monthly Payroll Slips History</h3>
        <form action="{{ route('admin.payroll.process', $employee->id) }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ date('Y-m') }}">
            <button type="submit" class="btn btn-primary btn-sm">Process Current Month Payroll</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Present Days</th>
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
                    <td><strong>{{ $p->month }}</strong></td>
                    <td>{{ $p->present_days }} / {{ $p->working_days }}</td>
                    <td>₹{{ number_format($p->gross_salary, 2) }}</td>
                    <td style="color: var(--danger);">₹{{ number_format($p->total_deductions, 2) }}</td>
                    <td><strong style="color: var(--primary);">₹{{ number_format($p->net_salary, 2) }}</strong></td>
                    <td><span class="badge badge-success">{{ $p->payment_status }}</span></td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.payroll.slip', $p->id) }}" class="btn btn-secondary btn-sm" target="_blank">🖨️ View Payslip</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">No payroll slips processed yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
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
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this document?')">🗑️</button>
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
