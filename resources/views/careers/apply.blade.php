<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Application | Talentifyy Enterprise</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />

    <style>
        :root {
            --primary: #00a884;
            --primary-dark: #008f70;
            --primary-light: #e6f7f3;
            --primary-border: #9ee5d4;
            --navy: #0f172a;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
            --bg-page: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 168, 132, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(15, 23, 42, 0.04) 0px, transparent 50%);
            min-height: 100vh;
            color: var(--text-main);
            padding: 24px 16px 48px 16px;
        }

        .apply-container {
            max-width: 860px;
            margin: 0 auto;
        }

        /* Top Brand Header */
        .brand-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-logo-img {
            height: 64px;
            width: auto;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .header-title-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--primary-border);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 10px;
            box-shadow: 0 2px 6px rgba(0, 168, 132, 0.08);
        }

        .page-main-heading {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .page-subheading {
            font-size: 0.95rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.5;
        }

        /* Form Card */
        .form-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 20px 25px -5px rgba(0, 168, 132, 0.04);
        }

        /* Section Headings */
        .section-header {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-light);
        }

        .section-header i {
            font-size: 1.1rem;
        }

        /* Form Grids */
        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 20px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
        }

        .form-label .req {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            font-size: 0.92rem;
            color: var(--navy);
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
        }

        .form-control.is-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .field-error {
            font-size: 0.78rem;
            font-weight: 600;
            color: #ef4444;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Resume Upload Dropzone */
        .dropzone-box {
            border: 2px dashed #9ee5d4;
            background: #f0faf7;
            border-radius: 14px;
            padding: 24px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .dropzone-box:hover, .dropzone-box.dragover {
            border-color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .dropzone-icon {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .dropzone-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 4px;
        }

        .dropzone-subtitle {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .selected-file-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--primary-border);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            margin-top: 10px;
        }

        /* Source Badge Notice */
        .source-notice-bar {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.84rem;
            color: #475569;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .source-tag {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 700;
            color: #0f172a;
            font-size: 0.78rem;
            text-transform: capitalize;
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #00a884 0%, #008f70 100%);
            color: #ffffff;
            border: none;
            padding: 16px 36px;
            font-size: 1.05rem;
            font-weight: 800;
            border-radius: 14px;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 18px rgba(0, 168, 132, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 168, 132, 0.45);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            body {
                padding: 16px 10px 32px 10px;
            }
            .form-card {
                padding: 24px 18px;
                border-radius: 16px;
            }
            .form-grid-2, .form-grid-3 {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .page-main-heading {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="apply-container">

    <!-- Top Branding -->
    <header class="brand-header">
        <img src="{{ asset('images/logo.png') }}" alt="Talentifyy Enterprise" class="brand-logo-img">
        <div>
            <span class="header-title-pill">
                <i class="fa-solid fa-briefcase"></i> Talentifyy Recruitment Portal
            </span>
        </div>
        <h1 class="page-main-heading">Candidate Application Form</h1>
        <p class="page-subheading">
            Submit your profile details and resume directly to our recruitment team for current and upcoming opportunities.
        </p>
    </header>

    @if($errors->any())
        <div style="background-color: #fef2f2; border: 1.5px solid #fecaca; color: #991b1b; padding: 16px 20px; border-radius: 14px; margin-bottom: 22px;">
            <div style="font-weight: 800; font-size: 0.95rem; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i> Please correct the highlighted issues:
            </div>
            <ul style="margin-left: 24px; font-size: 0.88rem; line-height: 1.6;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Application Form Card -->
    <main class="form-card">

        @if($source && $source !== 'direct')
            <div class="source-notice-bar">
                <span>
                    <i class="fa-solid fa-link" style="color: var(--primary);"></i> Applying via Referral / Social Channel:
                </span>
                <span class="source-tag">
                    @if($source === 'linkedin')
                        <i class="fa-brands fa-linkedin" style="color: #0077b5;"></i> LinkedIn
                    @elseif($source === 'whatsapp')
                        <i class="fa-brands fa-whatsapp" style="color: #25d366;"></i> WhatsApp
                    @elseif($source === 'instagram')
                        <i class="fa-brands fa-instagram" style="color: #e1306c;"></i> Instagram
                    @else
                        {{ ucfirst($source) }}
                    @endif
                </span>
            </div>
        @endif

        <form action="{{ route('careers.store') }}" method="POST" enctype="multipart/form-data" id="publicCandidateForm" onsubmit="handleFormSubmit()">
            @csrf

            <!-- Hidden Honeypot spam prevention fields -->
            <input type="text" name="website_url_check" style="display: none !important;" tabindex="-1" autocomplete="off">
            <input type="hidden" name="source" value="{{ $source }}">

            <!-- ==============================================
                 SECTION 1: PERSONAL & CONTACT INFORMATION
                 ============================================== -->
            <div class="section-header">
                <i class="fa-solid fa-user"></i>
                <span>Personal & Contact Information</span>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="candidate_name">Full Name <span class="req">*</span></label>
                    <input type="text" 
                           id="candidate_name" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           required 
                           minlength="2" 
                           maxlength="255" 
                           placeholder="e.g. Rahul Sharma"
                           autocomplete="name">
                    @error('name')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_email">Email Address <span class="req">*</span></label>
                    <input type="email" 
                           id="candidate_email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           required 
                           maxlength="255" 
                           placeholder="e.g. rahul.sharma@example.com"
                           autocomplete="email">
                    @error('email')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="candidate_phone">Mobile / WhatsApp Number <span class="req">*</span></label>
                    <input type="tel" 
                           id="candidate_phone" 
                           name="phone" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone') }}" 
                           required 
                           pattern="^(\+91[\-\s]?)?[6789]\d{9}$" 
                           maxlength="13" 
                           placeholder="e.g. 9876543210 or +919876543210"
                           title="Please enter a valid 10-digit Indian mobile number"
                           autocomplete="tel">
                    @error('phone')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_location">Current Location / City <span class="req">*</span></label>
                    <input type="text" 
                           id="candidate_location" 
                           name="location" 
                           class="form-control @error('location') is-invalid @enderror" 
                           value="{{ old('location') }}" 
                           required 
                           placeholder="e.g. Mumbai, Bengaluru, Delhi, Pune, Remote"
                           autocomplete="address-level2">
                    @error('location')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>


            <!-- ==============================================
                 SECTION 2: PROFESSIONAL & RECRUITMENT DETAILS
                 ============================================== -->
            <div class="section-header" style="margin-top: 14px;">
                <i class="fa-solid fa-briefcase"></i>
                <span>Professional & Technical Details</span>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label" for="candidate_job_title">Current Job Title / Designation</label>
                    <input type="text" 
                           id="candidate_job_title" 
                           name="job_title" 
                           class="form-control @error('job_title') is-invalid @enderror" 
                           value="{{ old('job_title', $defaultJobTitle) }}" 
                           placeholder="e.g. Senior Python Developer">
                    @error('job_title')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_skills">Key Skills <span class="req">*</span></label>
                    <input type="text" 
                           id="candidate_skills" 
                           name="skills" 
                           class="form-control @error('skills') is-invalid @enderror" 
                           value="{{ old('skills') }}" 
                           required 
                           minlength="2" 
                           placeholder="e.g. Java, Spring Boot, Microservices, MySQL">
                    @error('skills')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_experience">Total Experience (Years) <span class="req">*</span></label>
                    <input type="number" 
                           step="0.5" 
                           min="0" 
                           max="50" 
                           id="candidate_experience" 
                           name="experience" 
                           class="form-control @error('experience') is-invalid @enderror" 
                           value="{{ old('experience', 2) }}" 
                           required 
                           placeholder="e.g. 3.5 (0 for Fresher)">
                    @error('experience')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="candidate_job_type">Preferred Work Mode <span class="req">*</span></label>
                    <select id="candidate_job_type" name="job_type" class="form-control @error('job_type') is-invalid @enderror" required>
                        @foreach($jobTypes as $jt)
                            <option value="{{ $jt }}" {{ old('job_type', 'Full Time') === $jt ? 'selected' : '' }}>
                                {{ $jt }}
                            </option>
                        @endforeach
                    </select>
                    @error('job_type')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_notice_period">Notice Period <span class="req">*</span></label>
                    <select id="candidate_notice_period" name="notice_period" class="form-control @error('notice_period') is-invalid @enderror" required>
                        @foreach($noticePeriods as $np)
                            <option value="{{ $np }}" {{ old('notice_period', 'Immediate') === $np ? 'selected' : '' }}>
                                {{ $np }}
                            </option>
                        @endforeach
                    </select>
                    @error('notice_period')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="candidate_current_ctc">Current Annual CTC (INR ₹)</label>
                    <input type="number" 
                           step="1000" 
                           min="0" 
                           id="candidate_current_ctc" 
                           name="current_ctc" 
                           class="form-control @error('current_ctc') is-invalid @enderror" 
                           value="{{ old('current_ctc') }}" 
                           placeholder="e.g. 500000">
                    @error('current_ctc')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_expected_ctc">Expected Annual CTC (INR ₹)</label>
                    <input type="number" 
                           step="1000" 
                           min="0" 
                           id="candidate_expected_ctc" 
                           name="expected_ctc" 
                           class="form-control @error('expected_ctc') is-invalid @enderror" 
                           value="{{ old('expected_ctc') }}" 
                           placeholder="e.g. 700000">
                    @error('expected_ctc')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>


            <!-- ==============================================
                 SECTION 3: RESUME & ADDITIONAL PROFILES
                 ============================================== -->
            <div class="section-header" style="margin-top: 14px;">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Resume & Profiles</span>
            </div>

            <!-- Resume Upload Dropzone -->
            <div class="form-group">
                <label class="form-label">Attach Resume / CV (PDF, DOCX Max 5MB)</label>
                <div class="dropzone-box" id="dropzoneBox" onclick="document.getElementById('resumeFileInput').click();">
                    <i class="fa-solid fa-cloud-arrow-up dropzone-icon"></i>
                    <div class="dropzone-title">Click to upload or drag & drop your resume</div>
                    <div class="dropzone-subtitle">Supported formats: .pdf, .doc, .docx (Maximum file size: 5 MB)</div>
                    <div id="fileSelectedDisplay" style="display: none;">
                        <span class="selected-file-pill">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span id="selectedFileName">resume.pdf</span>
                            <i class="fa-solid fa-circle-check" style="color: #047857;"></i>
                        </span>
                    </div>
                </div>
                <input type="file" 
                       id="resumeFileInput" 
                       name="resume_file" 
                       accept=".pdf,.doc,.docx" 
                       style="display: none;" 
                       onchange="handleResumeSelected(this)">
                @error('resume_file')
                    <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid-2" style="margin-top: 10px;">
                <div class="form-group">
                    <label class="form-label" for="candidate_linkedin">LinkedIn Profile URL</label>
                    <input type="url" 
                           id="candidate_linkedin" 
                           name="linkedin_url" 
                           class="form-control @error('linkedin_url') is-invalid @enderror" 
                           value="{{ old('linkedin_url') }}" 
                           placeholder="https://linkedin.com/in/username">
                    @error('linkedin_url')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="candidate_portfolio">Portfolio / GitHub / Website</label>
                    <input type="url" 
                           id="candidate_portfolio" 
                           name="portfolio_url" 
                           class="form-control @error('portfolio_url') is-invalid @enderror" 
                           value="{{ old('portfolio_url') }}" 
                           placeholder="https://github.com/username">
                    @error('portfolio_url')
                        <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="candidate_cover_note">Short Note / Summary for Recruiter</label>
                <textarea id="candidate_cover_note" 
                          name="cover_note" 
                          class="form-control @error('cover_note') is-invalid @enderror" 
                          rows="3" 
                          maxlength="2000" 
                          placeholder="Briefly highlight your core strengths, availability to join, or any specific job roles you are targeting...">{{ old('cover_note') }}</textarea>
                @error('cover_note')
                    <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div style="margin-top: 28px;">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit Candidate Profile</span>
                </button>
            </div>

            <div style="text-align: center; margin-top: 16px; font-size: 0.8rem; color: #94a3b8;">
                <i class="fa-solid fa-shield-halved"></i> Your information is safely transmitted directly to the Talentifyy Enterprise recruitment database.
            </div>

        </form>
    </main>

    <!-- Footer -->
    <footer style="text-align: center; margin-top: 28px; font-size: 0.85rem; color: #64748b;">
        &copy; {{ date('Y') }} Talentifyy Enterprise Portal. All rights reserved.
    </footer>

</div>

<script>
    // File selection UI handler
    function handleResumeSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxBytes = 5 * 1024 * 1024; // 5MB

            if (file.size > maxBytes) {
                alert('File is too large! Please choose a resume under 5MB.');
                input.value = '';
                document.getElementById('fileSelectedDisplay').style.display = 'none';
                return;
            }

            document.getElementById('selectedFileName').innerText = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            document.getElementById('fileSelectedDisplay').style.display = 'block';
        }
    }

    // Drag and Drop functionality
    const dropzone = document.getElementById('dropzoneBox');
    const fileInput = document.getElementById('resumeFileInput');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
        }, false);
    });

    dropzone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            fileInput.files = files;
            handleResumeSelected(fileInput);
        }
    });

    // Form submit state
    function handleFormSubmit() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting Your Application...';
    }
</script>

</body>
</html>
