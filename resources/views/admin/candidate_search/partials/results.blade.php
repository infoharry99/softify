@use('App\Services\BooleanSearch\BooleanSearchEngine')

<div class="results-container">
    @forelse($candidates as $cand)
        @php
            $rawSkills = $cand->skills ?? '';
            $cleanSkills = preg_replace('/(Golang:|PHP:|Python:|iOS:|Databases:|Web Technologies:|Cloud & DevOps:|CI\/CD:|Others:|Microservices:)/i', '', $rawSkills);
            $rawItems = preg_split('/[,\|\;\n\r\t]+/', $cleanSkills);

            $parsedSkills = [];
            foreach($rawItems as $item) {
                $item = trim(preg_replace('/^[\:\s\¦]+|[\:\s\¦]+$/u', '', $item));
                if ($item !== '' && strlen($item) > 1 && !in_array(strtolower($item), ['and', 'with', 'or', 'for'])) {
                    $parsedSkills[] = $item;
                }
            }
            $parsedSkills = array_values(array_unique($parsedSkills));
            $initialSkills = array_slice($parsedSkills, 0, 8);
            $moreSkills = array_slice($parsedSkills, 8);
            $isStarred = (bool) $cand->is_highlighted;
        @endphp

        <div class="candidate-card {{ $isStarred ? 'is-shortlisted' : '' }}" id="candidate-card-{{ $cand->id }}">
            <!-- Card Top Header -->
            <div class="card-top-header">
                <div class="candidate-main-info">
                    <div class="candidate-avatar">
                        {{ strtoupper(substr($cand->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="candidate-name-row">
                            <a href="{{ route('admin.candidates.show', $cand->id) }}" class="candidate-name" target="_blank">
                                {!! BooleanSearchEngine::highlight($cand->name, $highlightTerms) !!}
                            </a>

                            <span class="badge-stage badge-stage-{{ strtolower(str_replace(' ', '-', $cand->status)) }}">
                                {{ $cand->status }}
                            </span>

                            <span class="badge-workmode">
                                <i class="fa-solid fa-briefcase"></i> {{ $cand->job_type }}
                            </span>
                        </div>

                        <div class="candidate-designation">
                            <strong style="color: #0f172a;">
                                {!! BooleanSearchEngine::highlight($cand->job_title ?: 'Professional', $highlightTerms) !!}
                            </strong>
                            <span class="bullet-divider">•</span>
                            <span>{{ $cand->experience > 0 ? $cand->experience . ' Yrs Experience' : 'Fresher / Entry Level' }}</span>
                            @if($cand->company_name && $cand->company_name !== 'General')
                                <span class="bullet-divider">•</span>
                                <span style="color: #64748b;">
                                    <i class="fa-regular fa-building"></i> {!! BooleanSearchEngine::highlight($cand->company_name, $highlightTerms) !!}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="candidate-quick-actions">
                    <button type="button" 
                            class="btn-star {{ $isStarred ? 'active' : '' }}" 
                            onclick="toggleCandidateShortlist({{ $cand->id }}, this)" 
                            title="{{ $isStarred ? 'Remove from Shortlist' : 'Add to Shortlist' }}">
                        <i class="{{ $isStarred ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                        <span class="star-label">{{ $isStarred ? 'Shortlisted' : 'Shortlist' }}</span>
                    </button>
                </div>
            </div>

            <!-- Key Metadata Grid Row -->
            <div class="candidate-meta-grid">
                <div class="meta-item" title="Current Location">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>{!! BooleanSearchEngine::highlight($cand->location, $highlightTerms) !!}</span>
                </div>

                <div class="meta-item" title="Current & Expected CTC">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                    <span>
                        Curr: <strong>₹{{ number_format($cand->current_ctc ?? 0) }}</strong> 
                        <span style="color: #cbd5e1;">|</span> 
                        Exp: <strong style="color: #00a884;">₹{{ number_format($cand->expected_ctc ?? 0) }}</strong>
                    </span>
                </div>

                <div class="meta-item" title="Notice Period">
                    <i class="fa-solid fa-clock"></i>
                    <span>Notice: <strong>{{ $cand->notice_period }}</strong></span>
                </div>

                <div class="meta-item contact-item" title="Contact Details">
                    <i class="fa-regular fa-envelope"></i>
                    <span>{{ $cand->email }}</span>
                    <span style="color: #cbd5e1;">•</span>
                    <i class="fa-solid fa-phone" style="font-size: 0.75rem;"></i>
                    <span>{{ $cand->phone }}</span>
                </div>
            </div>

            <!-- Skills Section with Search Highlights -->
            <div class="candidate-skills-wrap">
                <span class="skills-label"><i class="fa-solid fa-code"></i> Key Skills:</span>
                <div class="skills-chips">
                    @forelse($initialSkills as $sk)
                        <span class="skill-chip">
                            {!! BooleanSearchEngine::highlight($sk, $highlightTerms) !!}
                        </span>
                    @empty
                        <span class="skill-chip">
                            {!! BooleanSearchEngine::highlight($cand->skills, $highlightTerms) !!}
                        </span>
                    @endforelse

                    @if(count($moreSkills) > 0)
                        <span class="skill-chip more-chip" onclick="toggleMoreSkills({{ $cand->id }}, this)">
                            +{{ count($moreSkills) }} more
                        </span>
                        <span class="all-more-skills" id="more-skills-{{ $cand->id }}" style="display: none;">
                            @foreach($moreSkills as $sk)
                                <span class="skill-chip">
                                    {!! BooleanSearchEngine::highlight($sk, $highlightTerms) !!}
                                </span>
                            @endforeach
                        </span>
                    @endif
                </div>
            </div>

            <!-- Profile Summary / Recruiter Note (if available) -->
            @if($cand->note)
                <div class="candidate-summary-snippet">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <span>{!! BooleanSearchEngine::highlight(Str::limit($cand->note, 180), $highlightTerms) !!}</span>
                </div>
            @endif

            <!-- Card Bottom Footer Actions -->
            <div class="card-footer-actions">
                <div class="left-actions">
                    <span class="recruiter-tag">
                        <i class="fa-solid fa-user-check"></i> Sourced by: {{ $cand->hr->name ?? 'Recruiter' }}
                    </span>
                    <span class="updated-time">
                        <i class="fa-regular fa-calendar"></i> Updated {{ $cand->updated_at->diffForHumans() }}
                    </span>
                </div>

                <div class="right-buttons">
                    @if($cand->resume)
                        <button type="button" 
                                class="btn-action btn-action-resume" 
                                onclick="openResumeModal('{{ route('admin.candidates.resume_preview', ['candidate' => $cand->id, 'type' => 'original']) }}', '{{ route('admin.candidates.resume', ['candidate' => $cand->id, 'type' => 'original']) }}', '{{ addslashes($cand->name) }} (Original Resume)')">
                            <i class="fa-solid fa-file-pdf"></i> Original Resume
                        </button>
                    @endif

                    @if($cand->edited_resume)
                        <button type="button" 
                                class="btn-action btn-action-edited" 
                                onclick="openResumeModal('{{ route('admin.candidates.resume_preview', ['candidate' => $cand->id, 'type' => 'edited']) }}', '{{ route('admin.candidates.resume', ['candidate' => $cand->id, 'type' => 'edited']) }}', '{{ addslashes($cand->name) }} (Edited Copy)')">
                            <i class="fa-solid fa-file-pen"></i> Edited Copy
                        </button>
                    @endif

                    <a href="{{ route('admin.candidates.edit', $cand->id) }}" class="btn-action btn-action-secondary" title="Edit Candidate">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>

                    <a href="{{ route('admin.candidates.show', $cand->id) }}" class="btn-action btn-action-primary" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Profile
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="no-results-card">
            <div class="empty-icon"><i class="fa-solid fa-user-slash"></i></div>
            <h3>No candidates found matching your search criteria</h3>
            <p>Try refining your Boolean keywords, removing filters, or adjusting salary/experience ranges.</p>
            <div style="margin-top: 15px;">
                <button type="button" onclick="resetAllResdexFilters()" class="btn-reset-large">
                    <i class="fa-solid fa-rotate-left"></i> Clear All Filters & Show All Candidates
                </button>
            </div>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($candidates->hasPages())
        <div class="resdex-pagination-wrapper">
            <div class="pagination-meta">
                Showing <strong>{{ $candidates->firstItem() }}</strong> to <strong>{{ $candidates->lastItem() }}</strong> of <strong>{{ $candidates->total() }}</strong> candidates
            </div>
            <div class="pagination-links-box">
                {{ $candidates->links() }}
            </div>
        </div>
    @endif
</div>
