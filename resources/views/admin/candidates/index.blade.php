@extends('layouts.employee')

@section('title', 'Candidate Directory (ATS)')
@section('page_title', 'HR Candidate Management (ATS)')

@section('styles')
<style>
    .ats-search-bar {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }

    .ats-search-input-box {
        position: relative;
        flex: 1;
        min-width: 280px;
    }
    .ats-search-input-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #00a884;
        font-size: 1rem;
    }
    .ats-search-input-box input {
        width: 100%;
        padding: 11px 16px 11px 44px;
        font-size: 0.92rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        outline: none;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .ats-search-input-box input:focus {
        border-color: #00a884;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
    }

    .filter-btn-toggle {
        background-color: #e6f7f3;
        color: #00a884;
        border: 1px solid #9ee5d4;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 10px 18px;
        border-radius: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .filter-btn-toggle:hover {
        background-color: #00a884;
        color: #ffffff;
        border-color: #00a884;
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.25);
    }

    .filter-badge-count {
        background-color: #00a884;
        color: #ffffff;
        border-radius: 9999px;
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: 800;
    }
    .filter-btn-toggle:hover .filter-badge-count {
        background-color: #ffffff;
        color: #00a884;
    }

    /* Expanded Filter Drawer Card */
    .filter-panel-card {
        background: #ffffff;
        border: 1px solid #9ee5d4;
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(0, 168, 132, 0.08);
    }

    .filter-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 18px;
    }

    .filter-field-group {
        position: relative;
    }
    .filter-field-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .filter-field-input-wrap {
        position: relative;
    }
    .filter-field-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.88rem;
        pointer-events: none;
    }
    .filter-field-input-wrap input,
    .filter-field-input-wrap select {
        width: 100%;
        padding: 9px 12px 9px 36px;
        font-size: 0.88rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        outline: none;
        background-color: #ffffff;
        transition: all 0.15s ease;
    }
    .filter-field-input-wrap input:focus,
    .filter-field-input-wrap select:focus {
        border-color: #00a884;
        box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.12);
    }

    /* Active Filter Tags Bar */
    .active-filter-tags-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed var(--border-color);
    }
    .filter-tag-chip {
        background-color: #e6f7f3;
        color: #00a884;
        border: 1px solid #9ee5d4;
        border-radius: 9999px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    /* AJAX Table Loading Overlay */
    .table-container-wrapper {
        position: relative;
        min-height: 250px;
    }
    .table-loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.62);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius);
        transition: all 0.2s ease;
    }
    .loader-card-content {
        text-align: center;
        background: #ffffff;
        padding: 20px 32px;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border: 1px solid #9ee5d4;
    }
    .loader-spinner {
        font-size: 2.2rem;
        color: #00a884;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Resume Modal Overlay */
    .resume-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .resume-modal-card {
        background: #ffffff;
        width: 100%;
        max-width: 900px;
        height: 85vh;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .resume-modal-header {
        padding: 16px 24px;
        background: #0f172a;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    @media (max-width: 990px) {
        .filter-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 600px) {
        .filter-grid-4 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Candidate Directory (ATS)</h3>
        @if((auth()->user()->hasPermission('hr.create') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('data-entry')) && !auth()->user()->hasRole('talent-acquisition'))
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.candidates.quick_create') }}" class="btn btn-secondary btn-sm" style="border: 1.5px solid #9ee5d4; color: #00a884; font-weight: 700; padding: 9px 16px; border-radius: 10px; background: #e6f7f3;">
                <i class="fa-solid fa-bolt"></i> ⚡ Quick Add Candidate
            </a>
            <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary btn-sm" style="background-color: #00a884; border-color: #00a884; padding: 9px 18px; border-radius: 10px; font-weight: 700;">
                <i class="fa-solid fa-user-plus"></i> + Add New Candidate
            </a>
        </div>
        @endif
    </div>

    @php
        $activeFilterCount = 0;
        foreach(['search', 'job_title', 'skill', 'job_type', 'notice_period', 'expected_ctc'] as $key) {
            if(request()->filled($key)) $activeFilterCount++;
        }
        $isFilterActive = $activeFilterCount > 0;
    @endphp

    <div style="padding: 20px 20px 0 20px;">
        <form id="atsFilterForm" action="{{ route('admin.candidates.index') }}" method="GET">
            <!-- Top Search & Quick Filter Bar -->
            <div class="ats-search-bar">
                <div class="ats-search-input-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" name="search" placeholder="Search by candidate name, email, phone, location, job title, skills..." value="{{ request('search') }}" autocomplete="off">
                </div>

                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="filter-btn-toggle" onclick="toggleFilterPanel()">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Advanced Filters</span>
                        <span id="activeFilterBadge" class="filter-badge-count" style="{{ $activeFilterCount > 0 ? '' : 'display: none;' }}">{{ $activeFilterCount }}</span>
                    </button>

                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #00a884; border-color: #00a884; padding: 10px 18px; border-radius: 10px; font-weight: 700;">
                        <i class="fa-solid fa-filter"></i> Search
                    </button>

                    <button type="button" onclick="resetATSFilters()" class="btn btn-secondary btn-sm" style="border-radius: 10px; padding: 10px 14px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Expandable Advanced Multi-Filter Grid -->
            <div class="filter-panel-card" id="filterPanel" style="{{ $isFilterActive ? 'display: block;' : 'display: none;' }}">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; padding-bottom: 10px; border-bottom: 1.5px solid #e6f7f3;">
                    <div style="font-size: 0.98rem; font-weight: 700; color: #00a884; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-cubes-stacked"></i> ATS Candidate Filter Matrix
                    </div>
                    <div>
                        <button type="button" onclick="resetATSFilters()" style="background: none; border: none; font-size: 0.8rem; color: #64748b; font-weight: 600; cursor: pointer;">
                            <i class="fa-solid fa-xmark"></i> Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Row 1 -->
                <div class="filter-grid-4">
                    <div class="filter-field-group">
                        <label class="filter-field-label"><i class="fa-solid fa-user-gear" style="color: #00a884;"></i> Job Title</label>
                        <div class="filter-field-input-wrap">
                            <i class="fa-solid fa-user-gear"></i>
                            <input type="text" name="job_title" placeholder="e.g. Senior Python Developer..." value="{{ request('job_title') }}">
                        </div>
                    </div>

                    <div class="filter-field-group">
                        <label class="filter-field-label"><i class="fa-solid fa-code" style="color: #00a884;"></i> Skills & Tech Stack</label>
                        <div class="filter-field-input-wrap">
                            <i class="fa-solid fa-code"></i>
                            <input type="text" name="skill" placeholder="e.g. React, PHP, Laravel..." value="{{ request('skill') }}">
                        </div>
                    </div>

                    <div class="filter-field-group">
                        <label class="filter-field-label"><i class="fa-solid fa-briefcase" style="color: #00a884;"></i> Job Type</label>
                        <div class="filter-field-input-wrap">
                            <i class="fa-solid fa-briefcase"></i>
                            <select name="job_type">
                                <option value="">All Job Types</option>
                                <option value="Full Time" {{ request('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part Time" {{ request('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Contract" {{ request('job_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Remote" {{ request('job_type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                                <option value="Hybrid" {{ request('job_type') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-field-group">
                        <label class="filter-field-label"><i class="fa-solid fa-clock" style="color: #00a884;"></i> Notice Period</label>
                        <div class="filter-field-input-wrap">
                            <i class="fa-solid fa-clock"></i>
                            <select name="notice_period">
                                <option value="">All Notice Periods</option>
                                <option value="Immediate" {{ request('notice_period') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                                <option value="15 Days" {{ request('notice_period') === '15 Days' ? 'selected' : '' }}>15 Days</option>
                                <option value="30 Days" {{ request('notice_period') === '30 Days' ? 'selected' : '' }}>30 Days</option>
                                <option value="60 Days" {{ request('notice_period') === '60 Days' ? 'selected' : '' }}>60 Days</option>
                                <option value="90 Days" {{ request('notice_period') === '90 Days' ? 'selected' : '' }}>90 Days</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 2 -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 0;">
                    <div class="filter-field-group">
                        <label class="filter-field-label"><i class="fa-solid fa-indian-rupee-sign" style="color: #00a884;"></i> Expected CTC (₹)</label>
                        <div class="filter-field-input-wrap">
                            <i class="fa-solid fa-indian-rupee-sign"></i>
                            <input type="number" step="50000" min="0" name="expected_ctc" placeholder="e.g. 800000" value="{{ request('expected_ctc') }}">
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-end; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1; background-color: #00a884; border-color: #00a884; padding: 10px; border-radius: 10px; font-weight: 700;">
                            <i class="fa-solid fa-check"></i> Apply Matrix
                        </button>
                        <button type="button" onclick="resetATSFilters()" class="btn btn-secondary" style="padding: 10px 14px; border-radius: 10px;">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Candidate List Table Container with AJAX Loader Overlay -->
    <div class="table-container-wrapper" id="tableContainerWrapper">
        <!-- AJAX Logo Spinner Overlay -->
        <div class="table-loading-overlay" id="tableLoadingOverlay" style="display: none;">
            <div class="loader-card-content" style="padding: 24px 36px; display: flex; flex-direction: column; align-items: center;">
                <div style="position: relative; display: inline-block; margin-bottom: 8px;">
                    <img src="{{ asset('images/logo.png') }}" alt="Talentifyy" style="height: 48px; width: auto; animation: logoBouncePulse 1.4s infinite ease-in-out;">
                </div>
                <div style="font-weight: 800; font-size: 0.95rem; color: #00a884; letter-spacing: -0.2px;">TALENTIFYY</div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600; margin-top: 2px;">Filtering Candidate Matrix...</div>
            </div>
        </div>

        <div id="candidatesTableContainer">
            @include('admin.candidates.partials.table')
        </div>
    </div>
</div>

<!-- Modal Resume Document Viewer -->
<div class="resume-modal-overlay" id="resumePreviewModal">
    <div class="resume-modal-card">
        <div class="resume-modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-file-pdf" style="color: #00a884; font-size: 1.2rem;"></i>
                <span id="resumeModalCandidateName" style="font-weight: 700; font-size: 1.05rem;">Resume Document Preview</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a id="resumeModalDownloadBtn" href="#" class="btn btn-primary btn-sm" download style="background-color: #00a884; border-color: #00a884; border-radius: 8px; font-weight: 600;">
                    <i class="fa-solid fa-download"></i> Download Resume
                </a>
                <button type="button" onclick="closeResumeModal()" style="background: transparent; border: none; color: #ffffff; font-size: 1.2rem; cursor: pointer; padding: 4px 8px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <div style="flex: 1; background: #525659;">
            <iframe id="resumeModalIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let searchDebounceTimer = null;

    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }

    // AJAX Candidate Loader Function
    function loadCandidatesAJAX(targetUrl = null) {
        const form = document.getElementById('atsFilterForm');
        const overlay = document.getElementById('tableLoadingOverlay');
        
        let url = targetUrl;
        if (!url) {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            url = form.action + '?' + params.toString();
        }

        // Show Loader Spinner
        overlay.style.display = 'flex';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                document.getElementById('candidatesTableContainer').innerHTML = data.html;
                // Re-bind pagination AJAX clicks
                bindPaginationClicks();
                // Update URL in browser bar without page reload
                window.history.pushState({}, '', url);
            }
        })
        .catch(error => {
            console.error('AJAX Filter Error:', error);
        })
        .finally(() => {
            // Hide Loader Spinner
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 180);
        });
    }

    function bindPaginationClicks() {
        document.querySelectorAll('#candidatesTableContainer .ajax-pagination-links a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                loadCandidatesAJAX(this.href);
            });
        });
    }

    function resetATSFilters() {
        const form = document.getElementById('atsFilterForm');
        form.reset();
        form.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => input.value = '');
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        loadCandidatesAJAX(form.action);
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('atsFilterForm');

        // Form Submit via AJAX
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            loadCandidatesAJAX();
        });

        // Live Search Input Debounce (300ms)
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                loadCandidatesAJAX();
            }, 320);
        });

        // Status Select Dropdown Instant Filter (if present)
        const statusSelect = document.getElementById('statusSelect');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                loadCandidatesAJAX();
            });
        }

        // Bind initial pagination links
        bindPaginationClicks();
    });

    // Resume Modal Functions
    function openResumeModal(previewUrl, downloadUrl, candidateName) {
        document.getElementById('resumeModalCandidateName').innerText = 'Resume Preview: ' + candidateName;
        document.getElementById('resumeModalIframe').src = previewUrl;
        document.getElementById('resumeModalDownloadBtn').href = downloadUrl;
        document.getElementById('resumePreviewModal').style.display = 'flex';
    }

    function closeResumeModal() {
        document.getElementById('resumePreviewModal').style.display = 'none';
        document.getElementById('resumeModalIframe').src = '';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeResumeModal();
        }
    });
</script>
@endsection
