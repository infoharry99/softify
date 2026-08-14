@extends('layouts.employee')

@section('title', 'Notifications Center')
@section('page_title', 'Notifications Feed')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">🔔 System Notifications</h3>
        <form action="{{ route('notifications.read_all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">Mark All as Read</button>
        </form>
    </div>
    <div class="card-body">
        @forelse($notifications as $notif)
            <div style="padding: 15px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; {{ $notif->is_read ? 'opacity: 0.7;' : 'background-color: #f0fdf4;' }}">
                <div>
                    <strong style="font-size: 0.95rem; color: var(--text-main);">{{ $notif->title }}</strong>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">{{ $notif->message }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 6px;">{{ $notif->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <div>
                    @if(!$notif->is_read)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Mark Read</button>
                        </form>
                    @else
                        <span class="badge badge-secondary">Read</span>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; color: var(--text-muted); padding: 30px;">
                No notifications found.
            </div>
        @endforelse
    </div>
    <div style="padding: 15px 20px;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
