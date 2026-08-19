@extends('layouts.employee')

@section('title', 'Quick Candidate Entry (Data Entry Mode)')
@section('page_title', 'Quick Candidate Entry')

@section('styles')
<style>
    .quick-entry-card {
        background: #ffffff;
        border: 1px solid #9ee5d4;
        border-radius: var(--radius);
        box-shadow: 0 10px 25px -5px rgba(0, 168, 132, 0.08);
        margin-bottom: 80px; /* Space for sticky bottom action bar */
    }

    .quick-entry-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f0faf7 0%, #ffffff 100%);
        border-bottom: 1px solid #9ee5d4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .counter-pill {
        background: #ffffff;
        border: 1px solid #9ee5d4;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .counter-pill span {
        color: #00a884;
        font-size: 0.95rem;
    }

    .quick-section-header {
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #00a884;
        margin-bottom: 12px;
        padding-bottom: 6px;
        border-bottom: 1.5px solid #e6f7f3;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .compact-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }
    .compact-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group-compact {
        display: flex;
        flex-direction: column;
    }
    .form-group-compact label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 4px;
    }
    .form-group-compact label span.required {
        color: #ef4444;
    }
    .form-group-compact input,
    .form-group-compact select,
    .form-group-compact textarea {
        padding: 8px 12px;
        font-size: 0.88rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
        background: #ffffff;
        transition: all 0.15s ease;
    }
    .form-group-compact input:focus,
    .form-group-compact select:focus,
    .form-group-compact textarea:focus {
        border-color: #00a884;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.15);
    }
    .form-group-compact input.is-invalid,
    .form-group-compact select.is-invalid {
        border-color: #ef4444 !important;
        background-color: #fef2f2;
    }
    .inline-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 3px;
        font-weight: 600;
    }

    /* Duplicate Alert Inline Banner */
    .duplicate-alert-banner {
        background: #fffbebf8;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        margin-top: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Sticky Bottom Action Bar */
    .sticky-action-bar {
        position: fixed;
        bottom: 0; left: 260px; right: 0;
        background: #0f172a;
        color: #ffffff;
        padding: 14px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 1000;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    }

    /* Toast Notification Floating Card */
    .toast-float-card {
        position: fixed;
        top: 25px; right: 25px;
        background: #00a884;
        color: #ffffff;
        padding: 14px 22px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.92rem;
        box-shadow: 0 10px 30px rgba(0, 168, 132, 0.35);
        z-index: 99999;
        display: none;
        align-items: center;
        gap: 10px;
        animation: slideInRight 0.3s ease;
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @media (max-width: 990px) {
        .sticky-action-bar { left: 0; }
        .compact-grid-3 { grid-template-columns: 1fr; }
        .compact-grid-2 { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<!-- Floating Success Toast Notification -->
<div id="toastNotification" class="toast-float-card">
    <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
    <span id="toastMessage">Candidate saved successfully!</span>
</div>

<div class="quick-entry-card">
    <!-- Quick Entry Page Header -->
    <div class="quick-entry-header">
        <div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0;">⚡ Quick Candidate Entry</h2>
                <span class="badge" style="background-color: #e6f7f3; color: #00a884; border: 1px solid #9ee5d4; font-weight: 800; font-size: 0.78rem;">
                    Data Entry Mode
                </span>
            </div>
            <div style="font-size: 0.85rem; color: #64748b; margin-top: 2px;">
                Fast, keyboard-optimized candidate entry interface for Data Entry Operators
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div class="counter-pill">
                <i class="fa-solid fa-calendar-day" style="color: #00a884;"></i> Added Today: <span id="todayCountBadge">{{ $todayCount }}</span>
            </div>
            <div class="counter-pill">
                <i class="fa-solid fa-stopwatch" style="color: #00a884;"></i> Current Session: <span id="sessionCountBadge">0</span>
            </div>
            <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 10px; padding: 8px 16px; font-weight: 700;">
                <i class="fa-solid fa-arrow-left"></i> Candidate Directory
            </a>
        </div>
    </div>

    <!-- Quick Entry Form Container -->
    <form id="quickCandidateForm" action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
        @csrf

        <!-- Section 1: Personal & Contact Information -->
        <div class="quick-section-header">
            <i class="fa-solid fa-address-card"></i> 1. Personal & Contact Information
        </div>

        <div class="compact-grid-2">
            <!-- Full Name -->
            <div class="form-group-compact">
                <label for="inputName">Full Name <span class="required">*</span></label>
                <input type="text" id="inputName" name="name" tabindex="1" autofocus placeholder="e.g. Rahul Sharma" required autocomplete="off">
                <div class="inline-error" id="error_name"></div>
            </div>

            <!-- Phone Number -->
            <div class="form-group-compact">
                <label for="inputPhone">Phone Number <span class="required">*</span></label>
                <input type="tel" id="inputPhone" name="phone" tabindex="2" placeholder="e.g. 9876543210" pattern="^(\+91[\-\s]?)?[6789]\d{9}$" maxlength="13" title="Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210)" required autocomplete="off">
                <div class="inline-error" id="error_phone"></div>
                <div id="duplicatePhoneBanner" style="display: none;"></div>
            </div>
        </div>

        <div class="compact-grid-2">
            <!-- Email Address -->
            <div class="form-group-compact">
                <label for="inputEmail">Email Address <span class="required">*</span></label>
                <input type="email" id="inputEmail" name="email" tabindex="3" placeholder="e.g. rahul@example.com" required autocomplete="off">
                <div class="inline-error" id="error_email"></div>
                <div id="duplicateEmailBanner" style="display: none;"></div>
            </div>

            <!-- Location -->
            <div class="form-group-compact">
                <label for="inputLocation">Location (City / State) <span class="required">*</span></label>
                <input type="text" id="inputLocation" name="location" tabindex="4" placeholder="e.g. Indore, MP" required autocomplete="off">
                <div class="inline-error" id="error_location"></div>
            </div>
        </div>

        <!-- Section 2: Professional & Recruitment Details -->
        <div class="quick-section-header" style="margin-top: 20px;">
            <i class="fa-solid fa-briefcase"></i> 2. Professional & Recruitment Details
        </div>

        <div class="compact-grid-3">
            <!-- Key Skills -->
            <div class="form-group-compact">
                <label for="inputSkills">Key Skills <span class="required">*</span></label>
                <input type="text" id="inputSkills" name="skills" tabindex="5" placeholder="e.g. PHP, Laravel, MySQL, React" required autocomplete="off">
                <div class="inline-error" id="error_skills"></div>
            </div>

            <!-- Experience -->
            <div class="form-group-compact">
                <label for="inputExperience">Experience (Years) <span class="required">*</span></label>
                <input type="number" step="0.5" min="0" max="50" id="inputExperience" name="experience" tabindex="6" placeholder="e.g. 3" required autocomplete="off">
                <div class="inline-error" id="error_experience"></div>
            </div>

            <!-- Job Type -->
            <div class="form-group-compact">
                <label for="inputJobType">Job Type <span class="required">*</span></label>
                <select id="inputJobType" name="job_type" tabindex="7" required>
                    <option value="Full Time" selected>Full Time</option>
                    <option value="Part Time">Part Time</option>
                    <option value="Contract">Contract</option>
                    <option value="Remote">Remote</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
                <div class="inline-error" id="error_job_type"></div>
            </div>
        </div>

        <div class="compact-grid-3">
            <!-- Notice Period -->
            <div class="form-group-compact">
                <label for="inputNoticePeriod">Notice Period <span class="required">*</span></label>
                <select id="inputNoticePeriod" name="notice_period" tabindex="8" required>
                    <option value="Immediate" selected>Immediate</option>
                    <option value="15 Days">15 Days</option>
                    <option value="30 Days">30 Days</option>
                    <option value="60 Days">60 Days</option>
                    <option value="90 Days">90 Days</option>
                </select>
                <div class="inline-error" id="error_notice_period"></div>
            </div>

            <!-- Current CTC -->
            <div class="form-group-compact">
                <label for="inputCurrentCtc">Current CTC (Annual ₹)</label>
                <input type="number" step="10000" min="0" id="inputCurrentCtc" name="current_ctc" tabindex="9" placeholder="e.g. 500000" autocomplete="off">
                <div class="inline-error" id="error_current_ctc"></div>
            </div>

            <!-- Expected CTC -->
            <div class="form-group-compact">
                <label for="inputExpectedCtc">Expected CTC (Annual ₹)</label>
                <input type="number" step="10000" min="0" id="inputExpectedCtc" name="expected_ctc" tabindex="10" placeholder="e.g. 700000" autocomplete="off">
                <div class="inline-error" id="error_expected_ctc"></div>
            </div>
        </div>

        <div class="form-group-compact" style="margin-top: 15px;">
            <label for="inputResume">Resume File (PDF, DOC, DOCX - Max 5MB)</label>
            <input type="file" id="inputResume" name="resume_file" tabindex="11" accept=".pdf,.doc,.docx">
            <div class="inline-error" id="error_resume_file"></div>
        </div>

        <!-- Section 3: HR Notes -->
        <div class="quick-section-header" style="margin-top: 15px;">
            <i class="fa-solid fa-comment-dots"></i> 3. HR Evaluation Notes / Remarks (Optional)
        </div>

        <div class="form-group-compact">
            <textarea id="inputNote" name="note" tabindex="14" rows="2" placeholder="Add optional remarks or evaluation notes..." style="resize: vertical;"></textarea>
            <div class="inline-error" id="error_note"></div>
        </div>
    </form>
</div>

<!-- Sticky Bottom Action Bar for Continuous Data Entry -->
<div class="sticky-action-bar">
    <div style="font-size: 0.85rem; font-weight: 600; color: #94a3b8; display: flex; align-items: center; gap: 10px;">
        <span style="color: #00a884; font-weight: 800;">⚡ DATA ENTRY MODE ACTIVE</span>
        <span>• Press <strong style="color: #ffffff;">Ctrl + Enter</strong> to Save & Start Next Entry</span>
    </div>

    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 600;">
            Cancel
        </a>
        <button type="button" onclick="submitCandidateForm(false)" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 700; background: #334155; color: #ffffff; border: none;">
            Save Candidate
        </button>
        <button type="button" id="btnSaveAndNew" tabindex="15" onclick="submitCandidateForm(true)" class="btn btn-primary btn-sm" style="background-color: #00a884; border-color: #00a884; border-radius: 8px; font-weight: 800; padding: 8px 20px;">
            <i class="fa-solid fa-bolt"></i> Save & New (Ctrl + Enter)
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let sessionCount = 0;
    let duplicateCheckTimer = null;

    // Show floating toast message
    function showToast(message) {
        const toast = document.getElementById('toastNotification');
        document.getElementById('toastMessage').innerText = message;
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    // Clear inline errors
    function clearInlineErrors() {
        document.querySelectorAll('.inline-error').forEach(el => el.innerText = '');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    // Submit Form via AJAX or Normal Form Submit
    function submitCandidateForm(isSaveAndNew = true) {
        clearInlineErrors();

        const form = document.getElementById('quickCandidateForm');
        const formData = new FormData(form);

        if (!isSaveAndNew) {
            // Normal submit and redirect
            form.submit();
            return;
        }

        // Save & New AJAX Submission
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 422) {
                // Validation Errors
                const errors = res.body.errors || {};
                for (const key in errors) {
                    const errorEl = document.getElementById('error_' + key);
                    const inputEl = document.querySelector(`[name="${key}"]`);
                    if (errorEl) errorEl.innerText = errors[key][0];
                    if (inputEl) inputEl.classList.add('is-invalid');
                }
            } else if (res.body.success) {
                // Success Save & New
                sessionCount++;
                document.getElementById('sessionCountBadge').innerText = sessionCount;
                if (res.body.today_count) {
                    document.getElementById('todayCountBadge').innerText = res.body.today_count;
                }

                showToast(res.body.message || 'Candidate saved successfully ✓');

                // Clear Form & Reset Defaults
                form.reset();
                document.getElementById('inputJobType').value = 'Full Time';
                document.getElementById('inputNoticePeriod').value = 'Immediate';
                document.getElementById('inputStatus').value = 'Applied';
                document.getElementById('duplicatePhoneBanner').style.display = 'none';
                document.getElementById('duplicateEmailBanner').style.display = 'none';

                // Auto Focus Full Name Immediately
                setTimeout(() => {
                    document.getElementById('inputName').focus();
                }, 100);
            }
        })
        .catch(err => {
            console.error('Save & New Error:', err);
        });
    }

    // Keyboard Shortcut Handler (Ctrl + Enter)
    document.addEventListener('keydown', function(event) {
        if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
            event.preventDefault();
            submitCandidateForm(true);
        }
    });

    // Real-time Duplicate Candidate Checker
    function checkCandidateDuplicate(type, value) {
        if (!value || value.length < 4) return;

        const payload = {};
        payload[type] = value;
        payload['_token'] = '{{ csrf_token() }}';

        fetch('{{ route("admin.candidates.check_duplicate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            const bannerId = type === 'phone' ? 'duplicatePhoneBanner' : 'duplicateEmailBanner';
            const bannerEl = document.getElementById(bannerId);

            if (data.exists && data.candidate) {
                bannerEl.className = 'duplicate-alert-banner';
                bannerEl.innerHTML = `
                    <div>⚠️ <strong>Duplicate Warning:</strong> Candidate '${data.candidate.name}' already exists with this ${type}.</div>
                    <a href="${data.candidate.show_url}" target="_blank" style="color: #92400e; font-weight: 700; text-decoration: underline;">View Profile</a>
                `;
                bannerEl.style.display = 'flex';
            } else {
                bannerEl.style.display = 'none';
            }
        })
        .catch(err => console.error('Duplicate Check Error:', err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto focus Full Name on page load
        document.getElementById('inputName').focus();

        // Phone & Email Duplicate Check Listeners
        document.getElementById('inputPhone').addEventListener('blur', function() {
            checkCandidateDuplicate('phone', this.value);
        });
        document.getElementById('inputEmail').addEventListener('blur', function() {
            checkCandidateDuplicate('email', this.value);
        });
    });
</script>
@endsection
