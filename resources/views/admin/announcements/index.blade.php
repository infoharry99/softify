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
                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 18px; margin-bottom: 15px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-bullhorn" style="color: #00a884;"></i> {{ $ann->title }}
                        </h4>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge badge-primary">{{ $ann->audience }}</span>
                            <button type="button" onclick="openEditNoticeModal({{ json_encode($ann) }})" class="btn btn-secondary btn-sm" style="padding: 4px 8px; border-radius: 6px;" title="Edit Announcement">
                                <i class="fa-solid fa-pen-to-square" style="color: #00a884;"></i>
                            </button>
                            <form id="delete-notice-form-{{ $ann->id }}" action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmSwalDelete('delete-notice-form-{{ $ann->id }}', 'Delete Announcement', 'Are you sure you want to delete this notice?')" class="btn btn-danger btn-sm" style="padding: 4px 8px; border-radius: 6px;" title="Delete Announcement">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <p style="font-size: 0.9rem; color: var(--text-main); line-height: 1.5; white-space: pre-line;">{{ $ann->message }}</p>
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

<!-- Modal: Edit Announcement -->
<div class="modal fade" id="editNoticeModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:90%; max-width:550px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pen-to-square"></i> Edit Company Notice
            </h4>
            <button type="button" onclick="closeEditNoticeModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form id="editNoticeForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Notice Title *</label>
                <input type="text" id="edit_notice_title" name="title" class="form-control" required>
            </div>

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Target Audience *</label>
                <select id="edit_notice_audience" name="audience" class="form-control" required>
                    <option value="All Employees">All Employees</option>
                    <option value="Department">Department Specific</option>
                    <option value="Role">Role Specific</option>
                    <option value="Selected Employees">Selected Employees</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label">Notice Message *</label>
                <textarea id="edit_notice_message" name="message" class="form-control" rows="5" required></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeEditNoticeModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Update Notice</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditNoticeModal(notice) {
    var modal = document.getElementById('editNoticeModal');
    var form = document.getElementById('editNoticeForm');
    form.action = '/admin/announcements/' + notice.id;

    document.getElementById('edit_notice_title').value = notice.title || '';
    document.getElementById('edit_notice_audience').value = notice.audience || 'All Employees';
    document.getElementById('edit_notice_message').value = notice.message || '';

    modal.style.display = 'flex';
}

function closeEditNoticeModal() {
    document.getElementById('editNoticeModal').style.display = 'none';
}
</script>

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
