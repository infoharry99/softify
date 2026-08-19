@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'BDA Daily Target Details')
@section('page_title', 'BDA Work Details & Time Schedule')

@section('content')
<div style="margin-bottom: 15px;">
    <a href="{{ route('bda.work.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Work Directory
    </a>
</div>

<!-- Header Details Card -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-calendar-day" style="color: #00a884;"></i> {{ $task->title }}
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
            <button type="button" onclick="openEditTaskModal()" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 700;">
                <i class="fa-solid fa-pen-to-square"></i> Edit Task Targets
            </button>
            @endif

            <button type="button" onclick="openUpdateModal()" class="btn btn-primary btn-sm" style="border-radius: 8px; font-weight: 700;">
                <i class="fa-solid fa-circle-check"></i> {{ $isLead ? 'Review & Update Status' : 'Log Daily Achievements' }}
            </button>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 25px;">
    <!-- Left Column: Daily Time Schedule & Daily KPI Matrix -->
    <div>
        <!-- 1. Daily Time Schedule Table (Matching Image Standard) -->
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header" style="background: #00a884; color: #ffffff; border-radius: 12px 12px 0 0;">
                <h3 class="card-title" style="color: #ffffff; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-clock"></i> Daily Time Schedule & Activity Workflow
                </h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background: #f1f5f9; color: #0f172a;">
                                <th style="width: 150px;">Time Slot</th>
                                <th>Activity Workflow</th>
                                <th style="width: 200px;">Target Objective</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($task->effective_schedule_items as $idx => $item)
                            @php $isLunch = str_contains(strtolower($item['activity'] ?? ''), 'lunch'); @endphp
                            <tr @if($isLunch) style="background: #fefce8;" @endif>
                                <td><span class="badge {{ $isLunch ? 'badge-warning' : 'badge-secondary' }}">{{ $item['time_slot'] ?? '' }}</span></td>
                                <td>
                                    @if($isLunch)
                                        <strong><i class="fa-solid fa-utensils"></i> {{ $item['activity'] ?? '' }}</strong>
                                    @elseif(str_contains(strtolower($item['activity'] ?? ''), 'morning') || str_contains(strtolower($item['activity'] ?? ''), 'meeting'))
                                        <strong>{{ $item['activity'] ?? '' }}</strong>
                                    @else
                                        {{ $item['activity'] ?? '' }}
                                    @endif
                                </td>
                                <td>{{ $item['objective'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. Daily KPI Targets vs Achieved Progress -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-chart-pie" style="color: #00a884;"></i> Daily KPI Metrics & Achievements</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">🏢 New Companies</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0369a1; margin-top: 4px;">
                            {{ $task->achieved_new_companies }} <small style="font-size: 0.8rem; color: #64748b;">/ {{ $task->target_new_companies }} Target</small>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">🔗 LinkedIn Requests</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #b45309; margin-top: 4px;">
                            {{ $task->achieved_linkedin_requests }} <small style="font-size: 0.8rem; color: #64748b;">/ {{ $task->target_linkedin_requests }} Target</small>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">✉️ Emails Sent</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #6b21a8; margin-top: 4px;">
                            {{ $task->achieved_emails }} <small style="font-size: 0.8rem; color: #64748b;">/ {{ $task->target_emails }} Target</small>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">📞 Cold Calls</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #15803d; margin-top: 4px;">
                            {{ $task->achieved_cold_calls }} <small style="font-size: 0.8rem; color: #64748b;">/ {{ $task->target_cold_calls }} Target</small>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">🔄 Follow-ups</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #475569; margin-top: 4px;">
                            {{ $task->achieved_followups }} <small style="font-size: 0.8rem; color: #64748b;">/ {{ $task->target_followups }} Target</small>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 10px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">🤝 Meetings Booked</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #00a884; margin-top: 4px;">
                            {{ $task->achieved_meetings }} <small style="font-size: 0.8rem; color: #64748b;">/ {{ $task->target_meetings }} Target</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Notes & Review Details -->
    <div>
        <!-- Employee Report Notes -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-comment-dots" style="color: #00a884;"></i> Employee Daily Report Notes</h3>
            </div>
            <div class="card-body">
                @if($task->employee_notes)
                    <p style="font-size: 0.9rem; color: #334155; line-height: 1.5; white-space: pre-line;">{{ $task->employee_notes }}</p>
                @else
                    <div style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">No report notes logged by employee yet.</div>
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

<!-- Modal: BDA Employee / Team Lead Update -->
<div class="modal fade" id="updateTaskModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:620px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pen-to-square"></i> {{ $isLead ? 'Update Work Status & Team Lead Notes' : 'Log Achieved KPIs & Daily Report' }}
            </h4>
            <button type="button" onclick="closeUpdateModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        @if($isLead)
        <!-- Team Lead Update Form -->
        <form method="POST" action="{{ route('bda.work.update_lead', $task->id) }}">
            @csrf

            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label" style="font-weight:700;">Overall Work Status *</label>
                <select name="status" class="form-control" required>
                    <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Done" {{ $task->status === 'Done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Review Notes</label>
                <textarea name="lead_notes" class="form-control" rows="4" placeholder="Enter review feedback or instructions...">{{ old('lead_notes', $task->lead_notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Save Review</button>
            </div>
        </form>
        @else
        <!-- Employee Achievement Update Form -->
        <form method="POST" action="{{ route('bda.work.update_employee', $task->id) }}">
            @csrf

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">My Work Status *</label>
                <select name="status" class="form-control" required>
                    <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Done" {{ $task->status === 'Done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <h5 style="font-weight:800; color:#0f172a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px; font-size:0.92rem;">
                <i class="fa-solid fa-square-check" style="color:#00a884;"></i> Log Actual Achieved Numbers
            </h5>

            <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-bottom:20px; background:#f8fafc; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">New Companies</label>
                    <input type="number" name="achieved_new_companies" class="form-control" value="{{ old('achieved_new_companies', $task->achieved_new_companies) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">LinkedIn Requests</label>
                    <input type="number" name="achieved_linkedin_requests" class="form-control" value="{{ old('achieved_linkedin_requests', $task->achieved_linkedin_requests) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Emails Sent</label>
                    <input type="number" name="achieved_emails" class="form-control" value="{{ old('achieved_emails', $task->achieved_emails) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Cold Calls</label>
                    <input type="number" name="achieved_cold_calls" class="form-control" value="{{ old('achieved_cold_calls', $task->achieved_cold_calls) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Follow-ups</label>
                    <input type="number" name="achieved_followups" class="form-control" value="{{ old('achieved_followups', $task->achieved_followups) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Meetings Booked</label>
                    <input type="number" name="achieved_meetings" class="form-control" value="{{ old('achieved_meetings', $task->achieved_meetings) }}" min="0" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Employee Daily Report Notes</label>
                <textarea name="employee_notes" class="form-control" rows="4" placeholder="Log details of work done, challenges faced, or lead updates...">{{ old('employee_notes', $task->employee_notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Update Achievements</button>
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
function openEditTaskModal() {
    document.getElementById('editTaskModal').style.display = 'flex';
}
function closeEditTaskModal() {
    document.getElementById('editTaskModal').style.display = 'none';
}
</script>

@if($isLead)
<!-- Modal: Edit BDA Work Assignment & Targets -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:680px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pen-to-square"></i> Edit BDA Daily Work Assignment & Targets
            </h4>
            <button type="button" onclick="closeEditTaskModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form method="POST" action="{{ route('bda.work.update_task', $task->id) }}">
            @csrf
            @method('PUT')

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Assigned Employee *</label>
                    <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
                    <input type="text" class="form-control" value="{{ $task->assignee->name ?? '' }} ({{ $task->assignee->email ?? '' }})" readonly style="background-color: #f1f5f9; font-weight:600;">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Target Date *</label>
                    <input type="date" name="assigned_date" class="form-control" value="{{ old('assigned_date', $task->assigned_date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Target Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}">
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

            <h5 style="font-size:0.9rem; font-weight:800; color:#0f172a; margin-top:15px; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:5px;">
                Daily KPI Target Objectives
            </h5>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; margin-bottom:15px;">
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">New Companies</label>
                    <input type="number" name="target_new_companies" class="form-control" value="{{ old('target_new_companies', $task->target_new_companies) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">LinkedIn Requests</label>
                    <input type="number" name="target_linkedin_requests" class="form-control" value="{{ old('target_linkedin_requests', $task->target_linkedin_requests) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Emails Sent</label>
                    <input type="number" name="target_emails" class="form-control" value="{{ old('target_emails', $task->target_emails) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Cold Calls</label>
                    <input type="number" name="target_cold_calls" class="form-control" value="{{ old('target_cold_calls', $task->target_cold_calls) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Follow-ups</label>
                    <input type="number" name="target_followups" class="form-control" value="{{ old('target_followups', $task->target_followups) }}" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Meetings Booked</label>
                    <input type="number" name="target_meetings" class="form-control" value="{{ old('target_meetings', $task->target_meetings) }}" min="0" required>
                </div>
            </div>

            <h5 style="font-size:0.9rem; font-weight:800; color:#0f172a; margin-top:20px; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:5px; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-clock" style="color:#00a884;"></i> Daily Time Schedule & Activity Workflow (Optional Customization)
            </h5>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:15px; margin-bottom:20px; max-height:240px; overflow-y:auto;">
                <div style="display:grid; grid-template-columns:140px 1fr 160px; gap:10px; margin-bottom:8px; font-size:0.75rem; font-weight:700; color:#64748b;">
                    <div>TIME SLOT</div>
                    <div>ACTIVITY WORKFLOW</div>
                    <div>TARGET OBJECTIVE</div>
                </div>
                @foreach($task->effective_schedule_items as $idx => $item)
                <div style="display:grid; grid-template-columns:140px 1fr 160px; gap:10px; margin-bottom:8px; align-items:center;">
                    <div>
                        <input type="text" name="schedule_items[{{ $idx }}][time_slot]" class="form-control" value="{{ $item['time_slot'] ?? '' }}" placeholder="e.g. 10:00 - 10:15" style="font-size:0.8rem; padding:4px 8px;">
                    </div>
                    <div>
                        <input type="text" name="schedule_items[{{ $idx }}][activity]" class="form-control" value="{{ $item['activity'] ?? '' }}" placeholder="Activity Workflow..." style="font-size:0.8rem; padding:4px 8px;">
                    </div>
                    <div>
                        <input type="text" name="schedule_items[{{ $idx }}][objective]" class="form-control" value="{{ $item['objective'] ?? '' }}" placeholder="Target Objective..." style="font-size:0.8rem; padding:4px 8px;">
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Instructions / Notes</label>
                <textarea name="lead_notes" class="form-control" rows="3">{{ old('lead_notes', $task->lead_notes) }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeEditTaskModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Update Work Assignment</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
