@extends('layouts.admin')

@section('title', 'Create User')
@section('page_title', 'Create New User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Registration Form</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">⬅️ Back to Users</a>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="rahul@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="9876543210">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="e.g. Sales / HR / IT">
                </div>

                <div class="form-group">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="e.g. Senior Executive">
                </div>

                <div class="form-group">
                    <label class="form-label">Account Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-type password">
                </div>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border-color);">

            <!-- Role Assignment -->
            <div class="form-group">
                <label class="form-label" style="font-size: 1rem; font-weight: 600; margin-bottom: 10px;">Assign Role(s) *</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    @foreach($roles as $role)
                        <label class="checkbox-label">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}>
                            <span><strong>{{ $role->name }}</strong></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border-color);">

            <!-- Direct User Permissions Assignment -->
            <div class="form-group">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <div>
                        <label class="form-label" style="font-size: 1rem; font-weight: 600; margin: 0;">Direct User Permissions (Optional)</label>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Assign additional direct permissions specific to this user beyond their role.</span>
                    </div>
                </div>

                @foreach($permissionsByModule as $module => $modulePermissions)
                    <div class="permission-module-box">
                        <div class="permission-module-header">
                            <span>📂 {{ $module }} Module</span>
                        </div>
                        <div class="permission-grid">
                            @foreach($modulePermissions as $perm)
                                <label class="checkbox-label">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" {{ is_array(old('permissions')) && in_array($perm->id, old('permissions')) ? 'checked' : '' }}>
                                    <span>{{ $perm->name }} <small style="color: var(--text-muted);">({{ $perm->slug }})</small></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Save & Create User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
