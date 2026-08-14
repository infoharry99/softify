@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Candidate Recruitment Directory')
@section('page_title', 'HR Candidate Management (ATS)')

@section('styles')
<style>
    .filter-toggle-btn {
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        color: #0f766e;
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
        background-color: #f0fdfa;
        border: 1px solid #99f6e4;
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 25px;
    }
    .filter-header {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f766e;
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
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Candidate Directory (ATS)</h3>
        @if(auth()->user()->hasPermission('hr.create') || auth()->user()->hasRole('super-admin'))
        <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary btn-sm" style="background-color: #0d9488; border-color: #0d9488;">
            ➕ Add New Candidate
        </a>
        @endif
    </div>

    @php
        $isFilterActive = request()->anyFilled(['search', 'skill', 'job_type', 'notice_period', 'status', 'company_name', 'min_exp', 'max_exp', 'max_expected_ctc']);
    @endphp

    <div style="padding: 0 20px; margin-top: 15px;">
        <!-- Toggle Button for Expand / Hide Filters -->
        <button type="button" class="filter-toggle-btn" onclick="toggleFilterPanel()">
            <span>
                🔍 Multiple Multi-Filter Search Matrix
                <small style="color: #0d9488; font-weight: 500; margin-left: 8px;">
                    (Click to <span id="toggleText">{{ $isFilterActive ? 'Hide' : 'Show / Expand' }}</span> Filters)
                </small>
                @if($isFilterActive)
                    <span class="badge badge-success" style="margin-left: 8px; font-size: 0.75rem;">Active Filters Applied</span>
                @endif
            </span>
            <span id="toggleIcon">{{ $isFilterActive ? '▲' : '▼' }}</span>
        </button>

        <!-- Multiple Multi-Filter Option Panel (Collapsible) -->
        <div class="filter-panel" id="filterPanel" style="{{ $isFilterActive ? 'display: block;' : 'display: none;' }}">
            <div class="filter-header">
                <span>Filter Criteria</span>
                @if($isFilterActive)
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem;">Clear All Filters</a>
                @endif
            </div>

            <form action="{{ route('admin.candidates.index') }}" method="GET">
                <div class="filter-grid">
                    <!-- Search text -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Search Keyword</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, Email, Phone, City..." value="{{ request('search') }}">
                    </div>

                    <!-- Skill search -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Skills Keyword</label>
                        <input type="text" name="skill" class="form-control" placeholder="e.g. React, PHP, Laravel..." value="{{ request('skill') }}">
                    </div>

                    <!-- Client Hiring Company -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Hiring Company</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Client Company..." value="{{ request('company_name') }}">
                    </div>

                    <!-- Job Type Filter -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Job Type</label>
                        <select name="job_type" class="form-control">
                            <option value="">-- All Job Types --</option>
                            <option value="Full Time" {{ request('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                            <option value="Part Time" {{ request('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                            <option value="Contract" {{ request('job_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Remote" {{ request('job_type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                            <option value="Hybrid" {{ request('job_type') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <!-- Notice Period Filter -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Notice Period</label>
                        <select name="notice_period" class="form-control">
                            <option value="">-- All Notice Periods --</option>
                            <option value="Immediate" {{ request('notice_period') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                            <option value="15 Days" {{ request('notice_period') === '15 Days' ? 'selected' : '' }}>15 Days</option>
                            <option value="30 Days" {{ request('notice_period') === '30 Days' ? 'selected' : '' }}>30 Days</option>
                            <option value="60 Days" {{ request('notice_period') === '60 Days' ? 'selected' : '' }}>60 Days</option>
                            <option value="90 Days" {{ request('notice_period') === '90 Days' ? 'selected' : '' }}>90 Days</option>
                        </select>
                    </div>

                    <!-- Pipeline Status Filter -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Pipeline Stage / Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- All Stages --</option>
                            <option value="Applied" {{ request('status') === 'Applied' ? 'selected' : '' }}>Applied</option>
                            <option value="Screening" {{ request('status') === 'Screening' ? 'selected' : '' }}>Screening</option>
                            <option value="Interview Scheduled" {{ request('status') === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="Offered" {{ request('status') === 'Offered' ? 'selected' : '' }}>Offered</option>
                            <option value="Hired" {{ request('status') === 'Hired' ? 'selected' : '' }}>Hired</option>
                            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Min Experience -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Min Experience (Years)</label>
                        <input type="number" step="0.5" name="min_exp" class="form-control" placeholder="e.g. 2" value="{{ request('min_exp') }}">
                    </div>

                    <!-- Max Expected CTC -->
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Max Expected CTC (₹)</label>
                        <input type="number" step="50000" name="max_expected_ctc" class="form-control" placeholder="e.g. 800000" value="{{ request('max_expected_ctc') }}">
                    </div>
                </div>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    <button type="submit" class="btn btn-primary" style="background-color: #0d9488; border-color: #0d9488;">
                        ⚡ Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Candidate List Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Candidate Details</th>
                    <th>Hiring Client Company</th>
                    <th>Skills & Experience</th>
                    <th>Notice Period & Job Type</th>
                    <th>Current / Expected CTC</th>
                    <th>Pipeline Stage</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $cand)
                <tr>
                    <td>
                        <strong style="color: var(--text-main); font-size: 0.95rem;">{{ $cand->name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">📧 {{ $cand->email }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">📞 {{ $cand->phone }} | 📍 {{ $cand->location }}</div>
                    </td>
                    <td>
                        <strong>🏢 {{ $cand->company_name ?? 'General Pool' }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Added by: {{ $cand->hr->name ?? 'HR' }}</div>
                    </td>
                    <td>
                        <div style="max-width: 200px; display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 4px;">
                            @foreach(explode(',', $cand->skills) as $sk)
                                <span class="badge badge-secondary" style="font-size: 0.7rem;">{{ trim($sk) }}</span>
                            @endforeach
                        </div>
                        <div style="font-size: 0.8rem; font-weight: 600; color: #0d9488;">
                            ⭐ {{ $cand->experience }} Years Exp
                        </div>
                    </td>
                    <td>
                        <div><span class="badge badge-primary">{{ $cand->job_type }}</span></div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 3px;">Notice: <strong>{{ $cand->notice_period }}</strong></div>
                    </td>
                    <td>
                        <div style="font-size: 0.8rem;">Current: ₹{{ number_format($cand->current_ctc ?? 0) }}</div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #059669;">Expected: ₹{{ number_format($cand->expected_ctc ?? 0) }}</div>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($cand->status) {
                                'Applied' => 'badge-secondary',
                                'Screening' => 'badge-primary',
                                'Interview Scheduled' => 'badge-warning',
                                'Offered', 'Hired' => 'badge-success',
                                'Rejected' => 'badge-danger',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="padding: 6px 12px; font-size: 0.8rem;">
                            {{ $cand->status }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 5px;">
                            <a href="{{ route('admin.candidates.show', $cand->id) }}" class="btn btn-secondary btn-sm" title="View Profile">
                                👁️ View
                            </a>
                            @if(auth()->user()->hasPermission('hr.edit') || auth()->user()->hasRole('super-admin'))
                            <a href="{{ route('admin.candidates.edit', $cand->id) }}" class="btn btn-secondary btn-sm" title="Edit Candidate">
                                ✏️ Edit
                            </a>
                            @endif
                            @if($cand->resume)
                                <a href="{{ route('admin.candidates.resume', $cand->id) }}" class="btn btn-secondary btn-sm" title="Download Resume">
                                    📄 Resume
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 35px;">
                        No candidates found matching your filter criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-footer">
        {{ $candidates->withQueryString()->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        const toggleText = document.getElementById('toggleText');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            toggleText.innerText = 'Hide';
            toggleIcon.innerText = '▲';
        } else {
            panel.style.display = 'none';
            toggleText.innerText = 'Show / Expand';
            toggleIcon.innerText = '▼';
        }
    }
</script>
@endsection
