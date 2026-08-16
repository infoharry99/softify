@extends('layouts.admin')

@section('title', 'Role Management')
@section('page_title', 'Dynamic Role Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">System Roles List</h3>
        @if(auth()->user()->hasPermission('roles.create'))
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                ➕ Create New Role
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Role Name & Slug</th>
                    <th>Description</th>
                    <th>Assigned Users</th>
                    <th>Permissions</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: var(--text-main);">🛡️ {{ $role->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $role->slug }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $role->description ?? 'No description provided' }}
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.users', $role->id) }}" class="badge badge-primary" style="text-decoration: none;">
                            👥 {{ $role->users_count }} Users
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-secondary">
                            🔑 {{ $role->permissions_count }} Permissions
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $role->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($role->status) }}
                        </span>
                    </td>
                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 5px;">
                            <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-secondary btn-sm" title="View Role">
                                👁️ View
                            </a>

                            @if(auth()->user()->hasPermission('roles.edit'))
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-secondary btn-sm" title="Edit Permissions">
                                    ✏️ Edit
                                </a>
                            @endif

                            @if(auth()->user()->hasPermission('roles.edit') && $role->slug !== 'super-admin')
                                <form action="{{ route('admin.roles.toggle-status', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirmSwalDelete(event, this.form, 'Toggle Role Status?', 'Are you sure you want to toggle status for this role?')">
                                        {{ $role->status === 'active' ? '🚫' : '✅' }}
                                    </button>
                                </form>
                            @endif

                            @if(auth()->user()->hasPermission('roles.delete') && $role->slug !== 'super-admin')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmSwalDelete(event, this.form, 'Delete System Role?', 'Are you sure you want to delete this system role?')">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No roles found in the database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $roles->links() }}
    </div>
</div>
@endsection
