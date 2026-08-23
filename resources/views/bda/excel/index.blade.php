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
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">Upload, view, and open Excel work reports directly in system.</p>
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
                        <div style="max-width: 200px; font-size: 0.82rem; color: #475569; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $work->description }}">
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
                        <button type="button" onclick="openExcelModal('{{ route('bda.excel.stream', $work->id) }}', '{{ route('bda.excel.download', $work->id) }}', '{{ addslashes($work->title) }}', '{{ addslashes($work->file_name) }}')" style="background: none; border: none; padding: 0; cursor: pointer; text-align: left;">
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 0.82rem; color: #0284c7; font-weight: 600;">
                                <i class="fa-solid fa-file-excel" style="color: #10b981; font-size: 1.1rem;"></i>
                                <span style="max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $work->file_name }}">
                                    {{ $work->file_name }}
                                </span>
                            </div>
                        </button>
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
                            <!-- Open Excel Viewer Action -->
                            <button type="button" onclick="openExcelModal('{{ route('bda.excel.stream', $work->id) }}', '{{ route('bda.excel.download', $work->id) }}', '{{ addslashes($work->title) }}', '{{ addslashes($work->file_name) }}')" class="btn btn-secondary btn-sm" title="Open Excel Sheet in Browser" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #10b981;">
                                <i class="fa-solid fa-table-cells"></i> Open Sheet
                            </button>

                            <!-- View Details Page Action -->
                            <a href="{{ route('bda.excel.show', $work->id) }}" class="btn btn-secondary btn-sm" title="View Details" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600;">
                                <i class="fa-solid fa-eye" style="color: #00a884;"></i> View
                            </a>

                            <!-- Edit Action -->
                            <a href="{{ route('bda.excel.edit', $work->id) }}" class="btn btn-secondary btn-sm" title="Edit Upload" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #475569;">
                                <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Edit
                            </a>

                            <!-- Download Action -->
                            <a href="{{ route('bda.excel.download', $work->id) }}" class="btn btn-secondary btn-sm" title="Download Excel File" style="border-radius: 6px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #64748b;">
                                <i class="fa-solid fa-download"></i>
                            </a>

                            <!-- Delete Action -->
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

<!-- Modal: Quick Excel & CSV Sheet Previewer -->
<div id="excelPreviewModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; width: 100%; max-width: 1100px; height: 88vh; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column; overflow: hidden; animation: modalSlideUp 0.25s ease-out;">
        <!-- Modal Header -->
        <div style="padding: 16px 24px; background: #f0fdf4; border-bottom: 1px solid #a7f3d0; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background: #10b981; color: #ffffff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="fa-solid fa-file-excel"></i>
                </div>
                <div>
                    <h3 id="modalExcelTitle" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #065f46;">Excel Sheet Preview</h3>
                    <div id="modalExcelSubtitle" style="font-size: 0.78rem; color: #047857;">Opening file...</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <a id="modalDownloadBtn" href="#" class="btn btn-primary btn-sm" style="background-color: #10b981; border-color: #10b981; font-size: 0.8rem; font-weight: 700;">
                    <i class="fa-solid fa-download"></i> Download Excel
                </a>
                <button type="button" onclick="closeExcelModal()" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer; padding: 4px 8px;">✕</button>
            </div>
        </div>

        <!-- Sheet Tabs Bar -->
        <div id="modalSheetTabsBar" style="display: none; background: #cbd5e1; padding: 8px 16px 0 16px; border-bottom: 1px solid #cbd5e1; gap: 4px; flex-wrap: wrap;"></div>

        <!-- Filter Search Bar -->
        <div style="padding: 10px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <div style="position: relative; width: 280px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
                <input type="text" id="modalSheetSearchInput" onkeyup="filterModalSheetData()" placeholder="Filter cells in worksheet..." style="width: 100%; padding: 6px 10px 6px 30px; font-size: 0.8rem; border-radius: 6px; border: 1px solid #cbd5e1;">
            </div>
            <div id="modalSheetStats" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Worksheet Stats</div>
        </div>

        <!-- Modal Body Container -->
        <div style="flex: 1; overflow: auto; position: relative; background: #ffffff;">
            <div id="modalExcelSpinner" style="padding: 80px 20px; text-align: center; color: #64748b;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; color: #10b981; margin-bottom: 12px;"></i>
                <div style="font-size: 1rem; font-weight: 700; color: #334155;">Parsing Excel Sheet Data...</div>
            </div>
            <div id="modalExcelContainer" style="display: none; height: 100%;"></div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 12px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #64748b;">
            <div>Softify Live Excel Viewer Engine</div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeExcelModal()">Close Preview</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
let modalWorkbook = null;
let modalOriginalRows = [];

