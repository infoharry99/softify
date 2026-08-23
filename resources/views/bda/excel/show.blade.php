@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'View BDA Work - ' . $bdaWork->title)
@section('page_title', 'BDA Work Details')

@section('content')
<div style="max-width: 950px; margin: 0 auto;">
    <div class="card" style="margin-bottom: 25px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #f0fdf4;">
            <div>
                <h3 class="card-title" style="font-size: 1.25rem; font-weight: 700; color: #065f46; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-file-excel" style="color: #10b981;"></i> {{ $bdaWork->title }}
                </h3>
                <div style="font-size: 0.82rem; color: #047857; margin-top: 4px;">
                    Uploaded by: <strong>{{ $bdaWork->uploadedBy->name ?? 'User' }}</strong> on {{ $bdaWork->created_at->format('M d, Y \a\t h:i A') }}
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="{{ route('bda.excel.edit', $bdaWork->id) }}" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Edit Upload
                </a>
                <a href="{{ route('bda.excel.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Directory
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Metadata Cards Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 25px;">
                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Uploaded By</label>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a;">{{ $bdaWork->uploadedBy->name ?? 'N/A' }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">{{ $bdaWork->uploadedBy->email ?? '' }}</div>
                </div>

                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">User Role</label>
                    @php
                        $roleName = $bdaWork->uploadedBy->roles->pluck('name')->first() ?? 'User';
                    @endphp
                    <span class="badge badge-primary" style="font-size: 0.8rem;">{{ $roleName }}</span>
                </div>

                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Upload Date</label>
                    <div style="font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $bdaWork->created_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">{{ $bdaWork->created_at->format('h:i:s A') }}</div>
                </div>

                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Last Updated</label>
                    <div style="font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $bdaWork->updated_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">{{ $bdaWork->updated_at->format('h:i:s A') }}</div>
                </div>
            </div>

            <!-- Work Description -->
            <div style="margin-bottom: 25px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #0f172a; display: block; margin-bottom: 8px;">Description / Notes:</label>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; font-size: 0.9rem; color: #334155; line-height: 1.6; min-height: 70px;">
                    {!! nl2br(e($bdaWork->description ?? 'No description provided for this upload.')) !!}
                </div>
            </div>

            <!-- Excel File Header Action Card -->
            <div style="background: #f0fdf4; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: #10b981; color: #ffffff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fa-solid fa-file-excel"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.98rem; font-weight: 700; color: #065f46;">{{ $bdaWork->file_name }}</div>
                        <div style="font-size: 0.78rem; color: #047857; margin-top: 2px;">
                            Format: <strong>.{{ $bdaWork->file_extension }}</strong> | Ready for view and download
                        </div>
                    </div>
                </div>

                <a href="{{ route('bda.excel.download', $bdaWork->id) }}" class="btn btn-primary" style="background-color: #10b981; border-color: #10b981; border-radius: 8px; font-weight: 700; padding: 10px 20px;">
                    <i class="fa-solid fa-download"></i> Download Excel File
                </a>
            </div>

            <!-- Interactive In-Browser Excel & CSV Viewer -->
            <div class="card" style="border-top: 4px solid #10b981; border-radius: 12px; overflow: hidden; margin-bottom: 0;">
                <div class="card-header" style="background: #f8fafc; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #e2e8f0; padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-table-cells" style="color: #10b981; font-size: 1.2rem;"></i>
                        <div>
                            <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a;">Live Interactive Excel Sheet Viewer</h4>
                            <span style="font-size: 0.76rem; color: #64748b;">Opening {{ $bdaWork->file_name }} directly inside Softify</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <!-- In-Sheet Quick Search -->
                        <div style="position: relative; width: 230px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
                            <input type="text" id="sheetSearchInput" onkeyup="filterSheetData()" placeholder="Filter cells in sheet..." style="width: 100%; padding: 6px 10px 6px 30px; font-size: 0.8rem; border-radius: 6px; border: 1px solid #cbd5e1;">
                        </div>

                        <a href="{{ route('bda.excel.download', $bdaWork->id) }}" class="btn btn-secondary btn-sm" style="border-radius: 6px; font-size: 0.78rem; font-weight: 600; color: #10b981;">
                            <i class="fa-solid fa-download"></i> Download
                        </a>
                    </div>
                </div>

                <!-- Worksheet Tabs Bar -->
                <div id="sheetTabsBar" style="display: none; background: #cbd5e1; padding: 8px 16px 0 16px; border-bottom: 1px solid #cbd5e1; gap: 4px; flex-wrap: wrap;"></div>

                <div class="card-body" style="padding: 0; position: relative;">
                    <!-- Loading Spinner -->
                    <div id="excelViewerSpinner" style="padding: 60px 20px; text-align: center; color: #64748b;">
                        <i class="fa-solid fa-spinner fa-spin" style="font-size: 2.2rem; color: #10b981; margin-bottom: 12px;"></i>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #334155;">Opening and parsing Excel sheet data...</div>
                        <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px;">Please wait while the workbook loads.</div>
                    </div>

                    <!-- Container for rendered HTML table -->
                    <div id="excelViewerContainer" style="display: none; max-height: 580px; overflow: auto; background: #ffffff;"></div>
                </div>
                
                <div id="excelViewerFooter" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 20px; display: flex; justify-content: space-between; font-size: 0.78rem; color: #64748b;">
                    <div id="sheetStatsInfo">Sheet Data Loaded</div>
                    <div>Softify In-Browser Excel Viewer</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
