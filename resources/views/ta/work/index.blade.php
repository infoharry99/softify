@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('ta-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'TA Hiring Requirements & Work Assignments')
@section('page_title', 'Talent Acquisition Work & Job Requisitions')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-briefcase" style="color: #00a884;"></i> TA Job Requisitions & Work Directory
        </h3>

        @if($isLead)
        <button type="button" class="btn btn-primary" onclick="openAssignTaModal()" style="border-radius: 8px; font-weight: 700;">
            <i class="fa-solid fa-plus"></i> Assign Job Requirement to TA Employee
        </button>
        @endif
    </div>

    <!-- Filters -->
    <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('ta.work.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            @if($isLead)
            <div style="width: 220px;">
                <select name="assigned_to" class="form-control">
                    <option value="">-- All TA Employees --</option>
                    @foreach($taEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ request('assigned_to') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->department }})</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div style="width: 170px;">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter Date">
            </div>

            <div style="width: 160px;">
                <select name="status" class="form-control">
                    <option value="">-- All Status --</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Done" {{ request('status') === 'Done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm" style="border-radius: 6px;">Filter</button>
            <a href="{{ route('ta.work.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 6px;">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Assigned Date</th>
                    <th>Job Title & Location</th>
                    <th>Experience & Budget</th>
                    <th>Assigned TA Employee</th>
                    <th>Profiles Sourced / Target</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $task)
                <tr>
                    <td><strong>{{ $task->assigned_date->format('M d, Y') }}</strong></td>
                    <td>
                        <strong style="color:#00a884; font-size:0.95rem;">{{ $task->job_title }}</strong>
                        <div style="font-size: 0.78rem; color: #64748b;"><i class="fa-solid fa-location-dot"></i> {{ $task->location }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; font-weight: 600;">Exp: {{ $task->experience ?? 'N/A' }}</div>
                        <div style="font-size: 0.78rem; color: #15803d; font-weight: 700;">Budget: {{ $task->budget ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <strong>{{ $task->assignee->name ?? 'Unassigned' }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">By: {{ $task->assigner->name ?? 'System' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:0.85rem; padding: 5px 10px;">
                            👥 {{ $task->profiles_sourced }} / {{ $task->target_profiles }} Profiles
                        </span>
                    </td>
                    <td>
                        @if($task->status === 'Done')
                            <span class="badge badge-success" style="font-size: 0.8rem;"><i class="fa-solid fa-circle-check"></i> Done</span>
                        @elseif($task->status === 'In Progress')
                            <span class="badge badge-warning" style="background:#fff7ed; color:#c2410c; border:1px solid #ffedd5; font-size:0.8rem;"><i class="fa-solid fa-spinner"></i> In Progress</span>
                        @else
                            <span class="badge badge-secondary" style="font-size: 0.8rem;"><i class="fa-solid fa-clock"></i> Pending</span>
                        @endif
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('ta.work.show', $task->id) }}" class="btn btn-secondary btn-sm" style="padding: 5px 10px; border-radius: 6px; margin-right: 4px;" title="View Job Description & Update">
                            <i class="fa-solid fa-eye" style="color:#00a884;"></i> View & Update
                        </a>
                        @if($isLead)
                        <button type="button" onclick="openEditTaModal({{ json_encode($task) }})" class="btn btn-secondary btn-sm" style="padding: 5px 8px; border-radius: 6px; margin-right: 4px;" title="Edit Job Requisition">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form id="delete-ta-task-{{ $task->id }}" action="{{ route('ta.work.destroy', $task->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmSwalDelete('delete-ta-task-{{ $task->id }}', 'Delete Requisition', 'Are you sure you want to delete this job assignment?')" class="btn btn-danger btn-sm" style="padding: 5px 8px; border-radius: 6px;" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 35px;">
                        <i class="fa-solid fa-user-graduate" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.4;"></i><br>
                        No Talent Acquisition job requirements assigned yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 15px 20px;">
        {{ $assignments->links() }}
    </div>
</div>

@if($isLead)
<!-- Modal: Assign Job Requirement to TA Employee -->
<div class="modal fade" id="assignTaModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:680px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-briefcase"></i> Assign Job Requirement to TA Employee
            </h4>
            <button type="button" onclick="closeAssignTaModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form method="POST" action="{{ route('ta.work.store') }}">
            @csrf

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Select TA Employee *</label>
                    <select name="assigned_to" class="form-control" required>
                        <option value="">-- Choose TA Specialist --</option>
                        @foreach($taEmployees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->department }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Assignment Date *</label>
                    <input type="date" name="assigned_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <!-- Job Position Specs (From Screenshot) -->
            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Job Title *</label>
                    <input type="text" name="job_title" class="form-control" placeholder="e.g. Senior Python Developer" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Job Location *</label>
                    <input type="text" name="location" class="form-control" placeholder="e.g. Hyderabad / Remote" required>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Experience *</label>
                    <input type="text" name="experience" class="form-control" placeholder="e.g. 4+ Years" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Budget *</label>
                    <input type="text" name="budget" class="form-control" placeholder="e.g. 1.2 LPM" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Duration</label>
                    <input type="text" name="duration" class="form-control" placeholder="e.g. 6+ months">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label class="form-label" style="font-weight:700;">Target Profiles Required *</label>
                <input type="number" name="target_profiles" class="form-control" value="10" min="1" required style="width:180px;">
            </div>

            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label" style="font-weight:700;">Job Description & Requirements *</label>
                <textarea name="job_description" class="form-control" rows="5" placeholder="Work with young passionate teams... Focus on multi-skill enablement..." required></textarea>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Specific Instructions</label>
                <textarea name="lead_notes" class="form-control" rows="3" placeholder="Sourcing priorities or specific portal instructions..."></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeAssignTaModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Assign Job Requirement</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignTaModal() {
    document.getElementById('assignTaModal').style.display = 'flex';
}
function closeAssignTaModal() {
    document.getElementById('assignTaModal').style.display = 'none';
}

function openEditTaModal(task) {
    document.getElementById('editTaForm').action = '/ta/work/' + task.id + '/update-task';
    document.getElementById('edit_ta_assigned_to').value = task.assigned_to;
    document.getElementById('edit_ta_assigned_date').value = task.assigned_date ? task.assigned_date.split('T')[0] : '';
    document.getElementById('edit_ta_job_title').value = task.job_title || '';
    document.getElementById('edit_ta_location').value = task.location || '';
    document.getElementById('edit_ta_experience').value = task.experience || '';
    document.getElementById('edit_ta_budget').value = task.budget || '';
    document.getElementById('edit_ta_duration').value = task.duration || 'Full Time';
    document.getElementById('edit_ta_target_profiles').value = task.target_profiles || 1;
    document.getElementById('edit_ta_status').value = task.status || 'Pending';
    document.getElementById('edit_ta_job_description').value = task.job_description || '';
    document.getElementById('edit_ta_lead_notes').value = task.lead_notes || '';

    document.getElementById('editTaModal').style.display = 'flex';
}

function closeEditTaModal() {
    document.getElementById('editTaModal').style.display = 'none';
}
</script>

<!-- Modal: Edit Job Requirement -->
<div class="modal fade" id="editTaModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:680px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pen-to-square"></i> Edit TA Job Requisition & Targets
            </h4>
            <button type="button" onclick="closeEditTaModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form id="editTaForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Select TA Employee *</label>
                    <select id="edit_ta_assigned_to" name="assigned_to" class="form-control" required>
                        <option value="">-- Choose Employee --</option>
                        @foreach($taEmployees as $ta)
                            <option value="{{ $ta->id }}">{{ $ta->name }} ({{ $ta->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Target Date *</label>
                    <input type="date" id="edit_ta_assigned_date" name="assigned_date" class="form-control" required>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Job Title / Role *</label>
                    <input type="text" id="edit_ta_job_title" name="job_title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Job Location *</label>
                    <input type="text" id="edit_ta_location" name="location" class="form-control" required>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Experience Req. *</label>
                    <input type="text" id="edit_ta_experience" name="experience" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Budget / Package *</label>
                    <input type="text" id="edit_ta_budget" name="budget" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Employment Type</label>
                    <select id="edit_ta_duration" name="duration" class="form-control">
                        <option value="Full Time">Full Time</option>
                        <option value="Contract">Contract</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Target Profiles Required *</label>
                    <input type="number" id="edit_ta_target_profiles" name="target_profiles" class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Status *</label>
                    <select id="edit_ta_status" name="status" class="form-control" required>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label" style="font-weight:700;">Job Description & Requirements *</label>
                <textarea id="edit_ta_job_description" name="job_description" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Specific Instructions</label>
                <textarea id="edit_ta_lead_notes" name="lead_notes" class="form-control" rows="3"></textarea>
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
