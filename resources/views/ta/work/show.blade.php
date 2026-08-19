@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('ta-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Job Requirement Details')
@section('page_title', 'TA Job Requisition Specs & Sourcing Progress')

@section('content')
<div style="margin-bottom: 15px;">
    <a href="{{ route('ta.work.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Requisitions Directory
    </a>
</div>

<!-- Header Details Card -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 10px; color:#00a884; font-size:1.3rem;">
                <i class="fa-solid fa-briefcase"></i> {{ $task->job_title }}
            </h3>
            <div style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">
                Assigned Date: <strong>{{ $task->assigned_date->format('l, M d, Y') }}</strong> | Assigned To: <strong>{{ $task->assignee->name ?? '' }}</strong>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            @if($task->status === 'Done')
                <span class="badge badge-success" style="font-size: 0.95rem; padding: 6px 14px;"><i class="fa-solid fa-circle-check"></i> Status: Done</span>
            @elseif($task->status === 'In Progress')
                <span class="badge badge-warning" style="background:#fff7ed; color:#c2410c; border:1px solid #ffedd5; font-size:0.95rem; padding:6px 14px;"><i class="fa-solid fa-spinner"></i> Status: In Progress</span>
            @else
                <span class="badge badge-secondary" style="font-size: 0.95rem; padding: 6px 14px;"><i class="fa-solid fa-clock"></i> Status: Pending</span>
            @endif

            @if($isLead)
            <button type="button" onclick="openEditTaModal()" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 700;">
                <i class="fa-solid fa-pen-to-square"></i> Edit Requisition Specs
            </button>
            @endif

            <button type="button" onclick="openUpdateModal()" class="btn btn-primary btn-sm" style="border-radius: 8px; font-weight: 700;">
                <i class="fa-solid fa-circle-check"></i> {{ $isLead ? 'Review & Update Status' : 'Update Sourced Profiles' }}
            </button>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 25px;">
    <!-- Left Column: Job Description Specification (Matching Screenshot Format) -->
    <div>
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header" style="background: #1e293b; color: #ffffff; border-radius: 12px 12px 0 0;">
                <h3 class="card-title" style="color: #ffffff; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-file-lines" style="color:#00a884;"></i> Job Description & Requisition Specification
                </h3>
            </div>
            <div class="card-body" style="background: #0f172a; color: #f8fafc; padding: 25px; border-radius: 0 0 12px 12px; font-family: sans-serif; line-height: 1.6;">
                <div style="font-size: 1.05rem; font-weight: 700; color: #ffffff; margin-bottom: 4px;">
                    Job title: <span style="color: #38bdf8;">{{ $task->job_title }}</span>
                </div>
                <div style="font-size: 1rem; color: #cbd5e1;">Location: <strong>{{ $task->location }}</strong></div>
                <div style="font-size: 1rem; color: #cbd5e1;">Experience: <strong>{{ $task->experience ?? 'N/A' }}</strong></div>
                <div style="font-size: 1rem; color: #cbd5e1; margin-bottom: 15px;">Budget: <strong>{{ $task->budget ?? 'N/A' }}</strong></div>

                <div style="font-size: 1rem; color: #cbd5e1; margin-bottom: 15px;">Duration: <strong>{{ $task->duration ?? 'Full Time' }}</strong></div>

                <div style="font-size: 1.05rem; font-weight: 700; color: #ffffff; border-top: 1px solid #334155; padding-top: 12px; margin-bottom: 10px;">
                    Job Description:
                </div>

                <div style="font-size: 0.95rem; color: #e2e8f0; white-space: pre-line; padding-left: 5px;">
                    {!! nl2br(e($task->job_description)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Sourcing Progress & Dual-Side Notes -->
    <div>
        <!-- Sourcing Progress Matrix -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-chart-line" style="color: #00a884;"></i> Sourcing Progress</h3>
            </div>
            <div class="card-body">
                @php
                    $pct = $task->target_profiles > 0 ? min(100, round(($task->profiles_sourced / $task->target_profiles) * 100)) : 0;
                @endphp
                <div style="display:flex; justify-content:space-between; font-weight:700; margin-bottom:6px; font-size:0.95rem;">
                    <span>Profiles Sourced</span>
                    <span style="color:#00a884;">{{ $task->profiles_sourced }} / {{ $task->target_profiles }} Target ({{ $pct }}%)</span>
                </div>
                <div style="background:#e2e8f0; height:12px; border-radius:6px; overflow:hidden; margin-bottom:15px;">
                    <div style="background:#00a884; height:100%; width: {{ $pct }}%; transition: width 0.3s ease;"></div>
                </div>

                <div style="font-size:0.82rem; color:#64748b;">
                    <strong>Assigned By:</strong> {{ $task->assigner->name ?? 'Admin' }}<br>
                    <strong>Assigned To:</strong> {{ $task->assignee->name ?? 'Unassigned' }}
                </div>
            </div>
        </div>

        <!-- TA Employee Report Notes -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-comment-dots" style="color: #00a884;"></i> TA Employee Progress Notes</h3>
            </div>
            <div class="card-body">
                @if($task->employee_notes)
                    <p style="font-size: 0.9rem; color: #334155; line-height: 1.5; white-space: pre-line;">{{ $task->employee_notes }}</p>
                @else
                    <div style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">No report notes logged by TA employee yet.</div>
                @endif
            </div>
        </div>

        <!-- Team Lead Review Notes -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-user-check" style="color: #00a884;"></i> Team Lead Review & Notes</h3>
            </div>
            <div class="card-body">
                @if($task->lead_notes)
                    <p style="font-size: 0.9rem; color: #334155; line-height: 1.5; white-space: pre-line;">{{ $task->lead_notes }}</p>
                @else
                    <div style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">No review notes added by Team Lead yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal: TA Employee / Team Lead Update -->
<div class="modal fade" id="updateTaskModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:600px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pen-to-square"></i> {{ $isLead ? 'Update Job Requisition Status & Review Notes' : 'Update Sourced Profiles & Progress' }}
            </h4>
            <button type="button" onclick="closeUpdateModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        @if($isLead)
        <!-- Team Lead Update Form -->
        <form method="POST" action="{{ route('ta.work.update_lead', $task->id) }}">
            @csrf

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Overall Status *</label>
                <select name="status" class="form-control" required>
                    <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Done" {{ $task->status === 'Done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Target Profiles Required *</label>
                <input type="number" name="target_profiles" class="form-control" value="{{ old('target_profiles', $task->target_profiles) }}" min="1" required>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Review Notes</label>
                <textarea name="lead_notes" class="form-control" rows="4" placeholder="Enter instructions or feedback for the TA specialist...">{{ old('lead_notes', $task->lead_notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Save Review</button>
            </div>
        </form>
        @else
        <!-- TA Employee Update Form -->
        <form method="POST" action="{{ route('ta.work.update_employee', $task->id) }}">
            @csrf

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Sourcing Work Status *</label>
                <select name="status" class="form-control" required>
                    <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Done" {{ $task->status === 'Done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Profiles Sourced So Far *</label>
                <input type="number" name="profiles_sourced" class="form-control" value="{{ old('profiles_sourced', $task->profiles_sourced) }}" min="0" required>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">TA Employee Progress Notes</label>
                <textarea name="employee_notes" class="form-control" rows="4" placeholder="Log details of candidate profiles shortlisted, interviews scheduled, or portal updates...">{{ old('employee_notes', $task->employee_notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Update Sourcing Progress</button>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
function openUpdateModal() {
    document.getElementById('updateTaskModal').style.display = 'flex';
}
function closeUpdateModal() {
    document.getElementById('updateTaskModal').style.display = 'none';
}
function openEditTaModal() {
    document.getElementById('editTaModal').style.display = 'flex';
}
function closeEditTaModal() {
    document.getElementById('editTaModal').style.display = 'none';
}
</script>

@if($isLead)
<!-- Modal: Edit Job Requirement -->
<div class="modal fade" id="editTaModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:680px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pen-to-square"></i> Edit TA Job Requisition & Targets
            </h4>
            <button type="button" onclick="closeEditTaModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form method="POST" action="{{ route('ta.work.update_task', $task->id) }}">
            @csrf
            @method('PUT')

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Assigned TA Employee *</label>
                    <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
                    <input type="text" class="form-control" value="{{ $task->assignee->name ?? '' }} ({{ $task->assignee->email ?? '' }})" readonly style="background-color:#f1f5f9; font-weight:600;">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Target Date *</label>
                    <input type="date" name="assigned_date" class="form-control" value="{{ old('assigned_date', $task->assigned_date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Job Title / Role *</label>
                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $task->job_title) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Job Location *</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $task->location) }}" required>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Experience Req. *</label>
                    <input type="text" name="experience" class="form-control" value="{{ old('experience', $task->experience) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Budget / Package *</label>
                    <input type="text" name="budget" class="form-control" value="{{ old('budget', $task->budget) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Employment Type</label>
                    <select name="duration" class="form-control">
                        <option value="Full Time" {{ old('duration', $task->duration) === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Contract" {{ old('duration', $task->duration) === 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Part Time" {{ old('duration', $task->duration) === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Internship" {{ old('duration', $task->duration) === 'Internship' ? 'selected' : '' }}>Internship</option>
                    </select>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Target Profiles Required *</label>
                    <input type="number" name="target_profiles" class="form-control" value="{{ old('target_profiles', $task->target_profiles) }}" min="1" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" {{ old('status', $task->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ old('status', $task->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Done" {{ old('status', $task->status) === 'Done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label" style="font-weight:700;">Job Description & Requirements *</label>
                <textarea name="job_description" class="form-control" rows="5" required>{{ old('job_description', $task->job_description) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Specific Instructions</label>
                <textarea name="lead_notes" class="form-control" rows="3">{{ old('lead_notes', $task->lead_notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeEditTaModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Update Job Requisition</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
