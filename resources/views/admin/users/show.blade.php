@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)
@section('page_title', 'User Profile & Permissions')

@section('content')
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 25px;">
    <!-- Left Column: User Profile Card -->
    <div>
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div class="user-avatar" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto 15px auto;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 style="font-size: 1.2rem; font-weight: 700; color: var(--text-main);">{{ $user->name }}</h2>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">{{ $user->email }}</div>

                <div style="margin-bottom: 15px;">
                    <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                        Account {{ ucfirst($user->status) }}
                    </span>
                </div>

                <div style="text-align: left; background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color); font-size: 0.85rem; display: flex; flex-direction: column; gap: 8px;">
                    <div><strong>Mobile:</strong> {{ $user->mobile ?? 'Not set' }}</div>
                    <div><strong>Department:</strong> {{ $user->department ?? 'Unassigned' }}</div>
                    <div><strong>Designation:</strong> {{ $user->designation ?? 'Unassigned' }}</div>
                    <div><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}</div>
                    <div><strong>Registered:</strong> {{ $user->created_at->format('M d, Y') }}</div>
                </div>

                @if(auth()->user()->hasPermission('users.edit'))
                    <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 8px;">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" style="width: 100%;">
                            ✏️ Edit Profile & Roles
                        </a>
                        <a href="{{ route('admin.users.permissions', $user->id) }}" class="btn btn-secondary" style="width: 100%;">
                            🔑 Manage Direct Permissions
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Admin Reset Password Form -->
        @if(auth()->user()->hasPermission('users.change_password'))
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reset Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Min 8 characters">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirm new password">
                        </div>
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Update User Password</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Assigned Roles & Permissions Matrix -->
    <div>
        <!-- Roles Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assigned Roles</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @forelse($user->roles as $role)
                        <div style="background: var(--primary-light); border: 1px solid #bfdbfe; color: var(--primary); padding: 8px 14px; border-radius: var(--radius); font-weight: 600; font-size: 0.9rem;">
                            🛡️ {{ $role->name }}
                        </div>
                    @empty
                        <span style="color: var(--text-muted);">No roles assigned to this user.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Direct Permissions Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Direct User Permissions</h3>
                @if(auth()->user()->hasPermission('users.edit'))
                    <a href="{{ route('admin.users.permissions', $user->id) }}" class="btn btn-secondary btn-sm">Edit Direct Perms</a>
                @endif
            </div>
            <div class="card-body">
                @forelse($directPermissions as $perm)
                    <span class="badge badge-success" style="margin-right: 5px; margin-bottom: 8px; padding: 6px 10px;">
                        ✓ {{ $perm->module }} ➔ {{ $perm->name }} ({{ $perm->slug }})
                    </span>
                @empty
                    <div style="color: var(--text-muted); font-size: 0.875rem;">No direct permissions explicitly assigned to this user.</div>
                @endforelse
            </div>
        </div>

        <!-- Inherited Role Permissions Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Permissions Inherited From Roles</h3>
            </div>
            <div class="card-body">
                @if($user->hasRole('super-admin'))
                    <div class="alert alert-success" style="margin: 0;">
                        ⭐ <strong>Super Admin Role Active:</strong> User automatically has access to all system modules and permissions.
                    </div>
                @else
                    @forelse($rolePermissions->groupBy('module') as $module => $perms)
                        <div style="margin-bottom: 15px;">
                            <h4 style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">
                                📂 {{ $module }}
                            </h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @foreach($perms as $p)
                                    <span class="badge badge-secondary" style="padding: 5px 9px;">
                                        🛡️ {{ $p->name }} ({{ $p->slug }})
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div style="color: var(--text-muted); font-size: 0.875rem;">No role permissions inherited.</div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
