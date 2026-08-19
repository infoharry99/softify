@extends('layouts.admin')

@section('title', 'Edit Employee - ' . ($employee->user->name ?? 'Employee'))
@section('page_title', 'Edit Employee Record')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Employee: {{ $employee->user->name ?? 'Employee' }} ({{ $employee->employee_code }})</h3>
        <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-secondary btn-sm">⬅️ Back to 360° Profile</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Employee Code (Read-only)</label>
                    <input type="text" class="form-control" value="{{ $employee->employee_code }}" readonly style="background-color: #f1f5f9;">
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $employee->user->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->user->email ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="tel" name="mobile" class="form-control" value="{{ old('mobile', $employee->user->mobile ?? '') }}" placeholder="e.g. 9876543210" pattern="^(\+91[\-\s]?)?[6789]\d{9}$" maxlength="13" title="Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210)">
                </div>

                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <select name="department" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        <option value="Human Resources" {{ old('department', $employee->user->department ?? '') == 'Human Resources' ? 'selected' : '' }}>Human Resources (HR)</option>
                        <option value="Sales" {{ old('department', $employee->user->department ?? '') == 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="Finance" {{ old('department', $employee->user->department ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Business Development" {{ old('department', $employee->user->department ?? '') == 'Business Development' ? 'selected' : '' }}>Business Development (BDA)</option>
                        <option value="Talent Acquisition" {{ old('department', $employee->user->department ?? '') == 'Talent Acquisition' ? 'selected' : '' }}>Talent Acquisition</option>
                        <option value="Data Entry" {{ old('department', $employee->user->department ?? '') == 'Data Entry' ? 'selected' : '' }}>Data Entry</option>
                        <option value="Management" {{ old('department', $employee->user->department ?? '') == 'Management' ? 'selected' : '' }}>Management</option>
                        <option value="IT & Software" {{ old('department', $employee->user->department ?? '') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Designation *</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $employee->user->designation ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">System Role *</label>
                    @php $userRoleId = $employee->user ? $employee->user->roles->pluck('id')->first() : null; @endphp
                    @if($employee->user_id === auth()->id())
                        <input type="hidden" name="role_id" value="{{ $userRoleId }}">
                        <input type="text" class="form-control" value="{{ $employee->user->roles->first()->name ?? 'Staff' }} (Self Profile - Protected)" readonly style="background-color: #f1f5f9; font-weight: 700; color: #64748b;">
                    @else
                        <select name="role_id" class="form-control" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $role->id == $userRoleId ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Reporting Manager</label>
                    <select name="reporting_manager_id" class="form-control">
                        <option value="">-- None / Top Management --</option>
                        @foreach($managers as $m)
                            @if(!$m->user) @continue @endif
                            <option value="{{ $m->id }}" {{ $m->id == $employee->reporting_manager_id ? 'selected' : '' }}>{{ $m->user->name ?? 'N/A' }} ({{ $m->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Joining Date *</label>
                    <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $employee->joiningDetail ? $employee->joiningDetail->joining_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Employment Type *</label>
                    @php $empType = $employee->joiningDetail->employment_type ?? 'Full Time'; @endphp
                    <select name="employment_type" class="form-control" required>
                        <option value="Full Time" {{ $empType === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Part Time" {{ $empType === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Contract" {{ $empType === 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Intern" {{ $empType === 'Intern' ? 'selected' : '' }}>Intern</option>
                        <option value="Freelancer" {{ $empType === 'Freelancer' ? 'selected' : '' }}>Freelancer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Employment Status *</label>
                    @php $empStatus = $employee->joiningDetail->employment_status ?? 'Active'; @endphp
                    <select name="employment_status" class="form-control" required>
                        <option value="Active" {{ $empStatus === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Probation" {{ $empStatus === 'Probation' ? 'selected' : '' }}>Probation</option>
                        <option value="Notice Period" {{ $empStatus === 'Notice Period' ? 'selected' : '' }}>Notice Period</option>
                        <option value="Resigned" {{ $empStatus === 'Resigned' ? 'selected' : '' }}>Resigned</option>
                        <option value="Terminated" {{ $empStatus === 'Terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="Inactive" {{ $empStatus === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Work Location</label>
                    <input type="text" name="work_location" class="form-control" value="{{ old('work_location', $employee->joiningDetail->work_location ?? 'Head Office') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Update Employee Record</button>
        </form>
    </div>
</div>
@endsection
