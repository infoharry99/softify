@extends('layouts.employee')

@section('title', 'Add New Candidate')
@section('page_title', 'Candidate Registration (ATS)')

@section('styles')
<style>
    .ats-banner {
        background: linear-gradient(135deg, #00a884 0%, #008f70 100%);
        color: #ffffff;
        padding: 20px 25px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.2);
    }
    .ats-title {
        font-size: 1.35rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ats-subtitle {
        font-size: 0.84rem;
        color: #e6f7f3;
        margin-top: 3px;
    }

    .compact-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 25px 30px;
        box-shadow: var(--shadow);
    }

    .compact-section-divider {
        font-size: 0.95rem;
        font-weight: 700;
        color: #00a884;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        margin-bottom: 18px;
        border-bottom: 2px solid #e6f7f3;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .compact-dropzone {
        border: 1.5px dashed #9ee5d4;
        background-color: #f0faf7;
        padding: 10px 14px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .compact-dropzone:hover {
        border-color: #00a884;
        background-color: #e6f7f3;
    }

    .field-error-msg {
        font-size: 0.78rem;
        font-weight: 600;
        color: #ef4444;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .is-invalid-input {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    @media (max-width: 850px) {
        .form-grid-3, .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Header Banner -->
<div class="ats-banner">
    <div>
        <div class="ats-title">
            <i class="fa-solid fa-user-plus"></i> Add New Candidate
        </div>
        <div class="ats-subtitle">
            Register a candidate for client hiring companies (Candidates do not log in to the portal).
        </div>
    </div>
    <div>
        <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Candidate Directory
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px; background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px;">
        <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i> Please resolve the following validation errors:
        </div>
        <ul style="margin-left: 24px; font-size: 0.88rem;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Compact Form Card -->
<div class="compact-card">
    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. Personal Information -->
        <div class="compact-section-divider">
            <i class="fa-solid fa-user"></i> Personal & Contact Information
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid-input @enderror" value="{{ old('name') }}" required minlength="2" maxlength="255" placeholder="e.g. Rahul Sharma">
                @error('name')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid-input @enderror" value="{{ old('email') }}" required maxlength="255" placeholder="e.g. rahul@example.com">
                @error('email')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid-input @enderror" value="{{ old('phone') }}" required pattern="^(\+91[\-\s]?)?[6789]\d{9}$" maxlength="13" title="Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210)" placeholder="e.g. 9876543210">
                @error('phone')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Location *</label>
                <input type="text" name="location" class="form-control @error('location') is-invalid-input @enderror" value="{{ old('location') }}" required placeholder="e.g. Mumbai / Bangalore / Remote">
                @error('location')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Hiring Company (Client) *</label>
                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid-input @enderror" value="{{ old('company_name') }}" required placeholder="e.g. Nextecki / Infosys / Client Corp">
                @error('company_name')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 2. Professional & Recruitment Details -->
        <div class="compact-section-divider" style="margin-top: 25px;">
            <i class="fa-solid fa-briefcase"></i> Professional & Recruitment Details
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label class="form-label">Job Title / Designation</label>
                <input type="text" name="job_title" class="form-control @error('job_title') is-invalid-input @enderror" value="{{ old('job_title') }}" placeholder="e.g. Senior Python Developer">
            </div>

            <div class="form-group">
                <label class="form-label">Key Skills *</label>
                <input type="text" name="skills" class="form-control @error('skills') is-invalid-input @enderror" value="{{ old('skills') }}" required minlength="2" placeholder="e.g. PHP, React, Laravel, MySQL">
                @error('skills')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Experience (Years) *</label>
                <input type="number" step="0.5" min="0" max="50" name="experience" class="form-control @error('experience') is-invalid-input @enderror" value="{{ old('experience', 2) }}" required placeholder="e.g. 3.5">
                @error('experience')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Job Type *</label>
                <select name="job_type" class="form-control @error('job_type') is-invalid-input @enderror" required>
                    <option value="Full Time" {{ old('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                    <option value="Part Time" {{ old('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                    <option value="Contract" {{ old('job_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Remote" {{ old('job_type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                    <option value="Hybrid" {{ old('job_type') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
                @error('job_type')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label class="form-label">Notice Period *</label>
                <select name="notice_period" class="form-control @error('notice_period') is-invalid-input @enderror" required>
                    <option value="Immediate" {{ old('notice_period') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                    <option value="15 Days" {{ old('notice_period') === '15 Days' ? 'selected' : '' }}>15 Days</option>
                    <option value="30 Days" {{ old('notice_period') === '30 Days' ? 'selected' : '' }}>30 Days</option>
                    <option value="60 Days" {{ old('notice_period') === '60 Days' ? 'selected' : '' }}>60 Days</option>
                    <option value="90 Days" {{ old('notice_period') === '90 Days' ? 'selected' : '' }}>90 Days</option>
                </select>
                @error('notice_period')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Current CTC (Annual ₹)</label>
                <input type="number" step="1000" min="0" name="current_ctc" class="form-control @error('current_ctc') is-invalid-input @enderror" value="{{ old('current_ctc') }}" placeholder="e.g. 500000">
                @error('current_ctc')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Expected CTC (Annual ₹)</label>
                <input type="number" step="1000" min="0" name="expected_ctc" class="form-control @error('expected_ctc') is-invalid-input @enderror" value="{{ old('expected_ctc') }}" placeholder="e.g. 700000">
                @error('expected_ctc')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Pipeline Stage / Status *</label>
                <select name="status" class="form-control @error('status') is-invalid-input @enderror" required>
                    <option value="Applied" {{ old('status') === 'Applied' ? 'selected' : '' }}>Applied</option>
                    <option value="Screening" {{ old('status') === 'Screening' ? 'selected' : '' }}>Screening</option>
                    <option value="Interview Scheduled" {{ old('status') === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                    <option value="Offered" {{ old('status') === 'Offered' ? 'selected' : '' }}>Offered</option>
                    <option value="Hired" {{ old('status') === 'Hired' ? 'selected' : '' }}>Hired</option>
                    <option value="Rejected" {{ old('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Resume Attachment (PDF, DOCX Max 5MB)</label>
                <div class="compact-dropzone @error('resume_file') is-invalid-input @enderror" onclick="document.getElementById('resume_file_input').click();">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 1.4rem; color: #00a884;"></i>
                    <div style="flex: 1;">
                        <div id="file_selected_name" style="font-size: 0.84rem; font-weight: 600; color: #0f172a;">Click to upload resume (Max 5MB)</div>
                    </div>
                    <input type="file" name="resume_file" id="resume_file_input" style="display: none;" accept=".pdf,.doc,.docx" onchange="document.getElementById('file_selected_name').innerText = this.files[0].name;">
                </div>
                @error('resume_file')
                    <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 3. Remarks -->
        <div class="form-group" style="margin-top: 10px;">
            <label class="form-label">HR Evaluation Notes / Remarks</label>
            <textarea name="note" class="form-control @error('note') is-invalid-input @enderror" rows="2" maxlength="2000" placeholder="Internal HR feedback or interview comments">{{ old('note') }}</textarea>
            @error('note')
                <span class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
            @enderror
        </div>

        <!-- Action Footer -->
        <div style="margin-top: 25px; pt: 15px; border-top: 1px solid var(--border-color); display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary" style="background-color: #00a884; border-color: #00a884; padding: 10px 24px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Candidate
            </button>
        </div>
    </form>
</div>
@endsection
