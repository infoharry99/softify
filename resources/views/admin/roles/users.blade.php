@extends('layouts.admin')

@section('title', 'Users assigned to ' . $role->name)
@section('page_title', 'Users Assigned to Role: ' . $role->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users List (Role: {{ $role->name }})</h3>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">⬅️ Back to Roles</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Department & Designation</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                    </td>
                    <td>
                        <div>{{ $user->department ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->designation ?? '-' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">
                            👁️ View Profile
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No users currently assigned to this role.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
