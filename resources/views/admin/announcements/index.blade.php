@extends('layouts.admin')

@section('title', 'Company Notices')
@section('page_title', 'Company Announcements & Notices')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 380px; gap: 25px;">
    <!-- Announcements List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Published Announcements</h3>
        </div>
        <div class="card-body">
            @forelse($announcements as $ann)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 18px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main);">{{ $ann->title }}</h4>
                        <span class="badge badge-primary">{{ $ann->audience }}</span>
                    </div>
                    <p style="font-size: 0.9rem; color: var(--text-main); line-height: 1.5;">{{ $ann->message }}</p>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 10px;">
                        Published by: <strong>{{ $ann->publisher->name ?? 'Admin' }}</strong> | {{ $ann->published_at ? $ann->published_at->format('M d, Y h:i A') : 'Recent' }}
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 30px;">
                    No announcements published yet.
                </div>
            @endforelse
        </div>
        <div style="padding: 15px 20px;">
            {{ $announcements->links() }}
        </div>
    </div>

    <!-- Create Announcement Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📢 Publish New Notice</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Notice Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Office Independence Day Holiday">
                </div>

                <div class="form-group">
                    <label class="form-label">Target Audience *</label>
                    <select name="audience" class="form-control" required>
                        <option value="All Employees">All Employees</option>
                        <option value="Department">Department Specific</option>
                        <option value="Role">Role Specific</option>
                        <option value="Selected Employees">Selected Employees</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Notice Message *</label>
                    <textarea name="message" class="form-control" rows="5" required placeholder="Type company announcement message..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Publish Announcement</button>
            </form>
        </div>
    </div>
</div>
@endsection
