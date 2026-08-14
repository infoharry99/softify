@extends('layouts.admin')

@section('title', 'My Profile')
@section('page_title', 'My Account Profile')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
    <!-- Profile Info & Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Basic Profile Information</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address (Read-only)</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" readonly style="background-color: #f1f5f9;">
                </div>

                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}">
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
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Update Profile Details</button>
            </form>
        </div>
    </div>

    <!-- Password Change & Role Info -->
    <div>
        <!-- Security & Password Change -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required placeholder="Minimum 8 characters">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Re-type new password">
                    </div>

                    <button type="submit" class="btn btn-secondary">Change My Password</button>
                </form>
            </div>
        </div>

        <!-- Account Roles & System Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Assigned Roles & Access</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <strong>Assigned Roles:</strong><br>
                    <div style="display: flex; gap: 8px; margin-top: 5px;">
                        @foreach($user->roles as $role)
                            <span class="badge badge-primary" style="padding: 6px 12px; font-size: 0.85rem;">
                                🛡️ {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 6px;">
                    <div>📅 <strong>Account Created:</strong> {{ $user->created_at->format('M d, Y') }}</div>
                    <div>🕒 <strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Current Session' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
