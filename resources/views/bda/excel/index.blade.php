@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'BDA Work Excel Uploads')
@section('page_title', 'BDA Work Excel Uploads & Files Directory')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <i class="fa-solid fa-file-excel" style="color: #10b981;"></i> BDA Work Excel Directory
            </h3>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">Upload, manage, and view Excel work reports for BDA team members.</p>
        </div>

        <a href="{{ route('bda.excel.create') }}" class="btn btn-primary" style="border-radius: 8px; font-weight: 700;">
            <i class="fa-solid fa-cloud-arrow-up"></i> + Upload New BDA Work
        </a>
    </div>

    <!-- Search & Filters -->
    <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('bda.excel.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <div style="flex: 1; min-width: 220px;">
                <input type="text" name="search" class="form-control" placeholder="Search by title or description..." value="{{ request('search') }}">
            </div>

            @if(($isAdmin || $isLead) && count($usersList) > 0)
            <div style="width: 210px;">
                <select name="uploaded_by" class="form-control">
                    <option value="">-- All Uploaded By --</option>
                    @foreach($usersList as $u)
                        <option value="{{ $u->id }}" {{ request('uploaded_by') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->roles->pluck('name')->first() ?? 'User' }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div style="width: 170px;">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter Date">
            </div>

            <button type="submit" class="btn btn-primary btn-sm" style="background-color: #00a884; border-color: #00a884; border-radius: 6px; padding: 8px 16px; font-weight: 600;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>

            @if(request()->filled('search') || request()->filled('uploaded_by') || request()->filled('date'))
                <a href="{{ route('bda.excel.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 8px 14px;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Listing Table -->
    <div class="table-responsive">
        <table class="table" style="vertical-align: middle;">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Uploaded By</th>
                    <th>User Role</th>
                    <th>Excel File Name</th>
                    <th>Upload Date</th>
                    <th>Last Updated</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bdaWorks as $work)
                <tr>
                    <td>
                        <strong style="color: #0f172a; font-size: 0.92rem;">
                            <a href="{{ route('bda.excel.show', $work->id) }}" style="color: #0f172a; text-decoration: none;">
                                {{ $work->title }}
                            </a>
                        </strong>
                    </td>
                    <td>
                        <div style="max-width: 220px; font-size: 0.82rem; color: #475569; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $work->description }}">
                            {{ $work->description ?? 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <strong style="color: #334155; font-size: 0.88rem;">{{ $work->uploadedBy->name ?? 'Unknown' }}</strong>
                        <div style="font-size: 0.74rem; color: var(--text-muted);">{{ $work->uploadedBy->email ?? '' }}</div>
                    </td>
                    <td>
                        @php
                            $roleName = $work->uploadedBy->roles->pluck('name')->first() ?? 'User';
                            $badgeClass = match($roleName) {
                                'Super Admin', 'Admin' => 'badge-danger',
                                'BDA Team Lead' => 'badge-success',
                                'BDA' => 'badge-primary',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="font-size: 0.72rem;">{{ $roleName }}</span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.82rem; color: #0284c7; font-weight: 600;">
                            <i class="fa-solid fa-file-excel" style="color: #10b981; font-size: 1rem;"></i>
                            <span style="max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $work->file_name }}">
                                {{ $work->file_name }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.82rem; color: #334155;">{{ $work->created_at->format('M d, Y') }}</div>
                        <div style="font-size: 0.74rem; color: var(--text-muted);">{{ $work->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.82rem; color: #334155;">{{ $work->updated_at->format('M d, Y') }}</div>
                        <div style="font-size: 0.74rem; color: var(--text-muted);">{{ $work->updated_at->format('h:i A') }}</div>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 6px; justify-content: flex-end;">
                            <!-- View Action -->
                            <a href="{{ route('bda.excel.show', $work->id) }}" class="btn btn-secondary btn-sm" title="View Details" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600;">
                                <i class="fa-solid fa-eye" style="color: #00a884;"></i> View
                            </a>

                            <!-- Edit Action -->
                            <a href="{{ route('bda.excel.edit', $work->id) }}" class="btn btn-secondary btn-sm" title="Edit Upload" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #475569;">
                                <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Edit
                            </a>

                            <!-- Download Action -->
                            <a href="{{ route('bda.excel.download', $work->id) }}" class="btn btn-secondary btn-sm" title="Download Excel File" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #10b981;">
                                <i class="fa-solid fa-download"></i> Download
                            </a>

                            <!-- Delete Action (If Admin or Lead or Creator) -->
                            <form id="delete-bda-work-{{ $work->id }}" action="{{ route('bda.excel.destroy', $work->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-secondary btn-sm" onclick="confirmDeleteWork({{ $work->id }}, '{{ addslashes($work->title) }}')" title="Delete Upload" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; color: #ef4444;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 45px 20px;">
                        <div style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 10px;">
                            <i class="fa-solid fa-file-excel"></i>
                        </div>
                        <div style="font-size: 0.98rem; font-weight: 700; color: #475569;">No BDA Work Excel Files Found</div>
                        <div style="font-size: 0.82rem; color: #94a3b8; margin-top: 4px;">Click the button above to upload a new BDA Work Excel file.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div style="padding: 16px 20px; background: #ffffff; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 0.85rem; color: #64748b;">
        <div>
            @if($bdaWorks->total() > 0)
                Showing <strong style="color: #0f172a;">{{ $bdaWorks->firstItem() }}</strong> to <strong style="color: #0f172a;">{{ $bdaWorks->lastItem() }}</strong> of <strong style="color: #00a884;">{{ $bdaWorks->total() }}</strong> uploaded files
            @else
                Showing 0 uploaded files
            @endif
        </div>

        <div>
            {{ $bdaWorks->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
function confirmDeleteWork(id, title) {
    Swal.fire({
        title: 'Delete BDA Work Upload?',
        text: "Are you sure you want to delete '" + title + "'? The Excel file will be permanently removed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete Upload'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-bda-work-' + id).submit();
        }
    });
}
</script>
@endsection
