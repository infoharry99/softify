@extends('layouts.admin')

@section('title', 'Role Details - ' . $role->name)
@section('page_title', 'Role Overview: ' . $role->name)

@section('content')
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 25px;">
    <!-- Left Column: Role Details -->
    <div>
        <div class="card">
            <div class="card-body">
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 5px;">
                    🛡️ {{ $role->name }}
                </div>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px;">Slug: <code>{{ $role->slug }}</code></div>

                <div style="margin-bottom: 15px;">
                    <span class="badge {{ $role->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                        Role {{ ucfirst($role->status) }}
                    </span>
                </div>

                <div style="background: #f8fafc; padding: 15px; border-radius: var(--radius); border: 1px solid var(--border-color); font-size: 0.85rem; margin-bottom: 15px;">
                    <strong>Description:</strong><br>
                    {{ $role->description ?? 'No description provided.' }}
                </div>

                <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 6px;">
                    <div>👥 <strong>Assigned Users:</strong> {{ $role->users->count() }}</div>
                    <div>🔑 <strong>Permissions:</strong> {{ $role->permissions->count() }}</div>
                    <div>📅 <strong>Created Date:</strong> {{ $role->created_at->format('M d, Y') }}</div>
                </div>

                <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary" style="width: 100%;">
                        ✏️ Edit Permissions
                    </a>
                    <a href="{{ route('admin.roles.users', $role->id) }}" class="btn btn-secondary" style="width: 100%;">
                        👥 View Assigned Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Permission Matrix -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Role Permissions Breakdown</h3>
            </div>
            <div class="card-body">
                @if($role->slug === 'super-admin')
                    <div class="alert alert-success">
                        ⭐ <strong>Super Admin Role:</strong> This role inherently possesses full access to all system modules and actions.
                    </div>
                @endif

                @forelse($permissionsByModule as $module => $permissions)
                    <div class="permission-module-box">
                        <div class="permission-module-header">
                            <span>📂 {{ $module }} Module</span>
                            <span class="badge badge-secondary">{{ count($permissions) }} Granted</span>
                        </div>
                        <div class="permission-grid">
                            @foreach($permissions as $perm)
                                <div style="font-size: 0.85rem; background: #ffffff; padding: 8px 12px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                                    <strong style="color: var(--success);">✓ {{ $perm->name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $perm->slug }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-muted); text-align: center; padding: 20px;">No permissions assigned to this role yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