let currentWorkbook = null;
let currentSheetName = null;
let originalTableRows = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSpreadsheetData();
});

function loadSpreadsheetData() {
    const streamUrl = "{{ url('/bda-excel/' . $bdaWork->id . '/stream') }}";
    
    fetch(streamUrl)
        .then(response => {
            if (!response.ok) throw new Error("HTTP error " + response.status);
            return response.arrayBuffer();
        })
        .then(buffer => {
            const data = new Uint8Array(buffer);
            currentWorkbook = XLSX.read(data, { type: 'array' });
            
            document.getElementById('excelViewerSpinner').style.display = 'none';
            document.getElementById('excelViewerContainer').style.display = 'block';

            renderSheetTabs(currentWorkbook.SheetNames);
            switchSheet(currentWorkbook.SheetNames[0]);
        })
        .catch(error => {
            console.error("Error loading spreadsheet:", error);
            document.getElementById('excelViewerSpinner').innerHTML = `
                <div style="color: #ef4444; font-weight: 700; font-size: 1.1rem; margin-bottom: 8px;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Unable to parse Excel file
                </div>
                <div style="font-size: 0.85rem; color: #64748b;">The file format or contents could not be rendered directly. Please download the file to view locally.</div>
            `;
        });
}

function renderSheetTabs(sheetNames) {
    const tabsBar = document.getElementById('sheetTabsBar');
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
        btn.id = 'tab-btn-' + name.replace(/\s+/g, '-');
        btn.style.cssText = 'padding: 8px 16px; font-size: 0.8rem; font-weight: 600; border: 1px solid #cbd5e1; border-bottom: none; border-radius: 8px 8px 0 0; background: #94a3b8; color: #ffffff; cursor: pointer; transition: all 0.2s;';
        btn.onclick = () => switchSheet(name);
        tabsBar.appendChild(btn);
    });
}

function switchSheet(name) {
    if (!currentWorkbook || !currentWorkbook.Sheets[name]) return;
    currentSheetName = name;

    // Highlight active tab
    if (currentWorkbook.SheetNames.length > 1) {
        currentWorkbook.SheetNames.forEach(s => {
            const tabBtn = document.getElementById('tab-btn-' + s.replace(/\s+/g, '-'));
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

    const worksheet = currentWorkbook.Sheets[name];
    const htmlString = XLSX.utils.sheet_to_html(worksheet, { id: 'liveExcelTable', editable: false });
    
    const container = document.getElementById('excelViewerContainer');
    container.innerHTML = htmlString;

    // Style the generated table
    const table = document.getElementById('liveExcelTable');
    if (table) {
        table.className = 'table table-bordered table-striped';
        table.style.margin = '0';
        table.style.fontSize = '0.82rem';
        table.style.borderCollapse = 'collapse';
        table.style.width = '100%';

        // Add custom header styling
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

        // Store rows for quick filtering
        originalTableRows = Array.from(table.querySelectorAll('tr')).slice(1);
        
        document.getElementById('sheetStatsInfo').innerText = `Worksheet: ${name} | Total Rows: ${trs.length - 1}`;
    }
}

function filterSheetData() {
    const input = document.getElementById('sheetSearchInput').value.toLowerCase();
    originalTableRows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
