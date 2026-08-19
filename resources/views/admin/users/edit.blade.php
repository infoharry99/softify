@extends('layouts.admin')

@section('title', 'Edit User')
@section('page_title', 'Edit User Profile & Access')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit User: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">⬅️ Back to Details</a>
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

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $user->designation) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Account Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Change Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="New Password">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password">
                </div>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border-color);">

            <!-- Role Assignment -->
            <div class="form-group">
                <label class="form-label" style="font-size: 1rem; font-weight: 600; margin-bottom: 10px;">
                    Assigned Role(s)
                    @if($user->id === auth()->id())
                        <small style="color: var(--danger); font-weight: 600; margin-left: 8px;">(Self Profile - Role Modification Protected)</small>
                    @endif
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    @php $userRoleIds = $user->roles->pluck('id')->toArray(); @endphp
                    @foreach($roles as $role)
                        <label class="checkbox-label">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoleIds)) ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <span><strong>{{ $role->name }}</strong></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border-color);">

            <!-- Direct User Permissions -->
            <div class="form-group">
                <label class="form-label" style="font-size: 1rem; font-weight: 600; margin-bottom: 10px;">Direct User Permissions</label>
                @php $userDirectPermIds = $user->permissions->pluck('id')->toArray(); @endphp

                @foreach($permissionsByModule as $module => $modulePermissions)
                    <div class="permission-module-box">
                        <div class="permission-module-header">
                            <span>📂 {{ $module }} Module</span>
                        </div>
                        <div class="permission-grid">
                            @foreach($modulePermissions as $perm)
                                <label class="checkbox-label">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" {{ in_array($perm->id, old('permissions', $userDirectPermIds)) ? 'checked' : '' }}>
                                    <span>{{ $perm->name }} <small style="color: var(--text-muted);">({{ $perm->slug }})</small></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
