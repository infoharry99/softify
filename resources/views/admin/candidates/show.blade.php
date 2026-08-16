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
                        Recruited for Client Company: <strong>🏢 {{ $candidate->company_name ?? 'General Talent Pool' }}</strong>
                    </div>
                </div>
                <div>
                    @if((auth()->user()->hasPermission('hr.edit') || auth()->user()->hasRole('super-admin')) && !auth()->user()->hasRole('talent-acquisition'))
                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-secondary btn-sm">✏️ Edit Profile</a>
                    @endif
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm">⬅️ Back to Directory</a>
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
                        No edited copy uploaded yet. You can upload a customized/formatted resume for client sharing below.
                    </div>
                @endif

                <!-- Upload / Update Form for Copy Resume -->
                <form action="{{ route('admin.candidates.edited_resume', $candidate->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 15px; border-top: 1px dashed #cbd5e1; padding-top: 14px;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 700; color: #334155;">
                            {{ $candidate->edited_resume ? 'Replace Edited Copy' : 'Upload Edited Copy Resume' }} (PDF/DOC)
                        </label>
                        <input type="file" name="edited_resume_file" class="form-control" accept=".pdf,.doc,.docx" required style="font-size: 0.8rem; padding: 6px;">
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; border-radius: 8px; font-weight: 700; color: #0284c7; border-color: #38bdf8;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> {{ $candidate->edited_resume ? 'Update Copy Resume' : 'Save Copy Resume' }}
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
@endsection
