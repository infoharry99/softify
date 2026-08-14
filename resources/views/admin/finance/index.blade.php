@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Finance Requirements Directory')
@section('page_title', 'Finance Management & Leads Directory')

@section('styles')
<style>
    .filter-toggle-btn {
        background: #f0faf7;
        border: 1px solid #99f6e4;
        color: #0d9488;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        font-size: 0.95rem;
        cursor: pointer;
        border-radius: var(--radius);
        margin-bottom: 15px;
        transition: all 0.2s ease;
    }
    .filter-toggle-btn:hover {
        background-color: #ccfbf1;
    }
    .filter-panel {
        background-color: #f0faf7;
        border: 1px solid #99f6e4;
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 25px;
    }
    .filter-header {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0d9488;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
</style>
@endsection

@section('content')
<!-- Summary Metric Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-receipt" style="color: #0284c7;"></i> Total Requirements</div>
        <div class="stat-value">{{ $totalCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-sack-dollar" style="color: #10b981;"></i> Total Budget</div>
        <div class="stat-value" style="color: #059669;">₹{{ number_format($totalBudget, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-credit-card" style="color: #ef4444;"></i> Total Remaining</div>
        <div class="stat-value" style="color: #ef4444;">₹{{ number_format($totalRemaining, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-spinner" style="color: #f59e0b;"></i> Pending / In Progress</div>
        <div class="stat-value" style="color: #d97706;">{{ $pendingCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Closed Requirements</div>
        <div class="stat-value" style="color: #10b981;">{{ $closedCount }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Finance Requirements & Leads Directory</h3>
        @if(auth()->user()->hasPermission('finance.create') || auth()->user()->hasRole('super-admin'))
        <a href="{{ route('admin.finance.create') }}" class="btn btn-primary btn-sm" style="background-color: #389685; border-color: #389685;">
            <i class="fa-solid fa-plus"></i> Add Finance Requirement
        </a>
        @endif
    </div>

    @php
        $isFilterActive = request()->anyFilled(['search', 'status', 'company_name', 'min_budget', 'max_budget']);
    @endphp

    <div style="padding: 0 20px; margin-top: 15px;">
        <!-- Toggle Button for Expand / Hide Filters -->
        <button type="button" class="filter-toggle-btn" onclick="toggleFilterPanel()">
            <span>
                <i class="fa-solid fa-filter"></i> Multiple Multi-Filter Search Matrix
                @if($isFilterActive)
                    <span class="badge badge-primary" style="margin-left: 8px;">Active Filters Applied</span>
                @endif
            </span>
            <span id="toggleIcon">{{ $isFilterActive ? '▲' : '▼' }}</span>
        </button>

        <!-- Collapsible Multi-Filter Panel -->
        <div id="filterPanel" class="filter-panel" style="display: {{ $isFilterActive ? 'block' : 'none' }};">
            <div class="filter-header">
                <span><i class="fa-solid fa-sliders"></i> Filter Criteria Matrix</span>
                <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 4px 10px;">
                    <i class="fa-solid fa-rotate-left"></i> Reset All Filters
                </a>
            </div>

            <form action="{{ route('admin.finance.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Search Vendor / Company / Location</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Vendor name, company...">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Company Name</label>
                        <select name="company_name" class="form-control">
                            <option value="">-- All Companies --</option>
                            @foreach($companies as $comp)
                                <option value="{{ $comp }}" {{ request('company_name') === $comp ? 'selected' : '' }}>{{ $comp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- All Status --</option>
                            <option value="No Update" {{ request('status') === 'No Update' ? 'selected' : '' }}>No Update</option>
                            <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Min Budget (₹)</label>
                        <input type="number" name="min_budget" class="form-control" value="{{ request('min_budget') }}" placeholder="e.g. 10000">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Max Budget (₹)</label>
                        <input type="number" name="max_budget" class="form-control" value="{{ request('max_budget') }}" placeholder="e.g. 100000">
                    </div>
                </div>

                <div style="margin-top: 15px; text-align: right;">
                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #389685; border-color: #389685;">
                        <i class="fa-solid fa-magnifying-glass"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Vendor Name</th>
                    <th>Company & Location</th>
                    <th>Candidates</th>
                    <th>Budget</th>
                    <th>Remaining Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requirements as $req)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">{{ $req->vendor_name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Added by: {{ $req->creator->name ?? 'Finance User' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $req->company_name ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-location-dot"></i> {{ $req->vendor_location ?? '-' }}</div>
                    </td>
                    <td>
                        <span class="badge badge-primary" style="font-size: 0.85rem; padding: 5px 12px;">
                            <i class="fa-solid fa-users"></i> {{ $req->selected_candidates_count }}
                        </span>
                    </td>
                    <td><strong>₹{{ number_format($req->budget, 2) }}</strong></td>
                    <td>
                        <span style="color: {{ $req->remaining_payment > 0 ? '#ef4444' : '#10b981' }}; font-weight: 700;">
                            ₹{{ number_format($req->remaining_payment, 2) }}
                        </span>
                    </td>
                    <td>{{ $req->date->format('M d, Y') }}</td>
                    <td>
                        @php
                            $badgeClass = match($req->status) {
                                'No Update' => 'badge-secondary',
                                'In Progress' => 'badge-warning',
                                'Closed' => 'badge-success',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="padding: 6px 12px; font-size: 0.8rem;">
                            {{ $req->status }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 5px;">
                            <a href="{{ route('admin.finance.show', $req->id) }}" class="btn btn-secondary btn-sm" title="View Profile">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            @if(auth()->user()->hasPermission('finance.edit') || auth()->user()->hasRole('super-admin'))
                            <a href="{{ route('admin.finance.edit', $req->id) }}" class="btn btn-secondary btn-sm" title="Edit Record">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.delete') || auth()->user()->hasRole('super-admin'))
                            <form action="{{ route('admin.finance.destroy', $req->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this finance requirement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Record">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 35px;">
                        No finance requirements found matching your filter criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-footer">
        {{ $requirements->withQueryString()->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            toggleIcon.innerText = '▲';
        } else {
            panel.style.display = 'none';
            toggleIcon.innerText = '▼';
        }
    }
</script>
@endsection
