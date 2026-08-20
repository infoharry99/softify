@extends('layouts.employee')

@section('title', 'Candidate Profile - ' . $candidate->name)
@section('page_title', 'Candidate Recruitment Profile: ' . $candidate->name)

@section('content')
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 25px;">
    <!-- Main Candidate Profile Details -->
    <div>
        <div class="card">
            <div class="card-header" style="background-color: #f0fdfa;">
                <div>
                    <h3 class="card-title" style="font-size: 1.25rem; font-weight: 700; color: #0f766e;">
                        👤 {{ $candidate->name }}
                    </h3>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                        Job Title: <strong style="color: #00a884;">💼 {{ $candidate->job_title ?? 'N/A' }}</strong> | Recruited for Client Company: <strong>🏢 {{ $candidate->company_name ?? 'General Talent Pool' }}</strong>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 600;">✏️ Edit Profile</a>
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">⬅️ Back to Directory</a>
                </div>
            </div>

            <div class="card-body">
                <!-- Contact & Basic Info -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; background: #f8fafc; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); margin-bottom: 25px;">
                    <div><strong>Email:</strong> {{ $candidate->email }}</div>
                    <div><strong>Phone:</strong> {{ $candidate->phone }}</div>
                    <div><strong>Location:</strong> {{ $candidate->location }}</div>
                    <div><strong>Job Type:</strong> <span class="badge badge-primary">{{ $candidate->job_type }}</span></div>
                    <div><strong>Notice Period:</strong> {{ $candidate->notice_period }}</div>
                    <div><strong>Registered Date:</strong> {{ $candidate->created_at->format('M d, Y') }}</div>
                </div>

                <!-- Professional & Compensation -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                    <div class="card" style="margin: 0;">
                        <div class="card-header"><h4 class="card-title">💼 Professional Qualifications</h4></div>
                        <div class="card-body">
                            <div style="margin-bottom: 15px;">
                                <strong>Experience:</strong>
                                <div style="font-size: 1.4rem; font-weight: 700; color: #0d9488; margin-top: 4px;">
                                    {{ $candidate->experience }} Years
                                </div>
                            </div>
                            <div>
                                <strong>Skills Matrix:</strong>
                                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px;">
                                    @foreach(explode(',', $candidate->skills) as $sk)
                                        <span class="badge badge-secondary" style="font-size: 0.8rem; padding: 6px 12px;">{{ trim($sk) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin: 0;">
                        <div class="card-header"><h4 class="card-title">💲 Compensation Details</h4></div>
                        <div class="card-body" style="font-size: 0.9rem; display: flex; flex-direction: column; gap: 12px;">
                            <div>
                                <span>Current CTC:</span>
                                <strong style="font-size: 1.1rem; float: right;">₹{{ number_format($candidate->current_ctc ?? 0) }}</strong>
                            </div>
                            <div>
                                <span>Expected CTC:</span>
                                <strong style="font-size: 1.2rem; color: #059669; float: right;">₹{{ number_format($candidate->expected_ctc ?? 0) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HR Evaluation Notes -->
                <div class="card">
                    <div class="card-header"><h4 class="card-title">📝 HR Internal Evaluation & Feedback</h4></div>
                    <div class="card-body">
                        <p style="font-size: 0.9rem; color: var(--text-main); line-height: 1.6;">
                            {{ $candidate->note ?? 'No specific evaluation notes added yet.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Pipeline Stage Manager & Resume -->
    <div>
        <!-- Pipeline Stage Manager Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📊 Recruitment Pipeline Stage</h3>
            </div>
            <div class="card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    @php
                        $badgeClass = match($candidate->status) {
                            'Applied' => 'badge-secondary',
                            'Screening' => 'badge-primary',
                            'Interview Scheduled' => 'badge-warning',
                            'Offered', 'Hired' => 'badge-success',
                            'Rejected' => 'badge-danger',
                            default => 'badge-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}" style="font-size: 1.1rem; padding: 10px 20px;">
                        Current Stage: {{ $candidate->status }}
                    </span>
                </div>

                @if(auth()->user()->hasPermission('hr.edit') || auth()->user()->hasRole('super-admin'))
                <form action="{{ route('admin.candidates.status', $candidate->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Update Recruitment Stage</label>
                        <select name="status" class="form-control" required>
                            <option value="Applied" {{ $candidate->status === 'Applied' ? 'selected' : '' }}>Applied</option>
                            <option value="Screening" {{ $candidate->status === 'Screening' ? 'selected' : '' }}>Screening</option>
                            <option value="Interview Scheduled" {{ $candidate->status === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="Offered" {{ $candidate->status === 'Offered' ? 'selected' : '' }}>Offered</option>
                            <option value="Hired" {{ $candidate->status === 'Hired' ? 'selected' : '' }}>Hired</option>
                            <option value="Rejected" {{ $candidate->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; background-color: #0d9488; border-color: #0d9488;">
                        Update Stage
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Dual Resume System Card -->
        <!-- 1. Original Resume (Read-Only) -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header" style="background: #f8fafc;">
                <h3 class="card-title" style="font-size: 0.95rem; font-weight: 700; color: #334155; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-lock" style="color: #64748b;"></i> 1. Original Candidate Resume
                </h3>
            </div>
            <div class="card-body" style="text-align: center; padding: 18px;">
                @if($candidate->resume)
                    <div style="font-size: 2rem; color: #ef4444; margin-bottom: 6px;"><i class="fa-solid fa-file-pdf"></i></div>
                    <div style="font-size: 0.78rem; color: #64748b; font-weight: 600; margin-bottom: 12px;">Original Read-Only Copy</div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="{{ route('admin.candidates.resume_preview', ['candidate' => $candidate->id, 'type' => 'original']) }}" target="_blank" class="btn btn-secondary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 600; color: #00a884;">
                            <i class="fa-solid fa-eye"></i> Preview Original
                        </a>
                        <a href="{{ route('admin.candidates.resume', ['candidate' => $candidate->id, 'type' => 'original']) }}" class="btn btn-primary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 700; background-color: #00a884; border-color: #00a884;">
                            <i class="fa-solid fa-download"></i> Download Original
                        </a>
                    </div>
                @else
                    <div style="color: #94a3b8; font-size: 0.82rem; padding: 10px;">No original resume attached.</div>
                @endif
            </div>
        </div>

        <!-- 2. Editable Copy Resume (Customized Version) -->
        <div class="card">
            <div class="card-header" style="background: #f0f9ff; border-bottom: 1px solid #bae6fd;">
                <h3 class="card-title" style="font-size: 0.95rem; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square"></i> 2. Editable Copy Resume
                </h3>
            </div>
            <div class="card-body" style="padding: 18px;">
                <!-- Live Interactive PDF Resume Editor Trigger -->
                <button type="button" onclick="openPdfResumeEditorModal()" class="btn btn-primary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: none; padding: 10px 14px; margin-bottom: 14px; box-shadow: 0 4px 6px -1px rgba(2,132,199,0.25); cursor: pointer;">
                    <i class="fa-solid fa-wand-magic-sparkles" style="color: #38bdf8;"></i> ✨ Open Live PDF Resume Editor
                </button>

                @if($candidate->edited_resume)
                    <div style="text-align: center; margin-bottom: 15px;">
                        <div style="font-size: 2rem; color: #0284c7; margin-bottom: 6px;"><i class="fa-solid fa-file-circle-check"></i></div>
                        <div style="font-size: 0.78rem; color: #0369a1; font-weight: 700;">Customized / Edited Version Active</div>
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                            <a href="{{ route('admin.candidates.resume_preview', ['candidate' => $candidate->id, 'type' => 'edited']) }}" target="_blank" class="btn btn-secondary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 600; color: #0284c7;">
                                <i class="fa-solid fa-eye"></i> Preview Edited Copy
                            </a>
                            <a href="{{ route('admin.candidates.resume', ['candidate' => $candidate->id, 'type' => 'edited']) }}" class="btn btn-primary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 700; background-color: #0284c7; border-color: #0284c7;">
                                <i class="fa-solid fa-download"></i> Download Edited Copy
                            </a>
                        </div>
                    </div>
                @else
                    <div style="color: #64748b; font-size: 0.82rem; margin-bottom: 12px; font-style: italic;">
                        No edited copy saved yet. Use the Live Editor above or upload a customized resume file below.
                    </div>
                @endif

                <!-- Upload / Update Form for Copy Resume -->
                <form action="{{ route('admin.candidates.edited_resume', $candidate->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 15px; border-top: 1px dashed #cbd5e1; padding-top: 14px;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 700; color: #334155;">
                            {{ $candidate->edited_resume ? 'Replace File Directly' : 'Or Upload File Copy' }} (PDF/DOC)
                        </label>
                        <input type="file" name="edited_resume_file" class="form-control" accept=".pdf,.doc,.docx" required style="font-size: 0.8rem; padding: 6px;">
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 700; color: #0284c7; border-color: #38bdf8;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> {{ $candidate->edited_resume ? 'Update Copy Resume File' : 'Upload Copy Resume File' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- HR Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ℹ️ System Meta</h3>
            </div>
            <div class="card-body" style="font-size: 0.85rem; display: flex; flex-direction: column; gap: 8px;">
                <div>Added By: <strong>{{ $candidate->hr->name ?? 'HR Administrator' }}</strong></div>
                <div>Last Updated: {{ $candidate->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- HTML2PDF CDN Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Live Resume PDF Editor Modal -->
<div id="pdfResumeEditorModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #ffffff; width: 98%; max-width: 1350px; height: 92vh; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); display: flex; flex-direction: column; overflow: hidden; border: 1px solid #e2e8f0;">
        
        <!-- Modal Header -->
        <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #0284c7;">
            <div>
                <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-wand-magic-sparkles" style="color: #38bdf8;"></i> Live PDF Resume Editor — {{ $candidate->name }}
                </h3>
                <div style="font-size: 0.78rem; opacity: 0.9; margin-top: 2px;">
                    Edit candidate resume details, customize layout for client submission, and save as copy resume directly to profile.
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" onclick="savePdfResumeToProfile()" id="saveResumeToProfileBtn" class="btn btn-sm" style="background-color: #00a884; color: #fff; font-weight: 800; padding: 8px 16px; border-radius: 8px; border: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.15); cursor: pointer;">
                    <i class="fa-solid fa-floppy-disk"></i> 💾 Save as Copy Resume to Profile
                </button>
                <button type="button" onclick="downloadPdfResumeLocal()" class="btn btn-sm" style="background-color: #ffffff; color: #0284c7; font-weight: 800; padding: 8px 16px; border-radius: 8px; border: none; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <i class="fa-solid fa-download"></i> 📥 Download PDF
                </button>
                <button type="button" onclick="closePdfResumeEditorModal()" style="background: rgba(255,255,255,0.2); border: none; color: #fff; font-size: 1.4rem; border-radius: 8px; width: 36px; height: 36px; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
            </div>
        </div>

        <!-- Modal Body (Split Editor Grid) -->
        <div style="display: grid; grid-template-columns: 460px 1fr; flex: 1; overflow: hidden; background: #f8fafc;">
            
            <!-- Left Side: Form Controls -->
            <div style="padding: 20px; overflow-y: auto; border-right: 1px solid #e2e8f0; background: #ffffff;">
                
                <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #38bdf8; padding-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-sliders" style="color: #0284c7;"></i> Editor Controls & Content
                </div>

                <!-- 1. Header & Anonymization Options -->
                <div style="background: #f0f9ff; padding: 14px; border-radius: 10px; border: 1px solid #bae6fd; margin-bottom: 16px;">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #0369a1; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-shield-halved"></i> Client Presentation & Branding
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label class="form-label" style="font-size: 0.76rem; font-weight: 700;">Header Title Banner</label>
                        <input type="text" id="pdf_header_banner" class="form-control" value="TALENTIFYY ENTERPRISE RECRUITMENT PORTAL — CANDIDATE DOSSIER" style="font-size: 0.78rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <input type="checkbox" id="pdf_anonymize_contact" style="width: 16px; height: 16px; cursor: pointer;" onchange="updateLivePdfPreview()">
                        <label for="pdf_anonymize_contact" style="font-size: 0.8rem; font-weight: 700; color: #1e293b; cursor: pointer; margin: 0;">
                            🔒 Anonymize Candidate (Hide Email & Phone for Client Sharing)
                        </label>
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label class="form-label" style="font-size: 0.76rem; font-weight: 700;">Watermark Stamp Text</label>
                        <input type="text" id="pdf_watermark_text" class="form-control" value="CONFIDENTIAL — TALENTIFYY CANDIDATE COPY" style="font-size: 0.78rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                    </div>
                </div>

                <!-- 2. Candidate Personal Details -->
                <div style="margin-bottom: 16px;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 8px;">👤 Candidate Basic Details</div>
                    
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label class="form-label" style="font-size: 0.76rem;">Full Name</label>
                        <input type="text" id="pdf_candidate_name" class="form-control" value="{{ $candidate->name }}" style="font-size: 0.8rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                    </div>

                    <div class="form-group" style="margin-bottom: 10px;">
                        <label class="form-label" style="font-size: 0.76rem;">Job Title / Role</label>
                        <input type="text" id="pdf_candidate_job_title" class="form-control" value="{{ $candidate->job_title ?? 'Professional Candidate' }}" style="font-size: 0.8rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label" style="font-size: 0.76rem;">Email Address</label>
                            <input type="email" id="pdf_candidate_email" class="form-control" value="{{ $candidate->email }}" style="font-size: 0.78rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label" style="font-size: 0.76rem;">Phone Number</label>
                            <input type="text" id="pdf_candidate_phone" class="form-control" value="{{ $candidate->phone }}" style="font-size: 0.78rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label" style="font-size: 0.76rem;">Location</label>
                            <input type="text" id="pdf_candidate_location" class="form-control" value="{{ $candidate->location }}" style="font-size: 0.78rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label" style="font-size: 0.76rem;">Experience (Years)</label>
                            <input type="text" id="pdf_candidate_experience" class="form-control" value="{{ $candidate->experience }} Years" style="font-size: 0.78rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                        </div>
                    </div>
                </div>

                <!-- 3. Key Skills Matrix -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 0.76rem; font-weight: 700; color: #475569;">💡 Key Skills (Comma Separated)</label>
                    <input type="text" id="pdf_candidate_skills" class="form-control" value="{{ $candidate->skills }}" style="font-size: 0.8rem; padding: 6px 10px;" oninput="updateLivePdfPreview()">
                </div>

                <!-- 4. Professional Summary -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 0.76rem; font-weight: 700; color: #475569;">📝 Professional Summary & Overview</label>
                    <textarea id="pdf_candidate_summary" class="form-control" rows="3" style="font-size: 0.8rem; padding: 8px;" oninput="updateLivePdfPreview()">Experienced {{ $candidate->job_title ?? 'professional' }} with {{ $candidate->experience }} years of hands-on expertise. Proven track record in core competencies: {{ $candidate->skills }}. Dedicated to delivering high-impact results for client organizations.</textarea>
                </div>

                <!-- 5. Work Experience & Key Achievements -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 0.76rem; font-weight: 700; color: #475569;">💼 Work Experience & Projects</label>
                    <textarea id="pdf_candidate_work" class="form-control" rows="4" style="font-size: 0.8rem; padding: 8px;" oninput="updateLivePdfPreview()">• Senior Professional Role — Core Technical & Strategic Execution
• Led project deliverables, team collaboration, and client milestones
• Implemented scalable solutions using {{ $candidate->skills }}
• Managed key performance indicators and quality metrics across project lifecycles</textarea>
                </div>

                <!-- 6. Education & Credentials -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 0.76rem; font-weight: 700; color: #475569;">🎓 Education & Professional Certifications</label>
                    <textarea id="pdf_candidate_education" class="form-control" rows="2" style="font-size: 0.8rem; padding: 8px;" oninput="updateLivePdfPreview()">• Bachelor's Degree / Higher Education in Relevant Discipline
• Verified Industry Certifications & Skill Credentials</textarea>
                </div>
            </div>

            <!-- Right Side: Real-Time Live Preview Canvas Area -->
            <div style="padding: 24px; overflow-y: auto; display: flex; flex-direction: column; align-items: center; background: #525659;">
                
                <div style="width: 100%; max-width: 800px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; color: #fff;">
                    <span style="font-size: 0.82rem; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-eye" style="color: #38bdf8;"></i> Real-Time Live PDF Document Canvas
                    </span>
                    <span style="font-size: 0.75rem; opacity: 0.8;">Standard A4 Paper Format</span>
                </div>

                <!-- A4 Resume Printable Container -->
                <div id="liveResumePrintArea" style="width: 794px; min-height: 1123px; background: #ffffff; padding: 40px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; position: relative; color: #1e293b; border-radius: 4px; box-sizing: border-box;">
                    
                    <!-- Watermark -->
                    <div id="preview_watermark" style="position: absolute; top: 40%; left: 5%; width: 90%; text-align: center; font-size: 2.2rem; font-weight: 900; color: rgba(226, 232, 240, 0.45); transform: rotate(-30deg); pointer-events: none; text-transform: uppercase; letter-spacing: 2px; line-height: 1.4;">
                        CONFIDENTIAL — TALENTIFYY CANDIDATE COPY
                    </div>

                    <!-- Header Banner -->
                    <div style="border-bottom: 3px solid #0284c7; padding-bottom: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <div id="preview_header_banner" style="font-size: 0.7rem; font-weight: 800; color: #0284c7; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 4px;">
                                TALENTIFYY ENTERPRISE RECRUITMENT PORTAL — CANDIDATE DOSSIER
                            </div>
                            <h1 id="preview_name" style="margin: 0; font-size: 1.8rem; font-weight: 800; color: #0f172a;">
                                {{ $candidate->name }}
                            </h1>
                            <div id="preview_job_title" style="font-size: 1rem; font-weight: 700; color: #0284c7; margin-top: 2px;">
                                {{ $candidate->job_title ?? 'Professional Candidate' }}
                            </div>
                        </div>
                        <div style="text-align: right; font-size: 0.76rem; color: #64748b; font-weight: 600;">
                            <div>ID: <strong>TAL-CAND-{{ sprintf('%04d', $candidate->id) }}</strong></div>
                            <div>Exp: <strong id="preview_experience">{{ $candidate->experience }} Years</strong></div>
                        </div>
                    </div>

                    <!-- Contact & Info Bar -->
                    <div id="preview_contact_bar" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 6px; margin-bottom: 24px; font-size: 0.8rem; display: flex; flex-wrap: wrap; gap: 16px; color: #334155;">
                        <span id="preview_email_wrap">📧 <span id="preview_email">{{ $candidate->email }}</span></span>
                        <span id="preview_phone_wrap">📞 <span id="preview_phone">{{ $candidate->phone }}</span></span>
                        <span>📍 <span id="preview_location">{{ $candidate->location }}</span></span>
                        <span>⏳ Notice: <strong>{{ $candidate->notice_period }}</strong></span>
                    </div>

                    <!-- Executive Summary Section -->
                    <div style="margin-bottom: 24px;">
                        <h3 style="font-size: 0.9rem; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 8px;">
                            Professional Summary
                        </h3>
                        <p id="preview_summary" style="font-size: 0.85rem; line-height: 1.6; color: #334155; margin: 0; white-space: pre-line;">
                        </p>
                    </div>

                    <!-- Skills Matrix -->
                    <div style="margin-bottom: 24px;">
                        <h3 style="font-size: 0.9rem; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px;">
                            Key Technical & Professional Skills
                        </h3>
                        <div id="preview_skills_container" style="display: flex; flex-wrap: wrap; gap: 6px;">
                        </div>
                    </div>

                    <!-- Work History -->
                    <div style="margin-bottom: 24px;">
                        <h3 style="font-size: 0.9rem; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 8px;">
                            Professional Experience & Key Deliverables
                        </h3>
                        <p id="preview_work" style="font-size: 0.85rem; line-height: 1.6; color: #334155; margin: 0; white-space: pre-line;">
                        </p>
                    </div>

                    <!-- Education -->
                    <div style="margin-bottom: 24px;">
                        <h3 style="font-size: 0.9rem; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 8px;">
                            Education & Credentials
                        </h3>
                        <p id="preview_education" style="font-size: 0.85rem; line-height: 1.6; color: #334155; margin: 0; white-space: pre-line;">
                        </p>
                    </div>

                    <!-- Footer -->
                    <div style="position: absolute; bottom: 30px; left: 40px; right: 40px; border-top: 1px solid #e2e8f0; padding-top: 8px; display: flex; justify-content: space-between; font-size: 0.7rem; color: #94a3b8;">
                        <div>Talentifyy Enterprise Portal — Verified Candidate Document</div>
                        <div>Generated: {{ date('M d, Y') }}</div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
function openPdfResumeEditorModal() {
    document.getElementById('pdfResumeEditorModal').style.display = 'flex';
    updateLivePdfPreview();
}

function closePdfResumeEditorModal() {
    document.getElementById('pdfResumeEditorModal').style.display = 'none';
}

function updateLivePdfPreview() {
    // 1. Header & Anonymization
    document.getElementById('preview_header_banner').innerText = document.getElementById('pdf_header_banner').value || 'TALENTIFYY ENTERPRISE RECRUITMENT PORTAL';
    document.getElementById('preview_watermark').innerText = document.getElementById('pdf_watermark_text').value || '';

    var isAnonymized = document.getElementById('pdf_anonymize_contact').checked;
    if (isAnonymized) {
        document.getElementById('preview_email_wrap').style.display = 'none';
        document.getElementById('preview_phone_wrap').style.display = 'none';
    } else {
        document.getElementById('preview_email_wrap').style.display = 'inline';
        document.getElementById('preview_phone_wrap').style.display = 'inline';
        document.getElementById('preview_email').innerText = document.getElementById('pdf_candidate_email').value;
        document.getElementById('preview_phone').innerText = document.getElementById('pdf_candidate_phone').value;
    }

    // 2. Personal Info
    document.getElementById('preview_name').innerText = document.getElementById('pdf_candidate_name').value || 'Candidate Name';
    document.getElementById('preview_job_title').innerText = document.getElementById('pdf_candidate_job_title').value || 'Professional Candidate';
    document.getElementById('preview_location').innerText = document.getElementById('pdf_candidate_location').value || 'Location N/A';
    document.getElementById('preview_experience').innerText = document.getElementById('pdf_candidate_experience').value || 'N/A';

    // 3. Skills Matrix Pills
    var skillsRaw = document.getElementById('pdf_candidate_skills').value || '';
    var skillsArr = skillsRaw.split(',');
    var skillsHtml = '';
    skillsArr.forEach(function(s) {
        var trimmed = s.trim();
        if (trimmed) {
            skillsHtml += '<span style="background: #e0f2fe; color: #0369a1; font-size: 0.76rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; border: 1px solid #bae6fd;">' + trimmed + '</span>';
        }
    });
    document.getElementById('preview_skills_container').innerHTML = skillsHtml;

    // 4. Content Textareas
    document.getElementById('preview_summary').innerText = document.getElementById('pdf_candidate_summary').value || '';
    document.getElementById('preview_work').innerText = document.getElementById('pdf_candidate_work').value || '';
    document.getElementById('preview_education').innerText = document.getElementById('pdf_candidate_education').value || '';
}

function generatePdfBlob(callback) {
    var element = document.getElementById('liveResumePrintArea');
    var opt = {
        margin:       0,
        filename:     'Resume_{{ Str::slug($candidate->name) }}_Edited.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, logging: false },
        jsPDF:        { unit: 'pt', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).outputPdf('datauristring').then(function(pdfBase64) {
        callback(pdfBase64);
    });
}

function downloadPdfResumeLocal() {
    var element = document.getElementById('liveResumePrintArea');
    var opt = {
        margin:       0,
        filename:     'Resume_{{ Str::slug($candidate->name) }}_Edited.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, logging: false },
        jsPDF:        { unit: 'pt', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}

function savePdfResumeToProfile() {
    var btn = document.getElementById('saveResumeToProfileBtn');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating & Saving PDF...';

    generatePdfBlob(function(base64Data) {
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('edited_resume_pdf_base64', base64Data);

        fetch('{{ route("admin.candidates.edited_resume", $candidate->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if (data.success) {
                Swal.fire({
                    title: '✅ Copy Resume Saved!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#00a884',
                    confirmButtonText: 'Great!'
                }).then(() => {
                    closePdfResumeEditorModal();
                    window.location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to save edited resume.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            console.error(err);
            Swal.fire('Error', 'An error occurred while saving the edited resume.', 'error');
        });
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePdfResumeEditorModal();
    }
});
</script>
@endsection
