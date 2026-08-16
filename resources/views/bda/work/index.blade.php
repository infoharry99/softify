@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'BDA Daily Work & KPI Management')
@section('page_title', 'BDA Daily Targets & Work Schedule')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-list-check" style="color: #00a884;"></i> BDA Work & Daily Targets Directory
        </h3>

        @if($isLead)
        <button type="button" class="btn btn-primary" onclick="openAssignWorkModal()" style="border-radius: 8px; font-weight: 700;">
            <i class="fa-solid fa-plus"></i> Assign Daily Work to BDA Employee
        </button>
        @endif
    </div>

    <!-- Filters -->
    <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('bda.work.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            @if($isLead)
            <div style="width: 220px;">
                <select name="assigned_to" class="form-control">
                    <option value="">-- All BDA Employees --</option>
                    @foreach($bdaEmployees as $emp)
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
            <a href="{{ route('bda.work.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 6px;">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>BDA Employee</th>
                    <th>Assigned By</th>
                    <th>KPI Summary (Target vs Achieved)</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $task)
                <tr>
                    <td><strong>{{ $task->assigned_date->format('M d, Y') }}</strong></td>
                    <td>
                        <strong>{{ $task->assignee->name ?? 'Unassigned' }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $task->assignee->email ?? '' }}</div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $task->assigner->name ?? 'System' }}</span></td>
                    <td>
                        <div style="font-size: 0.82rem; display: flex; flex-wrap: wrap; gap: 6px;">
                            <span class="badge" style="background:#e0f2fe; color:#0369a1;" title="New Companies">🏢 {{ $task->achieved_new_companies }}/{{ $task->target_new_companies }}</span>
                            <span class="badge" style="background:#fef3c7; color:#b45309;" title="LinkedIn Requests">🔗 {{ $task->achieved_linkedin_requests }}/{{ $task->target_linkedin_requests }}</span>
                            <span class="badge" style="background:#f3e8ff; color:#6b21a8;" title="Emails Sent">✉️ {{ $task->achieved_emails }}/{{ $task->target_emails }}</span>
                            <span class="badge" style="background:#dcfce7; color:#15803d;" title="Cold Calls">📞 {{ $task->achieved_cold_calls }}/{{ $task->target_cold_calls }}</span>
                            <span class="badge" style="background:#ccfbf1; color:#0f766e;" title="Meetings Booked">🤝 {{ $task->achieved_meetings }}/{{ $task->target_meetings }}</span>
                        </div>
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
                        <a href="{{ route('bda.work.show', $task->id) }}" class="btn btn-secondary btn-sm" style="padding: 5px 10px; border-radius: 6px; margin-right: 4px;" title="View Details & Schedule">
                            <i class="fa-solid fa-eye" style="color:#00a884;"></i> View & Update
                        </a>
                        @if($isLead)
                        <form id="delete-bda-task-{{ $task->id }}" action="{{ route('bda.work.destroy', $task->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmSwalDelete('delete-bda-task-{{ $task->id }}', 'Delete Assignment', 'Are you sure you want to delete this work assignment?')" class="btn btn-danger btn-sm" style="padding: 5px 8px; border-radius: 6px;" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 35px;">
                        <i class="fa-solid fa-clipboard-list" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.4;"></i><br>
                        No daily BDA work assignments found.
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
<!-- Modal: Assign Work to BDA Employee -->
<div class="modal fade" id="assignWorkModal" tabindex="-1" aria-hidden="true" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; width:92%; max-width:680px; border-radius:14px; padding:25px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
            <h4 style="margin:0; font-weight:800; color:#00a884; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-plus-circle"></i> Assign Daily Work & Target to BDA Employee
            </h4>
            <button type="button" onclick="closeAssignWorkModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b;">&times;</button>
        </div>

        <form method="POST" action="{{ route('bda.work.store') }}">
            @csrf

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Select BDA Employee *</label>
                    <select name="assigned_to" class="form-control" required>
                        <option value="">-- Choose Employee --</option>
                        @foreach($bdaEmployees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->department }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Assignment Date *</label>
                    <input type="date" name="assigned_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label" style="font-weight:700;">Work Title / Focus</label>
                <input type="text" name="title" class="form-control" value="Daily BDA Sales & Outreach Target" required>
            </div>

            <h5 style="font-weight:800; color:#0f172a; border-bottom:1px solid #cbd5e1; padding-bottom:6px; margin-bottom:12px; font-size:0.95rem;">
                <i class="fa-solid fa-bullseye" style="color:#00a884;"></i> Set Daily KPI Targets
            </h5>

            <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-bottom:20px; background:#f8fafc; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">New Companies</label>
                    <input type="number" name="target_new_companies" class="form-control" value="20" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">LinkedIn Requests</label>
                    <input type="number" name="target_linkedin_requests" class="form-control" value="30" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Emails Sent</label>
                    <input type="number" name="target_emails" class="form-control" value="30" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Cold Calls</label>
                    <input type="number" name="target_cold_calls" class="form-control" value="35" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Follow-ups</label>
                    <input type="number" name="target_followups" class="form-control" value="15" min="0" required>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#334155;">Meetings Booked</label>
                    <input type="number" name="target_meetings" class="form-control" value="3" min="0" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-weight:700;">Team Lead Instructions / Notes</label>
                <textarea name="lead_notes" class="form-control" rows="3" placeholder="Add specific instructions for the day..."></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeAssignWorkModal()" class="btn btn-secondary btn-sm" style="border-radius:8px;">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px; font-weight:700;">Assign Work</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignWorkModal() {
    document.getElementById('assignWorkModal').style.display = 'flex';
}
function closeAssignWorkModal() {
    document.getElementById('assignWorkModal').style.display = 'none';
}
</script>
@endif

@endsection
