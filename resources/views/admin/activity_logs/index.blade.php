@extends('layouts.admin')

@section('title', 'Activity Audit Logs')
@section('page_title', 'Admin Audit Activity Logs')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">System Audit Log Trail</h3>
    </div>

    <!-- Search & Filter Header -->
    <div style="padding: 15px 20px; background-color: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('admin.activity_logs.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
            <div style="width: 200px;">
                <select name="user_id" class="form-control">
                    <option value="">-- All Users --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <input type="text" name="action" class="form-control" placeholder="Search by action (e.g. Login, User Created)..." value="{{ request('action') }}">
            </div>

            <div style="width: 160px;">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if(request()->anyFilled(['user_id', 'action', 'date']))
                <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Performed By</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $log)
                <tr>
                    <td style="font-size: 0.8rem; color: var(--text-muted); white-space: nowrap;">
                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                        <div style="font-size: 0.7rem;">({{ $log->created_at->diffForHumans() }})</div>
                    </td>
                    <td>
                        @if($log->user)
                            <strong>{{ $log->user->name }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $log->user->email }}</div>
                        @else
                            <span style="color: var(--text-muted);">System / Guest</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-primary">{{ $log->action }}</span>
                    </td>
                    <td>
                        {{ $log->description ?? '-' }}
                    </td>
                    <td style="font-family: monospace; font-size: 0.8rem;">
                        {{ $log->ip_address ?? '127.0.0.1' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No audit logs found matching your filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $activities->links() }}
    </div>
</div>
@endsection
