@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

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
                    @if(auth()->user()->hasPermission('hr.edit') || auth()->user()->hasRole('super-admin'))
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

        <!-- Resume File Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📄 Candidate Resume</h3>
            </div>
            <div class="card-body" style="text-align: center;">
                @if($candidate->resume)
                    <div style="font-size: 2.5rem; color: #0d9488; margin-bottom: 10px;">📄</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px;">Resume Document Uploaded</div>
                    <a href="{{ route('admin.candidates.resume', $candidate->id) }}" class="btn btn-primary" style="width: 100%; background-color: #0d9488; border-color: #0d9488;">
                        📥 Download Resume
                    </a>
                @else
                    <div style="color: var(--text-muted); font-size: 0.85rem; padding: 15px;">No resume file attached.</div>
                @endif
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