function openExcelModal(streamUrl, downloadUrl, title, fileName) {
    document.getElementById('modalExcelTitle').innerText = title;
    document.getElementById('modalExcelSubtitle').innerText = 'File: ' + fileName;
    document.getElementById('modalDownloadBtn').href = downloadUrl;

    const modal = document.getElementById('excelPreviewModal');
    modal.style.display = 'flex';

    document.getElementById('modalExcelSpinner').style.display = 'block';
    document.getElementById('modalExcelContainer').style.display = 'none';
    document.getElementById('modalSheetTabsBar').style.display = 'none';

    fetch(streamUrl)
        .then(response => {
            if (!response.ok) throw new Error("HTTP error " + response.status);
            return response.arrayBuffer();
        })
        .then(buffer => {
            const data = new Uint8Array(buffer);
            modalWorkbook = XLSX.read(data, { type: 'array' });

            document.getElementById('modalExcelSpinner').style.display = 'none';
            document.getElementById('modalExcelContainer').style.display = 'block';

            renderModalSheetTabs(modalWorkbook.SheetNames);
            switchModalSheet(modalWorkbook.SheetNames[0]);
        })
        .catch(error => {
            console.error("Error loading spreadsheet:", error);
            document.getElementById('modalExcelSpinner').innerHTML = `
                <div style="color: #ef4444; font-weight: 700; font-size: 1.1rem; margin-bottom: 8px;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Unable to parse Excel file
                </div>
                <div style="font-size: 0.85rem; color: #64748b;">The file format or contents could not be rendered directly. Please download the file to view locally.</div>
            `;
        });
}

function closeExcelModal() {
    document.getElementById('excelPreviewModal').style.display = 'none';
}

function renderModalSheetTabs(sheetNames) {
    const tabsBar = document.getElementById('modalSheetTabsBar');
    if (!sheetNames || sheetNames.length <= 1) {
        tabsBar.style.display = 'none';
        return;
    }

    tabsBar.style.display = 'flex';
    tabsBar.innerHTML = '';

    sheetNames.forEach(name => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerText = '📊 ' + name;
        btn.id = 'modal-tab-btn-' + name.replace(/\s+/g, '-');
        btn.style.cssText = 'padding: 8px 16px; font-size: 0.8rem; font-weight: 600; border: 1px solid #cbd5e1; border-bottom: none; border-radius: 8px 8px 0 0; background: #94a3b8; color: #ffffff; cursor: pointer; transition: all 0.2s;';
        btn.onclick = () => switchModalSheet(name);
        tabsBar.appendChild(btn);
    });
}

function switchModalSheet(name) {
    if (!modalWorkbook || !modalWorkbook.Sheets[name]) return;

    if (modalWorkbook.SheetNames.length > 1) {
        modalWorkbook.SheetNames.forEach(s => {
            const tabBtn = document.getElementById('modal-tab-btn-' + s.replace(/\s+/g, '-'));
            if (tabBtn) {
                if (s === name) {
                    tabBtn.style.background = '#ffffff';
                    tabBtn.style.color = '#065f46';
                    tabBtn.style.fontWeight = '700';
                } else {
                    tabBtn.style.background = '#94a3b8';
                    tabBtn.style.color = '#ffffff';
                    tabBtn.style.fontWeight = '600';
                }
            }
        });
    }

    const worksheet = modalWorkbook.Sheets[name];
    const htmlString = XLSX.utils.sheet_to_html(worksheet, { id: 'modalLiveExcelTable', editable: false });

    const container = document.getElementById('modalExcelContainer');
    container.innerHTML = htmlString;

    const table = document.getElementById('modalLiveExcelTable');
    if (table) {
        table.className = 'table table-bordered table-striped';
        table.style.margin = '0';
        table.style.fontSize = '0.82rem';
        table.style.borderCollapse = 'collapse';
        table.style.width = '100%';

        const trs = table.querySelectorAll('tr');
        if (trs.length > 0) {
            trs[0].style.background = '#f1f5f9';
            trs[0].style.color = '#0f172a';
            trs[0].style.fontWeight = '700';
            trs[0].style.position = 'sticky';
            trs[0].style.top = '0';
            trs[0].style.zIndex = '2';
        }

        table.querySelectorAll('td, th').forEach(cell => {
            cell.style.padding = '8px 12px';
            cell.style.border = '1px solid #cbd5e1';
            cell.style.whiteSpace = 'nowrap';
        });

        modalOriginalRows = Array.from(table.querySelectorAll('tr')).slice(1);
        document.getElementById('modalSheetStats').innerText = `Worksheet: ${name} | Total Rows: ${trs.length - 1}`;
    }
}

function filterModalSheetData() {
    const input = document.getElementById('modalSheetSearchInput').value.toLowerCase();
    modalOriginalRows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeExcelModal();
    }
});
</script>
@endsection
