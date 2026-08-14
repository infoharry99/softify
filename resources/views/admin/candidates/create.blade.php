@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Add New Candidate')
@section('page_title', 'Candidate Registration (ATS)')

@section('styles')
<style>
    .ats-header-banner {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
        color: #ffffff;
        padding: 25px 30px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);
    }
    .ats-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ats-header-subtitle {
        font-size: 0.875rem;
        color: #ccfbf1;
        margin-top: 4px;
    }
    .ats-progress-badge {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(4px);
        padding: 8px 18px;
        border-radius: 9999px;
        font-size: 1.1rem;
        font-weight: 700;
        color: #ffffff;
    }

    .ats-section-card {
        background: #ffffff;
        border: 1px solid #ccfbf1;
        border-radius: var(--radius);
        padding: 22px 25px;
        margin-bottom: 22px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    .ats-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f766e;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #e6fffa;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .ats-btn-submit {
        background-color: #0d9488;
        color: #ffffff;
        font-weight: 700;
        padding: 12px 28px;
        font-size: 0.95rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .ats-btn-submit:hover { background-color: #0f766e; }

    .ats-btn-cancel {
        background-color: #475569;
        color: #ffffff;
        font-weight: 600;
        padding: 12px 24px;
        font-size: 0.95rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .ats-btn-cancel:hover { background-color: #334155; }

    .resume-dropzone {
        border: 2px dashed #99f6e4;
        background-color: #f0fdfa;
        padding: 30px;
        text-align: center;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .resume-dropzone:hover {
        border-color: #0d9488;
        background-color: #ccfbf1;
    }
</style>
@endsection

@section('content')
<!-- Header Banner -->
<div class="ats-header-banner">
    <div>
        <div class="ats-header-title">
            👤+ Add New Candidate
        </div>
        <div class="ats-header-subtitle">
            Fill in the details to register a new candidate for client hiring companies. (Candidates do not log in to the system)
        </div>
    </div>
    <div class="ats-progress-badge">
        100% Form
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <ul style="margin-left: 20px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- 1. Personal Information Section -->
    <div class="ats-section-card">
        <div class="ats-section-title">
            👤 Personal Information
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. rahul@example.com">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="e.g. +91 9876543210">
            </div>

            <div class="form-group">
                <label class="form-label">Location *</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}" required placeholder="e.g. Mumbai / Bangalore / Remote">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Hiring Company Name (Client Company) *</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="e.g. Nextecki Technologies / Infosys / Client Corp">
            <span style="font-size: 0.75rem; color: var(--text-muted);">Specify the client hiring company this candidate is being recruited for.</span>
        </div>
    </div>

    <!-- 2. Professional Information Section -->
    <div class="ats-section-card">
        <div class="ats-section-title">
            💼 Professional Information
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Skills * (comma separated)</label>
                <input type="text" name="skills" class="form-control" value="{{ old('skills') }}" required placeholder="e.g. PHP, JavaScript, React, Laravel, MySQL">
            </div>

            <div class="form-group">
                <label class="form-label">Experience (Years) *</label>
                <input type="number" step="0.5" min="0" name="experience" class="form-control" value="{{ old('experience', 2) }}" required placeholder="e.g. 3.5">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Job Type *</label>
                <select name="job_type" class="form-control" required>
                    <option value="Full Time" {{ old('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                    <option value="Part Time" {{ old('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                    <option value="Contract" {{ old('job_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Remote" {{ old('job_type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                    <option value="Hybrid" {{ old('job_type') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Notice Period *</label>
                <select name="notice_period" class="form-control" required>
                    <option value="Immediate" {{ old('notice_period') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                    <option value="15 Days" {{ old('notice_period') === '15 Days' ? 'selected' : '' }}>15 Days</option>
                    <option value="30 Days" {{ old('notice_period') === '30 Days' ? 'selected' : '' }}>30 Days</option>
                    <option value="60 Days" {{ old('notice_period') === '60 Days' ? 'selected' : '' }}>60 Days</option>
                    <option value="90 Days" {{ old('notice_period') === '90 Days' ? 'selected' : '' }}>90 Days</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Recruitment Status / Pipeline Stage *</label>
            <select name="status" class="form-control" required>
                <option value="Applied" {{ old('status') === 'Applied' ? 'selected' : '' }}>Applied</option>
                <option value="Screening" {{ old('status') === 'Screening' ? 'selected' : '' }}>Screening</option>
                <option value="Interview Scheduled" {{ old('status') === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                <option value="Offered" {{ old('status') === 'Offered' ? 'selected' : '' }}>Offered</option>
                <option value="Hired" {{ old('status') === 'Hired' ? 'selected' : '' }}>Hired</option>
                <option value="Rejected" {{ old('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </div>

    <!-- 3. Compensation Details Section -->
    <div class="ats-section-card">
        <div class="ats-section-title">
            💲 Compensation Details
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Current CTC (Annual ₹) *</label>
                <input type="number" step="1000" name="current_ctc" class="form-control" value="{{ old('current_ctc') }}" placeholder="e.g. 500000">
            </div>

            <div class="form-group">
                <label class="form-label">Expected CTC (Annual ₹) *</label>
                <input type="number" step="1000" name="expected_ctc" class="form-control" value="{{ old('expected_ctc') }}" placeholder="e.g. 700000">
            </div>
        </div>
    </div>

    <!-- 4. Resume Upload Section -->
    <div class="ats-section-card">
        <div class="ats-section-title">
            📄 Resume Upload
        </div>
        <div class="form-group">
            <div class="resume-dropzone" onclick="document.getElementById('resume_file_input').click();">
                <div style="font-size: 2.2rem; color: #0d9488; margin-bottom: 8px;">📤</div>
                <strong style="color: #0f766e; font-size: 0.95rem;">Click to upload resume or drag and drop</strong>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">PDF, DOC, DOCX (Max 5MB)</div>
                <input type="file" name="resume_file" id="resume_file_input" style="display: none;" accept=".pdf,.doc,.docx" onchange="document.getElementById('file_selected_name').innerText = 'Selected File: ' + this.files[0].name;">
            </div>
            <div id="file_selected_name" style="margin-top: 10px; font-weight: 600; color: #0d9488; text-align: center;"></div>
        </div>

        <div class="form-group">
            <label class="form-label">HR Notes / Evaluation Remarks</label>
            <textarea name="note" class="form-control" rows="3" placeholder="Internal HR feedback or interview comments">{{ old('note') }}</textarea>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 15px; justify-content: center; margin-top: 25px; margin-bottom: 40px;">
        <button type="submit" class="ats-btn-submit">
            ➕ ADD CANDIDATE
        </button>
        <a href="{{ route('admin.candidates.index') }}" class="ats-btn-cancel">
            ⬅ CANCEL
        </a>
    </div>
</form>
@endsection
