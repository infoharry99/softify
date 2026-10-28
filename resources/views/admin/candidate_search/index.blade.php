@extends('layouts.employee')

@section('title', 'Candidate Directory (Resdex Search Pro)')
@section('page_title', 'HR Candidate Management (ATS)')

@section('styles')
<style>
    /* ===================================================
       NAUKRI RESDEX STYLED CANDIDATE SEARCH WORKSPACE
       =================================================== */
    :root {
        --resdex-teal: #00a884;
        --resdex-teal-dark: #008f70;
        --resdex-teal-light: #e6f7f3;
        --resdex-teal-border: #9ee5d4;
        --resdex-navy: #0f172a;
        --resdex-border: #e2e8f0;
        --resdex-bg-light: #f8fafc;
        --resdex-text-muted: #64748b;
    }

    /* Top Navigation Tabs (ATS vs Resdex Search) */
    .ats-resdex-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 2px;
    }
    .resdex-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
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

    /* Main Resdex Card */
    .resdex-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--resdex-border);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    /* Search Header Banner */
    .resdex-search-header {
        padding: 24px 28px;
        background: #ffffff;
        border-bottom: 1px solid var(--resdex-border);
    }
    .resdex-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
    }
    .resdex-title-block h2 {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .resdex-title-block p {
        font-size: 0.88rem;
        color: var(--resdex-text-muted);
        margin: 4px 0 0 0;
    }

    /* Boolean Toggle Pill */
    .boolean-toggle-wrap {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: #f1f5f9;
        padding: 6px 14px;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
    }
    .boolean-toggle-label {
        font-size: 0.86rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .switch-pill {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
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
        border-radius: 24px;
    }
    .slider-pill:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .25s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .switch-pill input:checked + .slider-pill {
        background-color: var(--resdex-teal);
    }
    .switch-pill input:checked + .slider-pill:before {
        transform: translateX(24px);
    }

    /* Boolean Input Area */
    .boolean-input-box {
        position: relative;
        margin-bottom: 12px;
    }
    .boolean-textarea {
        width: 100%;
        min-height: 76px;
        padding: 12px 18px;
        font-size: 0.95rem;
        font-family: 'Consolas', 'Courier New', monospace;
        font-weight: 600;
        color: #0f172a;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        outline: none;
        background: #f8fafc;
        transition: all 0.2s ease;
        line-height: 1.5;
        resize: vertical;
    }
    .boolean-textarea:focus {
        border-color: var(--resdex-teal);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
    }

    /* Operator Quick Buttons */
    .operator-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 6px;
    }
    .op-buttons-group {
        display: flex;
        align-items: center;
        gap: 6px;
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
    .syntax-help-link {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--resdex-teal);
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .syntax-help-link:hover {
        text-decoration: underline;
    }

    /* Live Validation Feedback Pill */
    .validation-feedback {
        margin-top: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
    }
    .validation-feedback.valid {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .validation-feedback.invalid {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* Simple Keyword Input Area */
    .simple-keyword-box {
        position: relative;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .simple-keyword-input {
        flex: 1;
        padding: 12px 18px 12px 42px;
        font-size: 0.94rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        outline: none;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    .simple-keyword-input:focus {
        border-color: var(--resdex-teal);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
    }
    .simple-keyword-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--resdex-teal);
        font-size: 1rem;
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
        text-align: left;
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

    /* Suggested Skills Chips */
    .suggested-skills-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
    }
    .suggested-label {
        font-size: 0.76rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-right: 4px;
    }
    .suggested-chip {
        font-size: 0.76rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #334155;
        padding: 3px 10px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .suggested-chip:hover {
        background: var(--resdex-teal-light);
        color: var(--resdex-teal);
        border-color: var(--resdex-teal-border);
    }

    /* Search Action Buttons Row */
    .search-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px dashed var(--resdex-border);
    }
    .btn-search-primary {
        background: var(--resdex-teal);
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 11px 26px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.25);
        transition: all 0.2s ease;
    }
    .btn-search-primary:hover {
        background: var(--resdex-teal-dark);
        transform: translateY(-1px);
    }
    .btn-search-reset {
        background: #ffffff;
        color: #64748b;
        border: 1.5px solid #cbd5e1;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-search-reset:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .btn-save-search {
        background: #ffffff;
        color: var(--resdex-teal);
        border: 1.5px solid var(--resdex-teal-border);
        font-size: 0.88rem;
        font-weight: 700;
        padding: 9px 16px;
        border-radius: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-save-search:hover {
        background: var(--resdex-teal-light);
    }

    /* Active Filter Tags Bar */
    .active-filters-bar {
        background: #f8fafc;
        padding: 12px 24px;
        border-bottom: 1px solid var(--resdex-border);
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .active-filters-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }
    .filter-tag-chip {
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .filter-tag-chip .remove-chip {
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .filter-tag-chip .remove-chip:hover {
        color: #ef4444;
    }
    .clear-all-link {
        font-size: 0.8rem;
        font-weight: 700;
        color: #ef4444;
        text-decoration: none;
        margin-left: 8px;
        cursor: pointer;
    }

    /* 2-Column Responsive Workspace Grid */
    .resdex-workspace-grid {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 24px;
        padding: 24px;
    }

    /* Filter Sidebar */
    .resdex-sidebar {
        background: #ffffff;
        border: 1px solid var(--resdex-border);
        border-radius: 14px;
        padding: 20px;
        height: fit-content;
    }
    .sidebar-section {
        margin-bottom: 22px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--resdex-border);
    }
    .sidebar-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .sidebar-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .filter-checkbox-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .filter-checkbox-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.86rem;
        color: #334155;
        cursor: pointer;
    }
    .filter-checkbox-item input[type="checkbox"] {
        margin-right: 8px;
        accent-color: var(--resdex-teal);
    }
    .filter-count-badge {
        font-size: 0.72rem;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 6px;
    }
    .range-inputs-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 8px;
    }
    .range-input {
        width: 100%;
        padding: 7px 10px;
        font-size: 0.84rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
    }
    .range-input:focus {
        border-color: var(--resdex-teal);
    }

    /* Main Results Area */
    .results-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 18px;
        padding: 12px 18px;
        background: #ffffff;
        border: 1px solid var(--resdex-border);
        border-radius: 12px;
    }
    .results-count-text {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
    }
    .results-sort-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sort-select {
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
        background: #ffffff;
    }

    /* Candidate Card (Naukri Resdex Styling) */
    .candidate-card {
        background: #ffffff;
        border: 1px solid var(--resdex-border);
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
        position: relative;
    }
    .candidate-card:hover {
        border-color: var(--resdex-teal-border);
        box-shadow: 0 8px 24px rgba(0, 168, 132, 0.08);
        transform: translateY(-2px);
    }
    .candidate-card.is-shortlisted {
        border-left: 4px solid #f59e0b;
        background: #fffdfa;
    }

    .card-top-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 12px;
    }
    .candidate-main-info {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }
    .candidate-avatar {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #e6f7f3;
        color: var(--resdex-teal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 800;
        flex-shrink: 0;
        border: 1.5px solid var(--resdex-teal-border);
    }
    .candidate-name-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .candidate-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .candidate-name:hover {
        color: var(--resdex-teal);
    }
    .candidate-designation {
        font-size: 0.92rem;
        color: #475569;
        margin-top: 3px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .bullet-divider {
        color: #cbd5e1;
        font-size: 0.8rem;
    }

    /* Badges */
    .badge-stage {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .badge-stage-applied { background: #e0f2fe; color: #0284c7; }
    .badge-stage-screening { background: #fef3c7; color: #d97706; }
    .badge-stage-interview-scheduled { background: #f3e8ff; color: #7c3aed; }
    .badge-stage-offered { background: #dcfce7; color: #15803d; }
    .badge-stage-hired { background: #bbf7d0; color: #166534; }
    .badge-stage-rejected { background: #fee2e2; color: #b91c1c; }

    .badge-workmode {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
    }

    /* Star Shortlist Button */
    .btn-star {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-star:hover {
        border-color: #f59e0b;
        color: #d97706;
        background: #fffbeb;
    }
    .btn-star.active {
        background: #fef3c7;
        color: #b45309;
        border-color: #f59e0b;
    }
    .btn-star.active i {
        color: #f59e0b;
    }

    /* Candidate Metadata Grid */
    .candidate-meta-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin: 12px 0 14px 0;
        padding: 10px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
    }
    .meta-item {
        font-size: 0.84rem;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .meta-item i {
        color: var(--resdex-teal);
        font-size: 0.85rem;
    }

    /* Skills Chips with Matched Search Highlight */
    .candidate-skills-wrap {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .skills-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        margin-top: 4px;
    }
    .skills-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        flex: 1;
    }
    .skill-chip {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .skill-chip.more-chip {
        background: #ffffff;
        color: var(--resdex-teal);
        border: 1px dashed var(--resdex-teal);
        cursor: pointer;
    }

    /* Keyword Highlighting */
    mark.resdex-highlight {
        background-color: #fef08a !important;
        color: #854d0e !important;
        font-weight: 800 !important;
        padding: 1px 4px !important;
        border-radius: 4px !important;
    }

    /* Summary Snippet */
    .candidate-summary-snippet {
        font-size: 0.85rem;
        color: #475569;
        background: #ffffff;
        border-left: 3px solid var(--resdex-teal);
        padding: 8px 14px;
        border-radius: 0 8px 8px 0;
        margin-bottom: 14px;
        line-height: 1.5;
        background: #fbfdfe;
    }
    .quote-icon {
        color: var(--resdex-teal);
        font-size: 0.75rem;
        margin-right: 6px;
    }

    /* Card Footer Actions */
    .card-footer-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 14px;
        border-top: 1px solid var(--resdex-border);
    }
    .left-actions {
        font-size: 0.78rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .right-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-action {
        font-size: 0.82rem;
        font-weight: 700;
        padding: 7px 14px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-action-primary {
        background: var(--resdex-teal);
        color: #ffffff;
        border: none;
    }
    .btn-action-primary:hover {
        background: var(--resdex-teal-dark);
    }
    .btn-action-secondary {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
    .btn-action-secondary:hover {
        background: #e2e8f0;
    }
    .btn-action-resume {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .btn-action-resume:hover {
        background: #fee2e2;
    }
    .btn-action-edited {
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }
    .btn-action-edited:hover {
        background: #e0f2fe;
    }

    /* Empty state */
    .no-results-card {
        background: #ffffff;
        border: 1.5px dashed var(--resdex-border);
        border-radius: 14px;
        padding: 50px 20px;
        text-align: center;
    }
    .empty-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 14px;
    }
    .btn-reset-large {
        background: var(--resdex-teal);
        color: #ffffff;
        padding: 10px 20px;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
    }

    /* Modal Overlay */
    .resdex-modal-overlay {
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
    .resdex-modal-card {
        background: #ffffff;
        width: 100%;
        max-width: 850px;
        max-height: 90vh;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .resdex-modal-header {
        padding: 16px 24px;
        background: #0f172a;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Responsive */
    @media (max-width: 980px) {
        .resdex-workspace-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Navigation Tabs Bar: Classic ATS vs Resdex Search Pro -->
<div class="ats-resdex-tabs">
    <a href="{{ route('admin.candidates.index') }}" class="resdex-tab-btn">
        <i class="fa-solid fa-table-list"></i>
        <span>Candidate Directory (ATS Grid)</span>
    </a>
    <a href="{{ route('admin.candidate_search.form', request()->all()) }}" class="resdex-tab-btn">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>🔍 Search Candidates (Resdex Form)</span>
        <span class="badge-pill" style="background: #0284c7;">FORM</span>
    </a>
    <a href="{{ route('admin.candidate_search.index', request()->all()) }}" class="resdex-tab-btn active">
        <i class="fa-solid fa-address-card"></i>
        <span>⭐ Candidate Search Results (Cards)</span>
        <span class="badge-pill">PRO</span>
    </a>
    <a href="{{ config('app.public_apply_url', 'https://apply.talentifyy.com') }}" target="_blank" class="resdex-tab-btn" style="margin-left: auto; color: #0284c7;">
        <i class="fa-solid fa-globe"></i>
        <span>🔗 Public Application Form</span>
        <span class="badge-pill" style="background: #0284c7;">SHARE</span>
    </a>
</div>

<!-- Main Search Form -->
<form id="resdexSearchForm" action="{{ route('admin.candidate_search.index') }}" method="GET">
    <div class="resdex-card">
        <!-- Search Header -->
        <div class="resdex-search-header">
            <div class="resdex-header-row">
                <div class="resdex-title-block">
                    <h2><i class="fa-solid fa-magnifying-glass-chart" style="color: var(--resdex-teal);"></i> Candidate Search (Resdex)</h2>
                    <p>Search and discover the right candidates with precision Boolean expressions and multi-dimensional filters</p>
                </div>

                <!-- Boolean Toggle Switch -->
                <div class="boolean-toggle-wrap">
                    <span class="boolean-toggle-label">
                        <i class="fa-solid fa-brain" style="color: var(--resdex-teal);"></i> Boolean Search
                    </span>
                    <label class="switch-pill">
                        <input type="checkbox" id="booleanModeToggle" name="boolean_mode" value="1" {{ $booleanMode ? 'checked' : '' }} onchange="toggleBooleanMode(this.checked)">
                        <span class="slider-pill"></span>
                    </label>
                    <span id="booleanStateLabel" style="font-size: 0.82rem; font-weight: 800; color: {{ $booleanMode ? '#00a884' : '#64748b' }};">
                        {{ $booleanMode ? 'ON' : 'OFF' }}
                    </span>
                </div>
            </div>

            <!-- 1. When Boolean Search is ON -->
            <div id="booleanSearchBox" style="{{ $booleanMode ? 'display: block;' : 'display: none;' }}">
                <div class="boolean-input-box">
                    <textarea id="booleanQueryInput" 
                              name="boolean_query" 
                              class="boolean-textarea" 
                              placeholder="e.g. ((Java AND Microservices) OR AWS) NOT Angular" 
                              rows="2">{{ $booleanQuery }}</textarea>
                </div>

                <div class="operator-toolbar">
                    <div class="op-buttons-group">
                        <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; margin-right: 4px;">Insert Operator:</span>
                        <button type="button" class="btn-op" onclick="insertOperator('AND')">+ AND</button>
                        <button type="button" class="btn-op" onclick="insertOperator('OR')">+ OR</button>
                        <button type="button" class="btn-op" onclick="insertOperator('NOT')">+ NOT</button>
                        <button type="button" class="btn-op" onclick="insertOperator('( )')">( )</button>
                        <button type="button" class="btn-op" onclick="insertOperator('\" \"')">"Quote"</button>
                    </div>

                    <a href="javascript:void(0)" onclick="openSyntaxHelpModal()" class="syntax-help-link">
                        <i class="fa-regular fa-circle-question"></i> View syntax help & examples
                    </a>
                </div>

                <!-- Live Validation Feedback -->
                <div id="booleanValidationFeedback" 
                     class="validation-feedback {{ $booleanError ? 'invalid' : ($booleanQuery ? 'valid' : '') }}" 
                     style="{{ ($booleanError || $booleanQuery) ? '' : 'display: none;' }}">
                    @if($booleanError)
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Syntax Error: {{ $booleanError }}</span>
                    @elseif($booleanQuery)
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Valid Boolean Expression</span>
                    @endif
                </div>
            </div>

            <!-- 2. When Boolean Search is OFF (Simple Keyword Search) -->
            <div id="simpleKeywordBox" style="{{ !$booleanMode ? 'display: block;' : 'display: none;' }}">
                <div class="simple-keyword-box" style="position: relative;">
                    <i class="fa-solid fa-magnifying-glass simple-keyword-icon"></i>
                    <input type="text" 
                           id="simpleKeywordInput" 
                           name="q" 
                           class="simple-keyword-input" 
                           placeholder="Search by keywords, skills, titles e.g. React, Python, Java, DevOps..." 
                           value="{{ $keyword }}"
                           autocomplete="off">
                    
                    <select name="keyword_mode" class="sort-select" title="Match condition for multiple keywords">
                        <option value="any" {{ request('keyword_mode', 'any') === 'any' ? 'selected' : '' }}>Match Any (OR)</option>
                        <option value="all" {{ request('keyword_mode') === 'all' ? 'selected' : '' }}>Match All (AND)</option>
                    </select>

                    <!-- Floating Autocomplete Dropdown from Database -->
                    <div id="indexKeywordAutocompleteDropdown" class="autocomplete-dropdown" style="top: 100%; left: 0; right: 0;">
                        <div class="autocomplete-header">Suggested Skills & Titles from Database</div>
                        <div id="indexKeywordAutocompleteItemsList"></div>
                    </div>
                </div>

                <!-- Suggested Skills Chips -->
                <div class="suggested-skills-row">
                    <span class="suggested-label"><i class="fa-solid fa-wand-magic-sparkles"></i> Suggested Skills:</span>
                    @foreach($suggestedSkills as $skill)
                        <span class="suggested-chip" onclick="appendKeywordChip('{{ $skill }}')">+ {{ $skill }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Search Actions Buttons -->
            <div class="search-actions-row">
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <button type="submit" class="btn-search-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Search Candidates
                    </button>
                    <a href="{{ route('admin.candidate_search.form', request()->all()) }}" class="btn-search-primary" style="background: #0284c7; text-decoration: none; border-color: #0284c7;">
                        <i class="fa-solid fa-pen-to-square"></i> Modify Search Form
                    </a>
                    <button type="button" onclick="resetAllResdexFilters()" class="btn-search-reset">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Active Filters Chips Bar -->
        @php
            $hasActiveFilters = $booleanQuery || $keyword || request()->filled('min_experience') || request()->filled('max_experience') || request()->filled('min_expected_ctc') || request()->filled('location') || request()->filled('job_type') || request()->filled('notice_period') || request()->filled('status') || request()->boolean('shortlisted_only');
        @endphp
        @if($hasActiveFilters)
        <div class="active-filters-bar">
            <span class="active-filters-label"><i class="fa-solid fa-sliders"></i> Applied Filters:</span>
            
            @if($booleanMode && $booleanQuery)
                <span class="filter-tag-chip">
                    <strong>Boolean:</strong> {{ Str::limit($booleanQuery, 40) }}
                    <span class="remove-chip" onclick="clearFilterField('boolean_query')">&times;</span>
                </span>
            @elseif(!$booleanMode && $keyword)
                <span class="filter-tag-chip">
                    <strong>Keyword:</strong> {{ $keyword }}
                    <span class="remove-chip" onclick="clearFilterField('q')">&times;</span>
                </span>
            @endif

            @if(request()->filled('min_experience') || request()->filled('max_experience'))
                <span class="filter-tag-chip">
                    <strong>Exp:</strong> {{ request('min_experience', 0) }} - {{ request('max_experience', 'Any') }} Yrs
                    <span class="remove-chip" onclick="clearFilterFields(['min_experience', 'max_experience'])">&times;</span>
                </span>
            @endif

            @if(request()->filled('location'))
                <span class="filter-tag-chip">
                    <strong>Location:</strong> {{ request('location') }}
                    <span class="remove-chip" onclick="clearFilterField('location')">&times;</span>
                </span>
            @endif

            @if(request()->filled('job_type'))
                <span class="filter-tag-chip">
                    <strong>Work Mode:</strong> {{ implode(', ', (array) request('job_type')) }}
                    <span class="remove-chip" onclick="clearFilterField('job_type')">&times;</span>
                </span>
            @endif

            @if(request()->filled('notice_period'))
                <span class="filter-tag-chip">
                    <strong>Notice:</strong> {{ implode(', ', (array) request('notice_period')) }}
                    <span class="remove-chip" onclick="clearFilterField('notice_period')">&times;</span>
                </span>
            @endif

            @if(request()->boolean('shortlisted_only'))
                <span class="filter-tag-chip" style="background: #fffbeb; border-color: #f59e0b; color: #b45309;">
                    ★ Starred / Shortlisted Only
                    <span class="remove-chip" onclick="clearFilterField('shortlisted_only')">&times;</span>
                </span>
            @endif

            <a href="javascript:void(0)" onclick="resetAllResdexFilters()" class="clear-all-link">Clear All</a>
        </div>
        @endif

        <!-- 2-Column Responsive Workspace Grid -->
        <div class="resdex-workspace-grid">
            <!-- Left Filter Sidebar -->
            <aside class="resdex-sidebar">
                <!-- Shortlisted Quick Filter -->
                <div class="sidebar-section">
                    <label class="filter-checkbox-item" style="font-weight: 700; color: #b45309;">
                        <span>
                            <input type="checkbox" name="shortlisted_only" value="1" {{ request()->boolean('shortlisted_only') ? 'checked' : '' }} onchange="this.form.submit()">
                            ★ Starred / Shortlisted
                        </span>
                        <span class="filter-count-badge">{{ $facets['shortlisted_count'] ?? 0 }}</span>
                    </label>
                </div>

                <!-- Experience Range -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">
                        <span><i class="fa-solid fa-graduation-cap" style="color: var(--resdex-teal);"></i> Experience (Years)</span>
                    </div>
                    <div class="filter-checkbox-list">
                        @php
                            $expBrackets = [
                                ['key' => 'any', 'label' => 'Any Experience', 'min' => '', 'max' => ''],
                                ['key' => '0-2', 'label' => '0 - 2 Years', 'min' => '0', 'max' => '2'],
                                ['key' => '3-5', 'label' => '3 - 5 Years', 'min' => '3', 'max' => '5'],
                                ['key' => '5-8', 'label' => '5 - 8 Years', 'min' => '5', 'max' => '8'],
                                ['key' => '8-12', 'label' => '8 - 12 Years', 'min' => '8', 'max' => '12'],
                                ['key' => '12+', 'label' => '12+ Years', 'min' => '12', 'max' => '50'],
                            ];
                        @endphp
                        @foreach($expBrackets as $bracket)
                            @php
                                $isSelected = request('min_experience') === $bracket['min'] && request('max_experience') === $bracket['max'];
                            @endphp
                            <label class="filter-checkbox-item">
                                <span>
                                    <input type="radio" name="exp_quick" onchange="setExperienceRange('{{ $bracket['min'] }}', '{{ $bracket['max'] }}')" {{ $isSelected ? 'checked' : '' }}>
                                    {{ $bracket['label'] }}
                                </span>
                                @if($bracket['key'] !== 'any')
                                    <span class="filter-count-badge">{{ $facets['exp_ranges'][$bracket['key']] ?? 0 }}</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    <div class="range-inputs-row">
                        <input type="number" step="0.5" id="min_experience" name="min_experience" class="range-input" placeholder="Min Yrs" value="{{ request('min_experience') }}">
                        <input type="number" step="0.5" id="max_experience" name="max_experience" class="range-input" placeholder="Max Yrs" value="{{ request('max_experience') }}">
                    </div>
                </div>

                <!-- Expected CTC Range -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">
                        <span><i class="fa-solid fa-indian-rupee-sign" style="color: var(--resdex-teal);"></i> Expected CTC (Annual)</span>
                    </div>
                    <div class="filter-checkbox-list">
                        @php
                            $ctcBrackets = [
                                ['key' => 'any', 'label' => 'Any CTC', 'min' => '', 'max' => ''],
                                ['key' => '0-1', 'label' => '0 - 1 Lac', 'min' => '0', 'max' => '1'],
                                ['key' => '1-2', 'label' => '1 - 2 Lac', 'min' => '1', 'max' => '2'],
                                ['key' => '2-5', 'label' => '2 - 5 Lac', 'min' => '2', 'max' => '5'],
                                ['key' => '5-10', 'label' => '5 - 10 Lac', 'min' => '5', 'max' => '10'],
                                ['key' => '10-15', 'label' => '10 - 15 Lac', 'min' => '10', 'max' => '15'],
                                ['key' => '15-50', 'label' => '15 - 50 Lac', 'min' => '15', 'max' => '50'],
                                ['key' => '50+', 'label' => '50+ Lac', 'min' => '50', 'max' => ''],
                            ];
                            $currMinCtc = request('min_expected_ctc', request('min_salary'));
                            $currMaxCtc = request('max_expected_ctc', request('max_salary'));
                        @endphp
                        @foreach($ctcBrackets as $bracket)
                            @php
                                $isSelected = false;
                                if ($bracket['min'] === '' && $bracket['max'] === '') {
                                    $isSelected = empty($currMinCtc) && empty($currMaxCtc);
                                } else {
                                    $matchMin = ($currMinCtc == $bracket['min']) || ($bracket['min'] !== '' && $currMinCtc == (float)$bracket['min'] * 100000);
                                    $matchMax = ($currMaxCtc == $bracket['max']) || ($bracket['max'] !== '' && $currMaxCtc == (float)$bracket['max'] * 100000);
                                    $isSelected = $matchMin && $matchMax;
                                }
                            @endphp
                            <label class="filter-checkbox-item">
                                <span>
                                    <input type="radio" 
                                           name="ctc_quick" 
                                           onchange="setCtcRange('{{ $bracket['min'] }}', '{{ $bracket['max'] }}')" 
                                           {{ $isSelected ? 'checked' : '' }}>
                                    {{ $bracket['label'] }}
                                </span>
                                @if($bracket['key'] !== 'any')
                                    <span class="filter-count-badge">{{ $facets['ctc_ranges'][$bracket['key']] ?? 0 }}</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    <div class="range-inputs-row" style="margin-top: 8px;">
                        <input type="number" step="any" id="min_expected_ctc" name="min_expected_ctc" class="range-input" placeholder="Min (Lacs or ₹)" value="{{ $currMinCtc }}">
                        <input type="number" step="any" id="max_expected_ctc" name="max_expected_ctc" class="range-input" placeholder="Max (Lacs or ₹)" value="{{ $currMaxCtc }}">
                    </div>
                </div>

                <!-- Work Mode / Job Type -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">
                        <span><i class="fa-solid fa-briefcase" style="color: var(--resdex-teal);"></i> Work Mode</span>
                    </div>
                    <div class="filter-checkbox-list">
                        @php
                            $selectedJobTypes = (array) request('job_type', []);
                        @endphp
                        @foreach(['Remote', 'Hybrid', 'Full Time', 'Contract', 'Part Time'] as $jt)
                            <label class="filter-checkbox-item">
                                <span>
                                    <input type="checkbox" name="job_type[]" value="{{ $jt }}" {{ in_array($jt, $selectedJobTypes) ? 'checked' : '' }}>
                                    {{ $jt }}
                                </span>
                                <span class="filter-count-badge">{{ $facets['job_types'][$jt] ?? 0 }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Notice Period -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">
                        <span><i class="fa-solid fa-clock" style="color: var(--resdex-teal);"></i> Notice Period</span>
                    </div>
                    <div class="filter-checkbox-list">
                        @php
                            $selectedNotice = (array) request('notice_period', []);
                        @endphp
                        @foreach(['Immediate', '15 Days', '30 Days', '60 Days', '90 Days'] as $np)
                            <label class="filter-checkbox-item">
                                <span>
                                    <input type="checkbox" name="notice_period[]" value="{{ $np }}" {{ in_array($np, $selectedNotice) ? 'checked' : '' }}>
                                    {{ $np }}
                                </span>
                                <span class="filter-count-badge">{{ $facets['notice_periods'][$np] ?? 0 }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Candidate Stage -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">
                        <span><i class="fa-solid fa-user-tag" style="color: var(--resdex-teal);"></i> Pipeline Stage</span>
                    </div>
                    <div class="filter-checkbox-list">
                        @php
                            $selectedStatus = (array) request('status', []);
                        @endphp
                        @foreach(['Applied', 'Screening', 'Interview Scheduled', 'Offered', 'Hired', 'Rejected'] as $st)
                            <label class="filter-checkbox-item">
                                <span>
                                    <input type="checkbox" name="status[]" value="{{ $st }}" {{ in_array($st, $selectedStatus) ? 'checked' : '' }}>
                                    {{ $st }}
                                </span>
                                <span class="filter-count-badge">{{ $facets['statuses'][$st] ?? 0 }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Location -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">
                        <span><i class="fa-solid fa-location-dot" style="color: var(--resdex-teal);"></i> Location</span>
                    </div>
                    <div style="position: relative;">
                        <input type="text" 
                               id="sidebarLocationInput" 
                               name="location" 
                               class="range-input" 
                               placeholder="e.g. Bengaluru, Delhi, Remote..." 
                               value="{{ request('location') }}" 
                               style="width: 100%; margin-bottom: 0;"
                               autocomplete="off">

                        <!-- Floating Autocomplete Dropdown from Database -->
                        <div id="sidebarLocationAutocompleteDropdown" class="autocomplete-dropdown" style="top: 100%; left: 0; right: 0;">
                            <div class="autocomplete-header">Candidate Locations from Database</div>
                            <div id="sidebarLocationAutocompleteItemsList"></div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <button type="submit" class="btn-search-primary" style="width: 100%; justify-content: center;">
                        <i class="fa-solid fa-filter"></i> Apply Filters
                    </button>
                </div>
            </aside>

            <!-- Main Results Area -->
            <main>
                <!-- Results Toolbar -->
                <div class="results-toolbar">
                    <div class="results-count-text">
                        Showing <strong style="color: var(--resdex-teal);">{{ $candidates->total() }}</strong> Matching Candidates 
                        @if($candidates->total() < $totalCandidatesCount)
                            <span style="font-size: 0.84rem; font-weight: 500; color: var(--resdex-text-muted);">(out of {{ $totalCandidatesCount }} in database)</span>
                        @endif
                    </div>

                    <div class="results-sort-controls">
                        <label style="font-size: 0.84rem; font-weight: 700; color: #64748b;">Sort By:</label>
                        <select name="sort_by" class="sort-select" onchange="this.form.submit()">
                            <option value="relevance" {{ $sortBy === 'relevance' ? 'selected' : '' }}>Relevance</option>
                            <option value="updated_at" {{ $sortBy === 'updated_at' ? 'selected' : '' }}>Recently Updated</option>
                            <option value="experience_desc" {{ $sortBy === 'experience_desc' ? 'selected' : '' }}>Experience: High to Low</option>
                            <option value="experience_asc" {{ $sortBy === 'experience_asc' ? 'selected' : '' }}>Experience: Low to High</option>
                            <option value="ctc_asc" {{ $sortBy === 'ctc_asc' ? 'selected' : '' }}>Expected CTC: Low to High</option>
                            <option value="ctc_desc" {{ $sortBy === 'ctc_desc' ? 'selected' : '' }}>Expected CTC: High to Low</option>
                        </select>

                        <select name="per_page" class="sort-select" onchange="this.form.submit()" title="Results per page">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / page</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / page</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / page</option>
                        </select>
                    </div>
                </div>

                <!-- Candidate Results Cards List -->
                <div id="resdexResultsContainer">
                    @include('admin.candidate_search.partials.results')
                </div>
            </main>
        </div>
    </div>
</form>

<!-- Modal 1: Boolean Syntax Help Modal -->
<div id="syntaxHelpModal" class="resdex-modal-overlay">
    <div class="resdex-modal-card" style="max-width: 680px;">
        <div class="resdex-modal-header">
            <h4 style="margin: 0; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-brain" style="color: #38bdf8;"></i> Boolean Search Syntax Guide (Naukri Resdex)
            </h4>
            <button type="button" onclick="closeSyntaxHelpModal()" style="background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 24px; overflow-y: auto;">
            <p style="font-size: 0.92rem; color: #475569; margin-top: 0;">
                Boolean search lets you combine keywords with operators to target the exact candidate profiles you need across skills, designations, and experience.
            </p>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; margin: 16px 0;">
                <thead>
                    <tr style="background: #f1f5f9; text-align: left;">
                        <th style="padding: 10px 14px; border: 1px solid #cbd5e1;">Operator</th>
                        <th style="padding: 10px 14px; border: 1px solid #cbd5e1;">Example Expression</th>
                        <th style="padding: 10px 14px; border: 1px solid #cbd5e1;">Expected Behavior</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-weight: 700; color: #00a884;">AND</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-family: monospace;">Java AND AWS</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1;">Candidate must match <strong>both</strong> Java and AWS</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-weight: 700; color: #0284c7;">OR</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-family: monospace;">Java OR Python</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1;">Candidate matches either Java, Python, or both</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-weight: 700; color: #ef4444;">NOT</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-family: monospace;">Java NOT Angular</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1;">Matches Java but strictly <strong>excludes</strong> Angular</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-weight: 700;">( ) Group</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-family: monospace;">(Java AND AWS) OR Azure</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1;">Java and AWS together, or Azure alone</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-weight: 700;">" " Quotes</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1; font-family: monospace;">("Spring Boot" AND Java) NOT PHP</td>
                        <td style="padding: 10px 14px; border: 1px solid #cbd5e1;">Exact multi-word phrase "Spring Boot" and Java, excluding PHP</td>
                    </tr>
                </tbody>
            </table>

            <div style="background: #e6f7f3; padding: 14px; border-radius: 10px; border-left: 4px solid #00a884; font-size: 0.85rem; color: #065f46;">
                <strong>💡 Pro-Tip:</strong> You can click any of the operator helper buttons (<code>+ AND</code>, <code>+ OR</code>, <code>+ NOT</code>, <code>( )</code>) to instantly insert operators into your search box.
            </div>
        </div>
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: right;">
            <button type="button" onclick="closeSyntaxHelpModal()" class="btn-search-primary" style="padding: 8px 18px; font-size: 0.88rem;">Got It</button>
        </div>
    </div>
</div>

<!-- Modal 2: Resume Preview Modal -->
<div id="resumePreviewModal" class="resdex-modal-overlay">
    <div class="resdex-modal-card" style="max-width: 900px; height: 85vh;">
        <div class="resdex-modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 1.25rem;"></i>
                <h4 id="resumeModalCandidateName" style="margin: 0; font-size: 1rem; font-weight: 700;">Resume Preview</h4>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <a id="resumeModalDownloadBtn" href="#" class="btn btn-sm" style="background: var(--resdex-teal); color: #fff; border-radius: 8px; font-weight: 700; padding: 6px 14px; text-decoration: none;" download>
                    <i class="fa-solid fa-download"></i> Download
                </a>
                <button type="button" onclick="closeResumeModal()" style="background: none; border: none; color: #94a3b8; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>
        </div>
        <div style="flex: 1; position: relative;">
            <iframe id="resumeModalIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ==========================================
    // RESDEX INTERFACE CONTROLS & LOGIC
    // ==========================================

    // 1. Toggle Boolean Mode Switch (ON/OFF)
    function toggleBooleanMode(isOn) {
        document.getElementById('booleanSearchBox').style.display = isOn ? 'block' : 'none';
        document.getElementById('simpleKeywordBox').style.display = isOn ? 'none' : 'block';
        
        const label = document.getElementById('booleanStateLabel');
        label.innerText = isOn ? 'ON' : 'OFF';
        label.style.color = isOn ? '#00a884' : '#64748b';

        if (isOn) {
            document.getElementById('booleanQueryInput').focus();
            validateBooleanLive();
        } else {
            document.getElementById('simpleKeywordInput').focus();
        }
    }

    // 2. Insert Operators at Cursor in Boolean Input
    function insertOperator(op) {
        const textarea = document.getElementById('booleanQueryInput');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        let insertion = op;
        let cursorOffset = op.length;

        if (op === '( )') {
            insertion = '( ';
            cursorOffset = 2;
        } else if (op === '" "') {
            insertion = '"';
            cursorOffset = 1;
        } else {
            insertion = ' ' + op + ' ';
            cursorOffset = insertion.length;
        }

        textarea.value = text.substring(0, start) + insertion + text.substring(end);
        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = start + cursorOffset;

        validateBooleanLive();
    }

    // 3. As-You-Type Live Boolean Syntax Validation (Debounced)
    let validationDebounceTimer = null;
    function validateBooleanLive() {
        clearTimeout(validationDebounceTimer);
        const query = document.getElementById('booleanQueryInput').value.trim();
        const feedback = document.getElementById('booleanValidationFeedback');

        if (!query) {
            feedback.style.display = 'none';
            return;
        }

        validationDebounceTimer = setTimeout(() => {
            fetch('{{ route("admin.candidate_search.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ expression: query })
            })
            .then(res => res.json())
            .then(data => {
                feedback.style.display = 'flex';
                if (data.valid) {
                    feedback.className = 'validation-feedback valid';
                    feedback.innerHTML = `<i class="fa-solid fa-circle-check"></i> <span>Valid Boolean Expression (${data.matching_count} matching profiles in database)</span>`;
                } else {
                    feedback.className = 'validation-feedback invalid';
                    feedback.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> <span>${data.error}</span>`;
                }
            })
            .catch(err => console.error('Validation error:', err));
        }, 320);
    }

    document.getElementById('booleanQueryInput')?.addEventListener('input', validateBooleanLive);

    // 4. Append Suggested Skill to Simple Keyword Search
    function appendKeywordChip(skill) {
        const input = document.getElementById('simpleKeywordInput');
        let current = input.value.trim();
        if (current) {
            const terms = current.split(',').map(s => s.trim().toLowerCase());
            if (!terms.includes(skill.toLowerCase())) {
                input.value = current + ', ' + skill;
            }
        } else {
            input.value = skill;
        }
        input.focus();
    }

    // 5. Quick Set Experience Range
    function setExperienceRange(min, max) {
        document.getElementById('min_experience').value = min;
        document.getElementById('max_experience').value = max;
    }

    function setCtcRange(min, max) {
        document.getElementById('min_expected_ctc').value = min;
        document.getElementById('max_expected_ctc').value = max;
    }

    // 6. Clear Filter Fields
    function clearFilterField(name) {
        const form = document.getElementById('resdexSearchForm');
        const elements = form.querySelectorAll(`[name="${name}"], [name="${name}[]"]`);
        elements.forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = '';
            }
        });
        form.submit();
    }

    function clearFilterFields(names) {
        names.forEach(name => {
            clearFilterField(name);
        });
    }

    function resetAllResdexFilters() {
        window.location.href = '{{ route("admin.candidate_search.index") }}';
    }

    // 7. Toggle Shortlist (Star) AJAX
    function toggleCandidateShortlist(candidateId, button) {
        fetch(`{{ url('admin/candidate-search/toggle-shortlist') }}/${candidateId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById(`candidate-card-${candidateId}`);
                const icon = button.querySelector('i');
                const label = button.querySelector('.star-label');

                if (data.is_highlighted) {
                    button.classList.add('active');
                    icon.className = 'fa-solid fa-star';
                    label.innerText = 'Shortlisted';
                    if (card) card.classList.add('is-shortlisted');
                } else {
                    button.classList.remove('active');
                    icon.className = 'fa-regular fa-star';
                    label.innerText = 'Shortlist';
                    if (card) card.classList.remove('is-shortlisted');
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        })
        .catch(err => console.error('Error toggling shortlist:', err));
    }

    // 8. Toggle More Skills
    function toggleMoreSkills(id, button) {
        const moreContainer = document.getElementById(`more-skills-${id}`);
        if (moreContainer.style.display === 'none') {
            moreContainer.style.display = 'inline';
            button.innerText = 'Show less';
        } else {
            moreContainer.style.display = 'none';
            button.innerText = '+ more';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    // ==========================================
    // 9. TOP KEYWORD AUTOCOMPLETE (FROM DATABASE)
    // ==========================================
    const topKeywordInput = document.getElementById('simpleKeywordInput');
    const topKeywordDropdown = document.getElementById('indexKeywordAutocompleteDropdown');
    const topKeywordItemsList = document.getElementById('indexKeywordAutocompleteItemsList');
    let topKeywordTimer = null;
    let selectedTopKeywordIndex = -1;
    let currentTopKeywordSuggestions = [];

    if (topKeywordInput && topKeywordDropdown) {
        topKeywordInput.addEventListener('input', function() {
            const fullVal = this.value;
            const parts = fullVal.split(',');
            const currentToken = parts[parts.length - 1].trim();

            clearTimeout(topKeywordTimer);

            if (currentToken.length < 1) {
                hideTopKeywordAutocomplete();
                return;
            }

            topKeywordTimer = setTimeout(() => {
                fetchTopKeywordSuggestions(currentToken);
            }, 180);
        });

        topKeywordInput.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateTopKeywordDropdown(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateTopKeywordDropdown(-1);
            } else if (e.key === 'Enter') {
                if (selectedTopKeywordIndex >= 0 && currentTopKeywordSuggestions[selectedTopKeywordIndex]) {
                    e.preventDefault();
                    selectTopKeyword(currentTopKeywordSuggestions[selectedTopKeywordIndex].text);
                }
            } else if (e.key === 'Escape') {
                hideTopKeywordAutocomplete();
            }
        });
    }

    function fetchTopKeywordSuggestions(query) {
        fetch(`{{ route('admin.candidate_search.suggestions') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                currentTopKeywordSuggestions = data;
                selectedTopKeywordIndex = -1;

                if (!data || data.length === 0) {
                    hideTopKeywordAutocomplete();
                    return;
                }

                topKeywordItemsList.innerHTML = '';
                data.forEach((item, index) => {
                    const el = document.createElement('div');
                    el.className = 'autocomplete-item';
                    el.innerHTML = `<span><i class="fa-solid fa-search" style="color: #94a3b8; font-size: 0.8rem; margin-right: 8px;"></i> ${escapeHtml(item.text)}</span> <span class="autocomplete-item-type">${escapeHtml(item.type)}</span>`;
                    el.addEventListener('click', () => {
                        selectTopKeyword(item.text);
                    });
                    topKeywordItemsList.appendChild(el);
                });

                topKeywordDropdown.style.display = 'block';
            })
            .catch(err => {
                console.error('Error fetching keyword suggestions:', err);
                hideTopKeywordAutocomplete();
            });
    }

    function selectTopKeyword(keyword) {
        const fullVal = topKeywordInput.value;
        const parts = fullVal.split(',').map(s => s.trim()).filter(Boolean);
        if (parts.length > 0) {
            parts[parts.length - 1] = keyword;
        } else {
            parts.push(keyword);
        }
        topKeywordInput.value = parts.join(', ') + ', ';
        hideTopKeywordAutocomplete();
        topKeywordInput.focus();
    }

    function navigateTopKeywordDropdown(direction) {
        const items = topKeywordItemsList.querySelectorAll('.autocomplete-item');
        if (items.length === 0) return;

        selectedTopKeywordIndex += direction;
        if (selectedTopKeywordIndex < 0) selectedTopKeywordIndex = items.length - 1;
        if (selectedTopKeywordIndex >= items.length) selectedTopKeywordIndex = 0;

        items.forEach((item, idx) => {
            if (idx === selectedTopKeywordIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    function hideTopKeywordAutocomplete() {
        if (topKeywordDropdown) {
            topKeywordDropdown.style.display = 'none';
        }
        selectedTopKeywordIndex = -1;
    }

    // ==========================================
    // 10. SIDEBAR LOCATION AUTOCOMPLETE (FROM DATABASE)
    // ==========================================
    const sidebarLocInput = document.getElementById('sidebarLocationInput');
    const sidebarLocDropdown = document.getElementById('sidebarLocationAutocompleteDropdown');
    const sidebarLocItemsList = document.getElementById('sidebarLocationAutocompleteItemsList');
    let sidebarLocTimer = null;
    let selectedSidebarLocIndex = -1;
    let currentSidebarLocSuggestions = [];

    if (sidebarLocInput && sidebarLocDropdown) {
        sidebarLocInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(sidebarLocTimer);

            if (query.length < 1) {
                hideSidebarLocAutocomplete();
                return;
            }

            sidebarLocTimer = setTimeout(() => {
                fetchSidebarLocSuggestions(query);
            }, 180);
        });

        sidebarLocInput.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateSidebarLocDropdown(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateSidebarLocDropdown(-1);
            } else if (e.key === 'Enter') {
                if (selectedSidebarLocIndex >= 0 && currentSidebarLocSuggestions[selectedSidebarLocIndex]) {
                    e.preventDefault();
                    selectSidebarLoc(currentSidebarLocSuggestions[selectedSidebarLocIndex].text);
                }
            } else if (e.key === 'Escape') {
                hideSidebarLocAutocomplete();
            }
        });
    }

    function fetchSidebarLocSuggestions(query) {
        fetch(`{{ route('admin.candidate_search.locations') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                currentSidebarLocSuggestions = data;
                selectedSidebarLocIndex = -1;

                if (!data || data.length === 0) {
                    hideSidebarLocAutocomplete();
                    return;
                }

                sidebarLocItemsList.innerHTML = '';
                data.forEach((item, index) => {
                    const el = document.createElement('div');
                    el.className = 'autocomplete-item';
                    el.innerHTML = `<span><i class="fa-solid fa-location-dot" style="color: var(--resdex-teal); font-size: 0.85rem; margin-right: 8px;"></i> ${escapeHtml(item.text)}</span> <span class="autocomplete-item-type">${escapeHtml(item.type)}</span>`;
                    el.addEventListener('click', () => {
                        selectSidebarLoc(item.text);
                    });
                    sidebarLocItemsList.appendChild(el);
                });

                sidebarLocDropdown.style.display = 'block';
            })
            .catch(err => {
                console.error('Error fetching sidebar location suggestions:', err);
                hideSidebarLocAutocomplete();
            });
    }

    function selectSidebarLoc(loc) {
        sidebarLocInput.value = loc;
        hideSidebarLocAutocomplete();
        sidebarLocInput.focus();
    }

    function navigateSidebarLocDropdown(direction) {
        const items = sidebarLocItemsList.querySelectorAll('.autocomplete-item');
        if (items.length === 0) return;

        selectedSidebarLocIndex += direction;
        if (selectedSidebarLocIndex < 0) selectedSidebarLocIndex = items.length - 1;
        if (selectedSidebarLocIndex >= items.length) selectedSidebarLocIndex = 0;

        items.forEach((item, idx) => {
            if (idx === selectedSidebarLocIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    function hideSidebarLocAutocomplete() {
        if (sidebarLocDropdown) {
            sidebarLocDropdown.style.display = 'none';
        }
        selectedSidebarLocIndex = -1;
    }

    // Close autocompletes on outside click
    document.addEventListener('click', function(e) {
        if (topKeywordInput && !topKeywordInput.parentElement.contains(e.target)) {
            hideTopKeywordAutocomplete();
        }
        if (sidebarLocInput && !sidebarLocInput.parentElement.contains(e.target)) {
            hideSidebarLocAutocomplete();
        }
    });

    // 10. Syntax Help Modal
    function openSyntaxHelpModal() {
        document.getElementById('syntaxHelpModal').style.display = 'flex';
    }

    function closeSyntaxHelpModal() {
        document.getElementById('syntaxHelpModal').style.display = 'none';
    }

    // 11. Resume Preview Modal
    function openResumeModal(previewUrl, downloadUrl, candidateName) {
        document.getElementById('resumeModalCandidateName').innerText = 'Resume: ' + candidateName;
        document.getElementById('resumeModalIframe').src = previewUrl;
        document.getElementById('resumeModalDownloadBtn').href = downloadUrl;
        document.getElementById('resumePreviewModal').style.display = 'flex';
    }

    function closeResumeModal() {
        document.getElementById('resumePreviewModal').style.display = 'none';
        document.getElementById('resumeModalIframe').src = '';
    }

    // Escape Key Modal Closer
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSyntaxHelpModal();
            closeSaveSearchModal();
            closeResumeModal();
        }
    });
</script>
@endsection
