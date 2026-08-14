@extends('layouts.admin')

@section('title', 'All Users')
@section('page_title', 'All Users')

@section('content')
<!-- Page Header -->
<div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.4px;">All Users</h2>
        <p style="font-size: 0.88rem; color: #64748b; margin-top: 2px;">Manage employees, users, and their access across the platform.</p>
    </div>
    <div>
        <span class="badge badge-primary" style="font-size: 0.85rem; padding: 6px 14px;">
            <i class="fa-solid fa-users"></i> {{ $users->total() }} users total
        </span>
        @if(auth()->user()->hasPermission('users.create'))
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm" style="margin-left: 10px;">
                <i class="fa-solid fa-user-plus"></i> Create New User
            </a>
        @endif
    </div>
</div>

<!-- Search & Filter Card (Matching Screenshot 3) -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between;">
            <div class="search-input-box" style="flex: 1; min-width: 250px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>

            <div style="width: 160px;">
                <select name="status" class="form-control" style="background-color: #f8fafc; border-radius: 10px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div style="width: 180px;">
                <select name="role" class="form-control" style="background-color: #f8fafc; border-radius: 10px;">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-vibrant-blue">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- User Directory Table (Matching Screenshot 2 & 3) -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Directory</h3>
        <div style="font-size: 0.8rem; color: var(--text-muted);">
            Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>USER</th>
                    <th>PHONE</th>
                    <th>ROLE</th>
                    <th>STATUS</th>
                    <th style="text-align: right;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td style="color: var(--text-muted); font-size: 0.82rem;">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="avatar-circle">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0f172a;">{{ $user->name }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color: #475569; font-weight: 500;">
                        {{ $user->mobile ?? '9876543210' }}
                    </td>
                    <td>
                        @forelse($user->roles as $r)
                            <span class="badge badge-primary">{{ $r->name }}</span>
                        @empty
                            <span class="badge badge-secondary">User</span>
                        @endforelse
                    </td>
                    <td>
                        @if(auth()->user()->hasPermission('users.activate') && $user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer; outline: none;" title="Toggle Status">
                                    <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}" style="padding: 6px 12px; font-size: 0.8rem;">
                                        <i class="fa-solid {{ $user->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i> {{ ucfirst($user->status) }}
                                    </span>
                                </button>
                            </form>
                        @else
                            <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}" style="padding: 6px 12px; font-size: 0.8rem;">
                                {{ ucfirst($user->status) }}
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px;">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-table-action">
                                View Profile
                            </a>

                            @if(auth()->user()->hasPermission('users.edit'))
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary btn-sm" title="Edit User">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="{{ route('admin.users.permissions', $user->id) }}" class="btn btn-secondary btn-sm" title="Direct Permissions">
                                    <i class="fa-solid fa-key"></i>
                                </a>
                            @endif

                            @if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete User">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 35px;">
                        No users found matching your search criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-footer">
        <div>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results</div>
        <div>
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
