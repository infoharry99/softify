<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Candidate Details</th>
                <th>Hiring Client Company</th>
                <th>Skills & Experience</th>
                <th>Notice Period & Job Type</th>
                <th>Current / Expected CTC</th>
                <th>Resume</th>
                <th>Pipeline Stage</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $cand)
            <tr>
                <td>
                    <strong style="color: var(--text-main); font-size: 0.95rem;">{{ $cand->name }}</strong>
                    <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-regular fa-envelope"></i> {{ $cand->email }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-phone"></i> {{ $cand->phone }} | <i class="fa-solid fa-location-dot"></i> {{ $cand->location }}</div>
                </td>
                <td>
                    <strong style="color: #0f172a;"><i class="fa-solid fa-building" style="color: #00a884;"></i> {{ $cand->company_name ?? 'General Pool' }}</strong>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Recruiter: {{ $cand->hr->name ?? 'HR' }}</div>
                </td>
                <td>
                    <div style="max-width: 200px; display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 4px;">
                        @foreach(explode(',', $cand->skills) as $sk)
                            @if(trim($sk) !== '')
                                <span class="badge badge-secondary" style="font-size: 0.7rem; background-color: #f0faf7; color: #00a884; border: 1px solid #9ee5d4;">{{ trim($sk) }}</span>
                            @endif
                        @endforeach
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">
                        Experience: {{ $cand->experience }} Yrs
                    </div>
                </td>
                <td>
                    <span class="badge badge-primary">{{ $cand->job_type }}</span>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 3px;">
                        Notice: <strong>{{ $cand->notice_period }}</strong>
                    </div>
                </td>
                <td>
                    <div>Curr: ₹{{ number_format($cand->current_ctc ?? 0) }}</div>
                    <div>Exp: <strong style="color: #00a884;">₹{{ number_format($cand->expected_ctc ?? 0) }}</strong></div>
                </td>
                <td>
                    @if($cand->resume)
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openResumeModal('{{ route('admin.candidates.resume_preview', $cand->id) }}', '{{ route('admin.candidates.resume', $cand->id) }}', '{{ addslashes($cand->name) }}')" style="font-size: 0.78rem; border-radius: 8px; color: #00a884; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i> Preview Resume
                        </button>
                    @else
                        <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">
                            <i class="fa-solid fa-file-circle-xmark"></i> No Resume
                        </span>
                    @endif
                </td>
                <td>
                    @php
                        $badgeClass = match($cand->status) {
                            'Hired' => 'badge-success',
                            'Offered' => 'badge-success',
                            'Interview Scheduled' => 'badge-primary',
                            'Screening' => 'badge-warning',
                            'Rejected' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $cand->status }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                        <a href="{{ route('admin.candidates.show', $cand->id) }}" class="btn btn-secondary btn-sm" title="View Profile" style="border-radius: 8px;">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        @if((auth()->user()->hasPermission('hr.edit') || auth()->user()->hasRole('super-admin')) && !auth()->user()->hasRole('talent-acquisition'))
                        <a href="{{ route('admin.candidates.edit', $cand->id) }}" class="btn btn-secondary btn-sm" title="Edit Record" style="border-radius: 8px;">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 45px 20px;">
                    <div style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 10px;"><i class="fa-solid fa-folder-open"></i></div>
                    <div style="font-size: 0.98rem; font-weight: 700; color: #475569;">No Candidate Records Found</div>
                    <div style="font-size: 0.82rem; color: #94a3b8; margin-top: 4px;">Try adjusting your search keywords or filter criteria matrix.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- DataTables Bootstrap Style Pagination & Entry Counter Footer -->
<div class="pagination-footer" style="padding: 16px 20px; background: #ffffff; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 0.85rem; color: #64748b;">
    <div>
        @if($candidates->total() > 0)
            Showing <strong style="color: #0f172a;">{{ $candidates->firstItem() }}</strong> to <strong style="color: #0f172a;">{{ $candidates->lastItem() }}</strong> of <strong style="color: #00a884;">{{ $candidates->total() }}</strong> candidate records
        @else
            Showing 0 candidate records
        @endif
    </div>

    <div class="ajax-pagination-links">
        {{ $candidates->links() }}
    </div>
</div>
