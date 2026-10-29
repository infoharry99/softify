@extends('layouts.employee')

@section('title', 'Search Candidates - Naukri Resdex')
@section('page_title', 'HR Candidate Management (ATS)')

@section('styles')
<style>
    :root {
        --resdex-teal: #00a884;
        --resdex-teal-dark: #008f70;
        --resdex-teal-light: #e6f7f3;
        --resdex-teal-border: #9ee5d4;
        --resdex-blue: #0284c7;
        --resdex-border: #cbd5e1;
        --resdex-border-light: #e2e8f0;
        --resdex-bg: #f8fafc;
        --resdex-text: #0f172a;
        --resdex-muted: #64748b;
    }

    /* Top Navigation Tabs */
    .ats-resdex-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 22px;
        border-bottom: 2px solid var(--resdex-border-light);
        padding-bottom: 2px;
    }
    .resdex-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--resdex-muted);
        text-decoration: none;
        border-radius: 10px 10px 0 0;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
        margin-bottom: -4px;
    }
    .resdex-tab-btn:hover {
        color: var(--resdex-teal);
        background: #f8fafc;
    }
    .resdex-tab-btn.active {
        color: var(--resdex-teal);
        background: #ffffff;
        border-bottom: 3px solid var(--resdex-teal);
    }
    .resdex-tab-btn .badge-pill {
        font-size: 0.7rem;
        padding: 2px 7px;
        border-radius: 999px;
        background: var(--resdex-teal);
        color: #ffffff;
    }

    /* Search Form Layout Grid */
    .resdex-form-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 28px;
        align-items: start;
    }

    /* Main Form Card */
    .resdex-form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--resdex-border-light);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        padding: 32px 36px;
    }

    .form-page-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--resdex-text);
        margin: 0 0 24px 0;
        letter-spacing: -0.5px;
    }

    /* Form Section */
    .form-group-block {
        margin-bottom: 28px;
        position: relative;
    }
    .form-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .form-field-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    /* Boolean Toggle Pill */
    .boolean-toggle-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
    }
    .switch-pill {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 22px;
    }
    .switch-pill input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider-pill {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .25s;
        border-radius: 22px;
    }
    .slider-pill:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .25s;
        border-radius: 50%;
    }
    .switch-pill input:checked + .slider-pill {
        background-color: var(--resdex-teal);
    }
    .switch-pill input:checked + .slider-pill:before {
        transform: translateX(22px);
    }

    /* Keywords Tag Box (Naukri Styled) */
    .keywords-tag-container {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 8px 14px;
        min-height: 52px;
        border: 1.5px solid var(--resdex-border);
        border-radius: 12px;
        background: #ffffff;
        transition: all 0.2s ease;
        position: relative;
    }
    .keywords-tag-container:focus-within {
        border-color: var(--resdex-teal);
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
    }

    .keyword-tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #0284c7;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 0.85rem;
        font-weight: 700;
        animation: fadeIn 0.15s ease-in-out;
    }
    .keyword-tag-chip .star-icon {
        color: #f59e0b;
        font-size: 0.8rem;
    }
    .keyword-tag-chip .remove-btn {
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.95rem;
        line-height: 1;
        border-radius: 50%;
        margin-left: 2px;
    }
    .keyword-tag-chip .remove-btn:hover {
        color: #ef4444;
    }

    .location-tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #0284c7;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 0.85rem;
        font-weight: 700;
        animation: fadeIn 0.15s ease-in-out;
    }
    .location-tag-chip .star-icon {
        color: #f59e0b;
        font-size: 0.8rem;
    }
    .location-tag-chip .remove-btn {
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.95rem;
        line-height: 1;
        border-radius: 50%;
        margin-left: 2px;
    }
    .location-tag-chip .remove-btn:hover {
        color: #ef4444;
    }

    .keyword-text-input {
        flex: 1;
        min-width: 220px;
        border: none;
        outline: none;
        font-size: 0.92rem;
        color: #0f172a;
        background: transparent;
        padding: 6px 4px;
    }
    .keyword-text-input::placeholder {
        color: #94a3b8;
        font-size: 0.92rem;
    }

    /* Floating Autocomplete Dropdown List */
    .autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        z-index: 9999;
        margin-top: 6px;
        max-height: 280px;
        overflow-y: auto;
        display: none;
    }
    .autocomplete-header {
        padding: 8px 16px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }
    .autocomplete-item {
        padding: 10px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f8fafc;
        transition: background 0.1s ease;
    }
    .autocomplete-item:hover, .autocomplete-item.active {
        background: #f0faf7;
        color: var(--resdex-teal);
    }
    .autocomplete-item-type {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #64748b;
    }
    .autocomplete-item:hover .autocomplete-item-type {
        background: var(--resdex-teal-light);
        color: var(--resdex-teal);
    }

    /* Sub options row under keyword */
    .keyword-sub-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 10px;
        font-size: 0.85rem;
    }
    .custom-checkbox-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.86rem;
        color: #334155;
        cursor: pointer;
        font-weight: 500;
    }
    .custom-checkbox-label input[type="checkbox"] {
        accent-color: var(--resdex-teal);
        width: 16px;
        height: 16px;
    }

    .link-blue {
        color: var(--resdex-blue);
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .link-blue:hover {
        text-decoration: underline;
    }

    /* AI / Suggested keywords cloud */
    .ai-suggested-box {
        margin-top: 16px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 14px 18px;
    }
    .ai-suggested-header {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .ai-chips-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .ai-chip {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .ai-chip:hover {
        background: var(--resdex-teal-light);
        color: var(--resdex-teal);
        border-color: var(--resdex-teal-border);
        transform: translateY(-1px);
    }

    /* Boolean Textarea Mode */
    .boolean-textarea {
        width: 100%;
        min-height: 85px;
        padding: 12px 18px;
        font-size: 0.95rem;
        font-family: 'Consolas', 'Courier New', monospace;
        font-weight: 600;
        color: #0f172a;
        border: 1.5px solid var(--resdex-border);
        border-radius: 12px;
        outline: none;
        background: #f8fafc;
        resize: vertical;
        line-height: 1.5;
    }
    .boolean-textarea:focus {
        border-color: var(--resdex-teal);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
    }
    .btn-op {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-op:hover {
        background: var(--resdex-teal-light);
        color: var(--resdex-teal);
        border-color: var(--resdex-teal-border);
    }

    /* Range Multi-Inputs Row */
    .range-inputs-flex {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .range-field-input {
        flex: 1;
        min-width: 140px;
        padding: 11px 16px;
        font-size: 0.92rem;
        border: 1.5px solid var(--resdex-border);
        border-radius: 10px;
        outline: none;
        background: #ffffff;
        color: #0f172a;
    }
    .range-field-input:focus {
        border-color: var(--resdex-teal);
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
    }
    .range-divider-text {
        font-size: 0.92rem;
        font-weight: 600;
        color: #64748b;
    }
    .currency-select {
        padding: 11px 14px;
        font-size: 0.92rem;
        font-weight: 700;
        border: 1.5px solid var(--resdex-border);
        border-radius: 10px;
        background: #f8fafc;
        outline: none;
        color: #334155;
    }

    /* Checkbox Pill Groups */
    .pill-checkboxes-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 6px;
    }
    .pill-checkbox-item {
        position: relative;
    }
    .pill-checkbox-item input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .pill-checkbox-box {
        display: inline-block;
        padding: 7px 16px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #334155;
        background: #f8fafc;
        border: 1.5px solid var(--resdex-border);
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .pill-checkbox-item input:checked + .pill-checkbox-box {
        background: var(--resdex-teal-light);
        color: var(--resdex-teal);
        border-color: var(--resdex-teal);
        font-weight: 700;
    }

    /* Right Sidebar Widgets */
    .sidebar-widget-card {
        background: #ffffff;
        border: 1px solid var(--resdex-border-light);
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
    }
    .widget-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--resdex-text);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .search-history-item {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    .search-history-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    .history-query-text {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        display: block;
    }
    .history-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
    }

    /* Fixed Bottom Action Bar */
    .form-bottom-bar {
        position: sticky;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 2px solid var(--resdex-border-light);
        padding: 18px 36px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06);
        border-radius: 0 0 16px 16px;
        margin-top: 20px;
    }
    .btn-search-large {
        background: var(--resdex-teal);
        color: #ffffff;
        border: none;
        padding: 12px 34px;
        font-size: 1.05rem;
        font-weight: 800;
        border-radius: 12px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(0, 168, 132, 0.3);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-search-large:hover {
        background: var(--resdex-teal-dark);
        transform: translateY(-2px);
    }
    .btn-reset-simple {
        background: transparent;
        color: #64748b;
        border: none;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        padding: 10px 20px;
    }
    .btn-reset-simple:hover {
        color: #0f172a;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    @media (max-width: 990px) {
        .resdex-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Top Navigation Tabs Bar -->
<div class="ats-resdex-tabs">
    <a href="{{ route('admin.candidates.index') }}" class="resdex-tab-btn">
        <i class="fa-solid fa-table-list"></i>
        <span>Candidate Directory (ATS Grid)</span>
    </a>
    <a href="{{ route('admin.candidate_search.form') }}" class="resdex-tab-btn active">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>🔍 Search Candidates (Resdex Form)</span>
        <span class="badge-pill">NEW</span>
    </a>
    <a href="{{ route('admin.candidate_search.index') }}" class="resdex-tab-btn">
        <i class="fa-solid fa-address-card"></i>
        <span>⭐ Candidate Search Results (Cards)</span>
    </a>
</div>

<!-- Main Search Form -->
<form id="advanceSearchForm" action="{{ route('admin.candidate_search.index') }}" method="GET">
    <div class="resdex-form-grid">
        <!-- Left Column: Search Candidates Detailed Form (Exact Naukri Layout) -->
        <div class="resdex-form-card">
            <h1 class="form-page-title">Search candidates</h1>

            <!-- 1. Keywords Section -->
            <div class="form-group-block">
                <div class="form-label-row">
                    <label class="form-field-label">Keywords</label>
                    <div class="boolean-toggle-pill" onclick="toggleBooleanForm()">
                        <span id="booleanLabelText" style="color: #64748b;">Boolean off</span>
                        <label class="switch-pill">
                            <input type="checkbox" id="booleanModeCheckbox" name="boolean_mode" value="1" onchange="toggleBooleanForm()">
                            <span class="slider-pill"></span>
                        </label>
                    </div>
                </div>

                <!-- 1.A. Simple Keyword Tag Box (Default) -->
                <div id="simpleKeywordContainer" style="display: block;">
                    <div class="keywords-tag-container" id="keywordsTagBox" onclick="focusKeywordInput()">
                        <!-- Keyword Chips will be injected here dynamically -->
                        <div id="keywordChipsWrapper" style="display: contents;"></div>

                        <input type="text" 
                               id="keywordMainInput" 
                               class="keyword-text-input" 
                               placeholder="Enter keywords like skills, designation and company" 
                               autocomplete="off">

                        <!-- Hidden input to submit all keyword chips as comma-separated -->
                        <input type="hidden" name="q" id="hiddenKeywordsQuery" value="">

                        <!-- Live Floating Autocomplete Dropdown -->
                        <div id="autocompleteDropdown" class="autocomplete-dropdown">
                            <div class="autocomplete-header">Suggested Skills & Titles from Database</div>
                            <div id="autocompleteItemsList"></div>
                        </div>
                    </div>

                    <!-- Sub Options -->
                    <div class="keyword-sub-options">
                        <label class="custom-checkbox-label">
                            <input type="checkbox" name="mandatory_keywords" value="1">
                            <span>Mark all keywords as mandatory</span>
                        </label>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.84rem; color: #64748b;">Search keyword in</span>
                            <select name="keyword_scope" class="currency-select" style="padding: 5px 10px; font-size: 0.82rem;">
                                <option value="all">Entire resume / profile</option>
                                <option value="skills">Key skills only</option>
                                <option value="title">Designation / Title only</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 18px; margin-top: 10px;">
                        <a href="javascript:void(0)" onclick="toggleExcludeKeywords()" class="link-blue">
                            <i class="fa-solid fa-plus"></i> Add Exclude Keywords
                        </a>
                        <a href="javascript:void(0)" onclick="toggleItSkillsSection()" class="link-blue">
                            <i class="fa-solid fa-plus"></i> Add IT Skills
                        </a>
                    </div>

                    <!-- Exclude Keywords Drawer (Hidden by default unless query present) -->
                    <div id="excludeKeywordsDrawer" style="{{ request('exclude_keywords') ? 'display: block;' : 'display: none;' }} margin-top: 12px; background: #fff5f5; border: 1px dashed #fca5a5; padding: 12px 16px; border-radius: 10px; position: relative;">
                        <label style="font-size: 0.84rem; font-weight: 700; color: #b91c1c; display: block; margin-bottom: 6px;">
                            <i class="fa-solid fa-ban"></i> Exclude Keywords (NOT in candidate profile)
                        </label>
                        <div style="position: relative;">
                            <input type="text" 
                                   id="excludeKeywordsInput" 
                                   name="exclude_keywords" 
                                   value="{{ request('exclude_keywords') }}" 
                                   class="range-field-input" 
                                   placeholder="e.g. Angular, PHP, Fresher (type to search from database)..." 
                                   style="width: 100%;"
                                   autocomplete="off">

                            <!-- Live Floating Exclude Dropdown from DB -->
                            <div id="excludeAutocompleteDropdown" class="autocomplete-dropdown">
                                <div class="autocomplete-header" style="color: #b91c1c;">Skills & Titles to Exclude (from Database)</div>
                                <div id="excludeAutocompleteItemsList"></div>
                            </div>
                        </div>
                    </div>

                    <!-- AI Suggested Keywords Cloud (Matching Screenshot 4) -->
                    <div class="ai-suggested-box">
                        <div class="ai-suggested-header">
                            <span><i class="fa-solid fa-wand-magic-sparkles" style="color: var(--resdex-teal);"></i> AI suggested keywords</span>
                            <span style="font-size: 0.72rem; color: #94a3b8; font-weight: 500;">Press keyword to add to search</span>
                        </div>
                        <div class="ai-chips-cloud">
                            @foreach($suggestedSkills as $skill)
                                <span class="ai-chip" onclick="addKeywordChip('{{ $skill }}')">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 1.B. Boolean Expression Box (When Boolean is ON) -->
                <div id="booleanExpressionContainer" style="display: none;">
                    <textarea id="booleanQueryInput" 
                              name="boolean_query" 
                              class="boolean-textarea" 
                              placeholder="e.g. ((Java AND Microservices) OR AWS) NOT Angular" 
                              rows="3">{{ request('boolean_query') }}</textarea>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px;">
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn-op" onclick="insertOp('AND')">+ AND</button>
                            <button type="button" class="btn-op" onclick="insertOp('OR')">+ OR</button>
                            <button type="button" class="btn-op" onclick="insertOp('NOT')">+ NOT</button>
                            <button type="button" class="btn-op" onclick="insertOp('( )')">( )</button>
                            <button type="button" class="btn-op" onclick="insertOp('\" \"')">"Quote"</button>
                        </div>
                        <span style="font-size: 0.82rem; color: var(--resdex-teal); font-weight: 600;">
                            <i class="fa-solid fa-circle-info"></i> Supports nested parentheses & quoted phrases
                        </span>
                    </div>

                    <div id="booleanValidationMsg" style="display: none; margin-top: 8px; padding: 6px 12px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;"></div>
                </div>
            </div>

            <!-- 2. Experience Section -->
            <div class="form-group-block">
                <label class="form-field-label" style="display: block; margin-bottom: 8px;">Experience</label>
                <div class="range-inputs-flex">
                    <input type="number" step="0.5" min="0" max="40" name="min_experience" value="{{ request('min_experience') }}" class="range-field-input" placeholder="Min experience">
                    <span class="range-divider-text">to</span>
                    <input type="number" step="0.5" min="0" max="50" name="max_experience" value="{{ request('max_experience') }}" class="range-field-input" placeholder="Max experience">
                    <span class="range-divider-text" style="font-weight: 700; color: #334155;">Years</span>
                </div>
            </div>

            <!-- 3. Current Location Section -->
            <div class="form-group-block">
                <label class="form-field-label" style="display: block; margin-bottom: 8px;">
                    <i class="fa-solid fa-location-dot" style="color: var(--resdex-teal); margin-right: 4px;"></i> Current location of candidate
                </label>
                <div style="position: relative;">
                    <div class="keywords-tag-container" id="locationsTagBox" onclick="focusLocationInput()">
                        <!-- Location Chips will be injected here dynamically -->
                        <div id="locationChipsWrapper" style="display: contents;"></div>

                        <input type="text" 
                               id="locationMainInput" 
                               class="keyword-text-input" 
                               placeholder="Add location (e.g. Bengaluru, Mumbai, Delhi, Remote...)" 
                               autocomplete="off">

                        <!-- Hidden input to submit all location chips as comma-separated -->
                        <input type="hidden" name="location" id="hiddenLocationsQuery" value="{{ is_array(request('location')) ? implode(', ', request('location')) : request('location') }}">

                        <!-- Live Floating Location Dropdown from DB -->
                        <div id="locationAutocompleteDropdown" class="autocomplete-dropdown" style="top: 100%; left: 0; right: 0;">
                            <div class="autocomplete-header">Candidate Locations from Database</div>
                            <div id="locationAutocompleteItemsList"></div>
                        </div>
                    </div>
                </div>

                @if(!empty($popularLocations))
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;"><i class="fa-solid fa-fire" style="color: #f59e0b;"></i> Popular:</span>
                        @foreach(array_slice($popularLocations, 0, 8) as $popLoc)
                            <span class="ai-chip" style="font-size: 0.76rem; padding: 3px 8px; cursor: pointer;" onclick="addLocationChip('{{ addslashes($popLoc) }}')">+ {{ $popLoc }}</span>
                        @endforeach
                    </div>
                @endif

                <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 6px;">
                    <label class="custom-checkbox-label">
                        <input type="checkbox" name="include_relocation" value="1" {{ request()->has('include_relocation') ? (request('include_relocation') ? 'checked' : '') : 'checked' }}>
                        <span>Include candidates who prefer to relocate to above locations</span>
                    </label>
                </div>
            </div>

            <!-- 4. Annual Salary Section -->
            <div class="form-group-block">
                <label class="form-field-label" style="display: block; margin-bottom: 8px;">Annual Salary</label>
                <div class="range-inputs-flex">
                    <select class="currency-select" name="currency">
                        <option value="INR" {{ request('currency', 'INR') === 'INR' ? 'selected' : '' }}>INR</option>
                        <option value="USD" {{ request('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                    </select>
                    <input type="number" step="any" name="min_salary" value="{{ request('min_salary', (request('min_expected_ctc') ? request('min_expected_ctc') / 100000 : '')) }}" class="range-field-input" placeholder="e.g. 1 Lacs or 100000">
                    <span class="range-divider-text">to</span>
                    <input type="number" step="any" name="max_salary" value="{{ request('max_salary', (request('max_expected_ctc') ? request('max_expected_ctc') / 100000 : '')) }}" class="range-field-input" placeholder="e.g. 5 Lacs or 500000">
                    <span class="range-divider-text" style="font-size: 0.82rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">Lacs or ₹</span>
                </div>
            </div>

            <!-- 5. Work Mode / Employment Type -->
            <div class="form-group-block">
                <label class="form-field-label" style="display: block; margin-bottom: 8px;">Work Mode / Employment Type</label>
                <div class="pill-checkboxes-grid">
                    @foreach(['Remote', 'Hybrid', 'Full Time', 'Contract', 'Part Time'] as $wm)
                        <label class="pill-checkbox-item">
                            <input type="checkbox" name="job_type[]" value="{{ $wm }}" {{ in_array($wm, (array) request('job_type', [])) ? 'checked' : '' }}>
                            <span class="pill-checkbox-box">{{ $wm }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 6. Notice Period -->
            <div class="form-group-block">
                <label class="form-field-label" style="display: block; margin-bottom: 8px;">Notice Period</label>
                <div class="pill-checkboxes-grid">
                    @foreach(['Immediate', '15 Days', '30 Days', '60 Days', '90 Days'] as $np)
                        <label class="pill-checkbox-item">
                            <input type="checkbox" name="notice_period[]" value="{{ $np }}" {{ in_array($np, (array) request('notice_period', [])) ? 'checked' : '' }}>
                            <span class="pill-checkbox-box">{{ $np }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Sticky Bottom Action Controls -->
            <div class="form-bottom-bar">
                <button type="button" onclick="resetSearchForm()" class="btn-reset-simple">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>

                <button type="submit" class="btn-search-large">
                    <i class="fa-solid fa-magnifying-glass"></i> Search candidates
                </button>
            </div>
        </div>

        <!-- Right Column: Sidebar (Recent Searches & Saved Searches) -->
        <aside>
            <!-- Recent Searches Widget (Matching Screenshot 3) -->
            <div class="sidebar-widget-card">
                <h3 class="widget-title">
                    <i class="fa-solid fa-clock-rotate-left" style="color: var(--resdex-teal);"></i> Recent Searches
                </h3>

                @if(!empty($recentSearches))
                    @foreach($recentSearches as $rec)
                        <div class="search-history-item">
                            <span class="history-query-text">{{ $rec['query'] }}</span>
                            <div class="history-actions-row">
                                <a href="javascript:void(0)" onclick="fillRecentSearch('{{ addslashes($rec['query']) }}')" class="link-blue">Fill this search</a>
                                <a href="{{ route('admin.candidate_search.index', array_merge(['boolean_mode' => $rec['boolean_mode'] ? 1 : 0, 'boolean_query' => $rec['query'], 'q' => $rec['query']], $rec['filters'] ?? [])) }}" style="color: var(--resdex-teal); text-decoration: none; font-weight: 700;">
                                    Search profiles &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="font-size: 0.84rem; color: #94a3b8; padding: 12px 0;">No recent searches recorded yet.</div>
                @endif
            </div>

            <!-- Quick Tips Widget -->
            <div class="sidebar-widget-card" style="background: #f0faf7; border-color: var(--resdex-teal-border);">
                <div style="font-size: 0.9rem; font-weight: 700; color: #065f46; margin-bottom: 6px;">
                    <i class="fa-solid fa-lightbulb"></i> Naukri Resdex Pro Tip
                </div>
                <p style="font-size: 0.82rem; color: #047857; margin: 0; line-height: 1.5;">
                    Type any tech skill (e.g. <code>java</code>, <code>python</code>, <code>react</code>) and our system will suggest real skills and job titles from candidates in the database.
                </p>
            </div>
        </aside>
    </div>
</form>
@endsection

@section('scripts')
<script>
    // ==========================================
    // KEYWORD CHIPS & AUTOCOMPLETE ENGINE
    // ==========================================
    let keywordChips = [];
    const keywordMainInput = document.getElementById('keywordMainInput');
    const keywordChipsWrapper = document.getElementById('keywordChipsWrapper');
    const hiddenKeywordsQuery = document.getElementById('hiddenKeywordsQuery');
    const autocompleteDropdown = document.getElementById('autocompleteDropdown');
    const autocompleteItemsList = document.getElementById('autocompleteItemsList');

    let autocompleteTimer = null;
    let selectedDropdownIndex = -1;
    let currentSuggestions = [];

    // Focus input when clicking tag box
    function focusKeywordInput() {
        keywordMainInput.focus();
    }

    // Add a chip
    function addKeywordChip(keyword) {
        keyword = keyword.trim();
        if (!keyword) return;

        // Prevent duplicate
        if (!keywordChips.some(k => k.toLowerCase() === keyword.toLowerCase())) {
            keywordChips.push(keyword);
            renderKeywordChips();
        }

        keywordMainInput.value = '';
        keywordMainInput.placeholder = 'Type another keyword';
        hideAutocomplete();
        keywordMainInput.focus();
    }

    // Remove a chip
    function removeKeywordChip(index) {
        keywordChips.splice(index, 1);
        renderKeywordChips();
        if (keywordChips.length === 0) {
            keywordMainInput.placeholder = 'Enter keywords like skills, designation and company';
        }
    }

    // Render chips in DOM
    function renderKeywordChips() {
        keywordChipsWrapper.innerHTML = '';
        keywordChips.forEach((kw, index) => {
            const chip = document.createElement('span');
            chip.className = 'keyword-tag-chip';
            chip.innerHTML = `<i class="fa-solid fa-star star-icon"></i> <span>${escapeHtml(kw)}</span> <span class="remove-btn" onclick="removeKeywordChip(${index}); event.stopPropagation();">&times;</span>`;
            keywordChipsWrapper.appendChild(chip);
        });

        // Update hidden input
        hiddenKeywordsQuery.value = keywordChips.join(', ');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    // Input Typing Handler with Live Autocomplete
    keywordMainInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(autocompleteTimer);

        if (query.length < 1) {
            hideAutocomplete();
            return;
        }

        autocompleteTimer = setTimeout(() => {
            fetchSuggestions(query);
        }, 180);
    });

    // Keyboard Navigation: Enter, Comma, Backspace, Arrow keys
    keywordMainInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            if (selectedDropdownIndex >= 0 && currentSuggestions[selectedDropdownIndex]) {
                addKeywordChip(currentSuggestions[selectedDropdownIndex].text);
            } else if (this.value.trim()) {
                addKeywordChip(this.value.trim());
            }
        } else if (e.key === 'Backspace' && this.value === '' && keywordChips.length > 0) {
            removeKeywordChip(keywordChips.length - 1);
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            navigateDropdown(1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            navigateDropdown(-1);
        } else if (e.key === 'Escape') {
            hideAutocomplete();
        }
    });

    // Fetch Suggestions from Database API
    function fetchSuggestions(query) {
        fetch(`{{ route('admin.candidate_search.suggestions') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                currentSuggestions = data;
                selectedDropdownIndex = -1;

                if (!data || data.length === 0) {
                    hideAutocomplete();
                    return;
                }

                autocompleteItemsList.innerHTML = '';
                data.forEach((item, index) => {
                    const el = document.createElement('div');
                    el.className = 'autocomplete-item';
                    el.innerHTML = `<span><i class="fa-solid fa-search" style="color: #94a3b8; font-size: 0.8rem; margin-right: 8px;"></i> ${escapeHtml(item.text)}</span> <span class="autocomplete-item-type">${escapeHtml(item.type)}</span>`;
                    el.addEventListener('click', () => {
                        addKeywordChip(item.text);
                    });
                    autocompleteItemsList.appendChild(el);
                });

                autocompleteDropdown.style.display = 'block';
            })
            .catch(err => {
                console.error('Error fetching suggestions:', err);
                hideAutocomplete();
            });
    }

    function navigateDropdown(direction) {
        const items = autocompleteItemsList.querySelectorAll('.autocomplete-item');
        if (items.length === 0) return;

        selectedDropdownIndex += direction;
        if (selectedDropdownIndex < 0) selectedDropdownIndex = items.length - 1;
        if (selectedDropdownIndex >= items.length) selectedDropdownIndex = 0;

        items.forEach((item, idx) => {
            if (idx === selectedDropdownIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    function hideAutocomplete() {
        autocompleteDropdown.style.display = 'none';
        selectedDropdownIndex = -1;
    }

    // ==========================================
    // 2. MULTI-LOCATION STAR CHIPS & AUTOCOMPLETE
    // ==========================================
    let locationChips = [];
    const locationMainInput = document.getElementById('locationMainInput');
    const locationChipsWrapper = document.getElementById('locationChipsWrapper');
    const hiddenLocationsQuery = document.getElementById('hiddenLocationsQuery');
    const locationDropdown = document.getElementById('locationAutocompleteDropdown');
    const locationItemsList = document.getElementById('locationAutocompleteItemsList');
    let locationTimer = null;
    let selectedLocationIndex = -1;
    let currentLocationSuggestions = [];

    function focusLocationInput() {
        if (locationMainInput) locationMainInput.focus();
    }

    function addLocationChip(loc) {
        if (!loc) return;
        loc = loc.trim();
        if (!loc) return;

        // Prevent duplicate case-insensitive
        if (!locationChips.some(l => l.toLowerCase() === loc.toLowerCase())) {
            locationChips.push(loc);
            renderLocationChips();
        }

        if (locationMainInput) {
            locationMainInput.value = '';
            locationMainInput.placeholder = 'Type another location...';
            locationMainInput.focus();
        }
        hideLocationAutocomplete();
    }

    function removeLocationChip(index) {
        locationChips.splice(index, 1);
        renderLocationChips();
        if (locationChips.length === 0 && locationMainInput) {
            locationMainInput.placeholder = 'Add location (e.g. Bengaluru, Mumbai, Delhi, Remote...)';
        }
    }

    function renderLocationChips() {
        if (!locationChipsWrapper || !hiddenLocationsQuery) return;
        locationChipsWrapper.innerHTML = '';
        locationChips.forEach((loc, index) => {
            const chip = document.createElement('span');
            chip.className = 'location-tag-chip';
            chip.innerHTML = `<i class="fa-solid fa-star star-icon"></i> <span>${escapeHtml(loc)}</span> <span class="remove-btn" onclick="removeLocationChip(${index}); event.stopPropagation();">&times;</span>`;
            locationChipsWrapper.appendChild(chip);
        });

        hiddenLocationsQuery.value = locationChips.join(', ');
    }

    if (locationMainInput) {
        locationMainInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(locationTimer);

            if (query.length < 1) {
                hideLocationAutocomplete();
                return;
            }

            locationTimer = setTimeout(() => {
                fetchLocationSuggestions(query);
            }, 180);
        });

        locationMainInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                if (selectedLocationIndex >= 0 && currentLocationSuggestions[selectedLocationIndex]) {
                    addLocationChip(currentLocationSuggestions[selectedLocationIndex].text);
                } else if (this.value.trim()) {
                    addLocationChip(this.value.trim());
                }
            } else if (e.key === 'Backspace' && this.value === '' && locationChips.length > 0) {
                removeLocationChip(locationChips.length - 1);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateLocationDropdown(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateLocationDropdown(-1);
            } else if (e.key === 'Escape') {
                hideLocationAutocomplete();
            }
        });
    }

    function fetchLocationSuggestions(query) {
        fetch(`{{ route('admin.candidate_search.locations') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                currentLocationSuggestions = data;
                selectedLocationIndex = -1;

                if (!data || data.length === 0) {
                    hideLocationAutocomplete();
                    return;
                }

                locationItemsList.innerHTML = '';
                data.forEach((item, index) => {
                    const el = document.createElement('div');
                    el.className = 'autocomplete-item';
                    el.innerHTML = `<span><i class="fa-solid fa-location-dot" style="color: var(--resdex-teal); font-size: 0.85rem; margin-right: 8px;"></i> ${escapeHtml(item.text)}</span> <span class="autocomplete-item-type">${escapeHtml(item.type)}</span>`;
                    el.addEventListener('click', () => {
                        addLocationChip(item.text);
                    });
                    locationItemsList.appendChild(el);
                });

                locationDropdown.style.display = 'block';
            })
            .catch(err => {
                console.error('Error fetching location suggestions:', err);
                hideLocationAutocomplete();
            });
    }

    function navigateLocationDropdown(direction) {
        const items = locationItemsList.querySelectorAll('.autocomplete-item');
        if (items.length === 0) return;

        selectedLocationIndex += direction;
        if (selectedLocationIndex < 0) selectedLocationIndex = items.length - 1;
        if (selectedLocationIndex >= items.length) selectedLocationIndex = 0;

        items.forEach((item, idx) => {
            if (idx === selectedLocationIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    function hideLocationAutocomplete() {
        if (locationDropdown) {
            locationDropdown.style.display = 'none';
        }
        selectedLocationIndex = -1;
    }

    // ==========================================
    // 3. EXCLUDE KEYWORDS AUTOCOMPLETE FROM DATABASE
    // ==========================================
    const excludeInput = document.getElementById('excludeKeywordsInput');
    const excludeDropdown = document.getElementById('excludeAutocompleteDropdown');
    const excludeItemsList = document.getElementById('excludeAutocompleteItemsList');
    let excludeTimer = null;
    let selectedExcludeIndex = -1;
    let currentExcludeSuggestions = [];

    if (excludeInput) {
        excludeInput.addEventListener('input', function() {
            const fullVal = this.value;
            const parts = fullVal.split(',');
            const currentToken = parts[parts.length - 1].trim();

            clearTimeout(excludeTimer);

            if (currentToken.length < 1) {
                hideExcludeAutocomplete();
                return;
            }

            excludeTimer = setTimeout(() => {
                fetchExcludeSuggestions(currentToken);
            }, 180);
        });

        excludeInput.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateExcludeDropdown(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateExcludeDropdown(-1);
            } else if (e.key === 'Enter') {
                if (selectedExcludeIndex >= 0 && currentExcludeSuggestions[selectedExcludeIndex]) {
                    e.preventDefault();
                    selectExcludeKeyword(currentExcludeSuggestions[selectedExcludeIndex].text);
                }
            } else if (e.key === 'Escape') {
                hideExcludeAutocomplete();
            }
        });
    }

    function fetchExcludeSuggestions(query) {
        fetch(`{{ route('admin.candidate_search.suggestions') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                currentExcludeSuggestions = data;
                selectedExcludeIndex = -1;

                if (!data || data.length === 0) {
                    hideExcludeAutocomplete();
                    return;
                }

                excludeItemsList.innerHTML = '';
                data.forEach((item, index) => {
                    const el = document.createElement('div');
                    el.className = 'autocomplete-item';
                    el.innerHTML = `<span><i class="fa-solid fa-ban" style="color: #ef4444; font-size: 0.85rem; margin-right: 8px;"></i> ${escapeHtml(item.text)}</span> <span class="autocomplete-item-type">${escapeHtml(item.type)}</span>`;
                    el.addEventListener('click', () => {
                        selectExcludeKeyword(item.text);
                    });
                    excludeItemsList.appendChild(el);
                });

                excludeDropdown.style.display = 'block';
            })
            .catch(err => {
                console.error('Error fetching exclude suggestions:', err);
                hideExcludeAutocomplete();
            });
    }

    function selectExcludeKeyword(keyword) {
        const fullVal = excludeInput.value;
        const parts = fullVal.split(',').map(s => s.trim()).filter(Boolean);
        if (parts.length > 0) {
            parts[parts.length - 1] = keyword;
        } else {
            parts.push(keyword);
        }
        excludeInput.value = parts.join(', ') + ', ';
        hideExcludeAutocomplete();
        excludeInput.focus();
    }

    function navigateExcludeDropdown(direction) {
        const items = excludeItemsList.querySelectorAll('.autocomplete-item');
        if (items.length === 0) return;

        selectedExcludeIndex += direction;
        if (selectedExcludeIndex < 0) selectedExcludeIndex = items.length - 1;
        if (selectedExcludeIndex >= items.length) selectedExcludeIndex = 0;

        items.forEach((item, idx) => {
            if (idx === selectedExcludeIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    function hideExcludeAutocomplete() {
        if (excludeDropdown) {
            excludeDropdown.style.display = 'none';
        }
        selectedExcludeIndex = -1;
    }

    // Close autocompletes on click outside
    document.addEventListener('click', function(e) {
        if (!document.getElementById('keywordsTagBox').contains(e.target)) {
            hideAutocomplete();
        }
        if (document.getElementById('locationsTagBox') && !document.getElementById('locationsTagBox').contains(e.target)) {
            hideLocationAutocomplete();
        }
        if (excludeInput && !excludeInput.parentElement.contains(e.target)) {
            hideExcludeAutocomplete();
        }
    });

    // ==========================================
    // BOOLEAN MODE TOGGLE
    // ==========================================
    function toggleBooleanForm() {
        const checkbox = document.getElementById('booleanModeCheckbox');
        const isChecked = checkbox.checked;
        const label = document.getElementById('booleanLabelText');

        if (isChecked) {
            label.innerText = 'Boolean on';
            label.style.color = '#00a884';
            document.getElementById('simpleKeywordContainer').style.display = 'none';
            document.getElementById('booleanExpressionContainer').style.display = 'block';
            document.getElementById('booleanQueryInput').focus();
        } else {
            label.innerText = 'Boolean off';
            label.style.color = '#64748b';
            document.getElementById('simpleKeywordContainer').style.display = 'block';
            document.getElementById('booleanExpressionContainer').style.display = 'none';
            keywordMainInput.focus();
        }
    }

    function insertOp(op) {
        const textarea = document.getElementById('booleanQueryInput');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        let insertion = op;
        let cursorOffset = op.length;

        if (op === '( )') {
            insertion = '( ';
            cursorOffset = 2;
        } else if (op === '\" \"') {
            insertion = '"';
            cursorOffset = 1;
        } else {
            insertion = ' ' + op + ' ';
            cursorOffset = insertion.length;
        }

        textarea.value = text.substring(0, start) + insertion + text.substring(end);
        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = start + cursorOffset;
    }

    function toggleExcludeKeywords() {
        const drawer = document.getElementById('excludeKeywordsDrawer');
        drawer.style.display = drawer.style.display === 'none' ? 'block' : 'none';
        if (drawer.style.display === 'block') {
            drawer.querySelector('input').focus();
        }
    }

    function toggleItSkillsSection() {
        const box = document.querySelector('.ai-suggested-box');
        box.scrollIntoView({ behavior: 'smooth' });
    }

    function fillRecentSearch(query) {
        // If contains boolean operators, switch to boolean mode
        if (/\b(AND|OR|NOT)\b/i.test(query)) {
            document.getElementById('booleanModeCheckbox').checked = true;
            toggleBooleanForm();
            document.getElementById('booleanQueryInput').value = query;
        } else {
            // Split comma keywords and add as chips
            const tokens = query.split(',').map(s => s.trim()).filter(Boolean);
            keywordChips = [];
            tokens.forEach(t => addKeywordChip(t));
        }
    }

    function resetSearchForm() {
        document.getElementById('advanceSearchForm').reset();
        keywordChips = [];
        renderKeywordChips();
        if (keywordMainInput) {
            keywordMainInput.placeholder = 'Enter keywords like skills, designation and company';
        }
        locationChips = [];
        renderLocationChips();
        if (locationMainInput) {
            locationMainInput.placeholder = 'Add location (e.g. Bengaluru, Mumbai, Delhi, Remote...)';
        }
        document.getElementById('booleanModeCheckbox').checked = false;
        toggleBooleanForm();
    }

    // Safety on form submit: flush any un-entered text into chips
    document.getElementById('advanceSearchForm').addEventListener('submit', function() {
        if (keywordMainInput && keywordMainInput.value.trim()) {
            addKeywordChip(keywordMainInput.value.trim());
        }
        if (locationMainInput && locationMainInput.value.trim()) {
            addLocationChip(locationMainInput.value.trim());
        }
    });

    // Auto-initialize query parameters if loaded with existing search (e.g. from Modify Search)
    document.addEventListener('DOMContentLoaded', function() {
        @if(request('boolean_mode'))
            const bmCheckbox = document.getElementById('booleanModeCheckbox');
            if (bmCheckbox) {
                bmCheckbox.checked = true;
                toggleBooleanForm();
            }
        @elseif(request('q'))
            const rawTokens = {!! json_encode(array_values(array_filter(array_map('trim', explode(',', request('q')))))) !!};
            if (Array.isArray(rawTokens)) {
                rawTokens.forEach(tok => addKeywordChip(tok));
            }
        @endif

        @if(request('location'))
            @php
                $rawInitLoc = request('location');
                $initLocArray = is_array($rawInitLoc) ? $rawInitLoc : preg_split('/[,;|]+/', (string) $rawInitLoc);
                $initLocArray = array_values(array_filter(array_map('trim', $initLocArray)));
            @endphp
            const rawLocTokens = {!! json_encode($initLocArray) !!};
            if (Array.isArray(rawLocTokens)) {
                rawLocTokens.forEach(tok => addLocationChip(tok));
            }
        @endif
    });
</script>
@endsection
