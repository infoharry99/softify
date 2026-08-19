@extends('layouts.admin')

@section('title', 'Add New Employee')
@section('page_title', 'Create Employee Record')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Employee Registration Form</h3>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-sm">⬅️ Back to Directory</a>
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

        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Employee Code (Auto Generated) *</label>
                    <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', 'EMP-' . rand(1000, 9999)) }}" readonly style="background-color: #f1f5f9; font-weight: 700; color: #475569; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Vikram Malhotra">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="vikram@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="e.g. 9876543210" pattern="^(\+91[\-\s]?)?[6789]\d{9}$" maxlength="13" title="Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210)">
                </div>

                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <select name="department" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        <option value="Human Resources" {{ old('department') == 'Human Resources' ? 'selected' : '' }}>Human Resources (HR)</option>
                        <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Business Development" {{ old('department') == 'Business Development' ? 'selected' : '' }}>Business Development (BDA)</option>
                        <option value="Talent Acquisition" {{ old('department') == 'Talent Acquisition' ? 'selected' : '' }}>Talent Acquisition</option>
                        <option value="Data Entry" {{ old('department') == 'Data Entry' ? 'selected' : '' }}>Data Entry</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Designation *</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', 'Software Engineer') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">System Role *</label>
                    <select name="role_id" class="form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Reporting Manager</label>
                    <select name="reporting_manager_id" class="form-control">
                        <option value="">-- None / Top Management --</option>
                        @foreach($managers as $m)
                            <option value="{{ $m->id }}">{{ $m->user->name }} ({{ $m->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Joining Date *</label>
                    <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Employment Type *</label>
                    <select name="employment_type" class="form-control" required>
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Contract">Contract</option>
                        <option value="Intern">Intern</option>
                        <option value="Freelancer">Freelancer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Employment Status *</label>
                    <select name="employment_status" class="form-control" required>
                        <option value="Active">Active</option>
                        <option value="Probation">Probation</option>
                        <option value="Notice Period">Notice Period</option>
                        <option value="Resigned">Resigned</option>
                        <option value="Terminated">Terminated</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Work Location</label>
                    <input type="text" name="work_location" class="form-control" value="{{ old('work_location', 'Remote') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Account Password *</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-type password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Save & Create Employee Record</button>
        </form>
    </div>
</div>
@endsection
