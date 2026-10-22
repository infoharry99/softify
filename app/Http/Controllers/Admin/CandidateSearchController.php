<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateSavedSearch;
use App\Services\ActivityLogger;
use App\Services\BooleanSearch\BooleanSearchEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateSearchController extends Controller
{
    protected BooleanSearchEngine $booleanEngine;

    public function __construct(BooleanSearchEngine $booleanEngine)
    {
        $this->booleanEngine = $booleanEngine;
    }

    /**
     * Display the Naukri Resdex style candidate search interface.
     */
    public function index(Request $request)
    {
        $booleanMode = $request->boolean('boolean_mode', false);
        $booleanQuery = $request->input('boolean_query', '');
        $keyword = $request->input('q', '');
        $keywordMode = $request->input('keyword_mode', 'any'); // 'any' or 'all'

        $query = Candidate::with(['hr', 'updatedBy']);

        $highlightTerms = [];
        $booleanError = null;

        // 1. Search Query Execution (Boolean Search or Keyword Search)
        if ($booleanMode && !empty(trim($booleanQuery))) {
            $validation = $this->booleanEngine->validate($booleanQuery);
            if ($validation['valid']) {
                $applyResult = $this->booleanEngine->applyToQuery($query, $booleanQuery);
                if ($applyResult['success']) {
                    $highlightTerms = $applyResult['positive_terms'];
                } else {
                    $booleanError = $applyResult['error'];
                }
            } else {
                $booleanError = $validation['error'];
            }
        } elseif (!$booleanMode && !empty(trim($keyword))) {
            $result = $this->booleanEngine->applySimpleKeywords($query, $keyword, $keywordMode);
            $highlightTerms = $result['positive_terms'];
        }

        // 2. Advanced Filters Sidebar Integration
        // Experience Filter
        if ($request->filled('min_experience')) {
            $query->where('experience', '>=', (float) $request->input('min_experience'));
        }
        if ($request->filled('max_experience')) {
            $query->where('experience', '<=', (float) $request->input('max_experience'));
        }

        // Current CTC Filter
        if ($request->filled('min_current_ctc')) {
            $query->where('current_ctc', '>=', (float) $request->input('min_current_ctc'));
        }
        if ($request->filled('max_current_ctc')) {
            $query->where('current_ctc', '<=', (float) $request->input('max_current_ctc'));
        }

        // Expected CTC Filter
        if ($request->filled('min_expected_ctc')) {
            $query->where('expected_ctc', '>=', (float) $request->input('min_expected_ctc'));
        }
        if ($request->filled('max_expected_ctc')) {
            $query->where('expected_ctc', '<=', (float) $request->input('max_expected_ctc'));
        }

        // Location Filter
        if ($request->filled('location')) {
            $locations = is_array($request->input('location')) ? $request->input('location') : [$request->input('location')];
            $query->where(function ($sub) use ($locations) {
                foreach ($locations as $i => $loc) {
                    if (empty($loc)) continue;
                    if ($i === 0) {
                        $sub->where('location', 'like', "%{$loc}%");
                    } else {
                        $sub->orWhere('location', 'like', "%{$loc}%");
                    }
                }
            });
        }

        // Work Mode / Job Type Filter
        if ($request->filled('job_type')) {
            $jobTypes = (array) $request->input('job_type');
            $query->whereIn('job_type', $jobTypes);
        }

        // Notice Period Filter
        if ($request->filled('notice_period')) {
            $noticePeriods = (array) $request->input('notice_period');
            $query->whereIn('notice_period', $noticePeriods);
        }

        // Candidate Pipeline Stage / Status Filter
        if ($request->filled('status')) {
            $statuses = (array) $request->input('status');
            $query->whereIn('status', $statuses);
        }

        // Job Title Filter
        if ($request->filled('job_title')) {
            $query->where('job_title', 'like', "%{$request->input('job_title')}%");
        }

        // Company Filter
        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', "%{$request->input('company_name')}%");
        }

        // Shortlisted / Starred Only Filter
        if ($request->boolean('shortlisted_only')) {
            $query->where('is_highlighted', true);
        }

        // 3. Sorting Engine
        $sortBy = $request->input('sort_by', 'relevance');
        if ($sortBy === 'relevance' && !empty($highlightTerms)) {
            // Sort by occurrence score of matched terms in skills & job title
            $caseStatements = [];
            $bindings = [];
            foreach ($highlightTerms as $term) {
                $caseStatements[] = "CASE WHEN skills LIKE ? THEN 2 ELSE 0 END + CASE WHEN job_title LIKE ? THEN 3 ELSE 0 END";
                $bindings[] = "%{$term}%";
                $bindings[] = "%{$term}%";
            }
            $scoreSql = '(' . implode(' + ', $caseStatements) . ')';
            $query->orderByRaw("{$scoreSql} DESC", $bindings)->orderByDesc('updated_at');
        } elseif ($sortBy === 'experience_desc') {
            $query->orderByDesc('experience');
        } elseif ($sortBy === 'experience_asc') {
            $query->orderBy('experience');
        } elseif ($sortBy === 'ctc_asc') {
            $query->orderBy('expected_ctc');
        } elseif ($sortBy === 'ctc_desc') {
            $query->orderByDesc('expected_ctc');
        } else {
            $query->orderByDesc('updated_at');
        }

        // 4. Pagination
        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50], true) ? (int) $request->input('per_page') : 10;
        $candidates = $query->paginate($perPage)->withQueryString();

        // 5. Facet Counts & Suggestions for Sidebar
        $totalCandidatesCount = Candidate::count();
        $facets = [
            'job_types' => Candidate::select('job_type', DB::raw('count(*) as count'))->groupBy('job_type')->pluck('count', 'job_type')->toArray(),
            'notice_periods' => Candidate::select('notice_period', DB::raw('count(*) as count'))->groupBy('notice_period')->pluck('count', 'notice_period')->toArray(),
            'statuses' => Candidate::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status')->toArray(),
            'shortlisted_count' => Candidate::where('is_highlighted', true)->count(),
        ];

        // Popular skills suggestions for quick chips
        $suggestedSkills = [
            'Java', 'Python', 'React', 'PHP', 'Laravel', 'Microservices', 'AWS',
            'Spring Boot', '.NET Core', 'Node.js', 'Angular', 'SQL', 'Docker',
            'DevOps', 'REST API', 'JavaScript', 'HTML/CSS', 'Flutter'
        ];

        // Saved Searches for current user
        $savedSearches = CandidateSavedSearch::where('user_id', auth()->id())->latest()->get();

        if ($request->ajax() && $request->has('ajax_search')) {
            return response()->json([
                'html' => view('admin.candidate_search.partials.results', compact('candidates', 'highlightTerms'))->render(),
                'total' => $candidates->total(),
                'first_item' => $candidates->firstItem() ?? 0,
                'last_item' => $candidates->lastItem() ?? 0,
                'boolean_error' => $booleanError,
                'highlight_terms' => $highlightTerms,
            ]);
        }

        return view('admin.candidate_search.index', compact(
            'candidates',
            'highlightTerms',
            'booleanError',
            'totalCandidatesCount',
            'facets',
            'suggestedSkills',
            'savedSearches',
            'booleanMode',
            'booleanQuery',
            'keyword',
            'sortBy',
            'perPage'
        ));
    }

    /**
     * Real-time AJAX validation for Boolean search expression.
     */
    public function validateExpression(Request $request)
    {
        $expression = (string) $request->input('expression', '');
        $validation = $this->booleanEngine->validate($expression);

        $matchingCount = 0;
        if ($validation['valid']) {
            $q = Candidate::query();
            $this->booleanEngine->applyToQuery($q, $expression);
            $matchingCount = $q->count();
        }

        return response()->json([
            'valid' => $validation['valid'],
            'error' => $validation['error'],
            'tokens_count' => $validation['tokens_count'],
            'positive_terms' => $validation['positive_terms'],
            'matching_count' => $matchingCount,
            'readable_expression' => $validation['readable_expression'] ?? '',
        ]);
    }

    /**
     * AJAX Toggle Candidate Shortlist / Starred status.
     */
    public function toggleShortlist(Request $request, Candidate $candidate)
    {
        $candidate->update([
            'is_highlighted' => !$candidate->is_highlighted,
            'last_updated_by' => auth()->id(),
        ]);

        ActivityLogger::log('Candidate Shortlist Toggled', "Toggled shortlist status for '{$candidate->name}' to " . ($candidate->is_highlighted ? 'Shortlisted' : 'Unshortlisted'), Candidate::class, $candidate->id);

        return response()->json([
            'success' => true,
            'is_highlighted' => (bool) $candidate->is_highlighted,
            'message' => $candidate->is_highlighted ? "★ '{$candidate->name}' added to shortlisted candidates." : "☆ '{$candidate->name}' removed from shortlist.",
        ]);
    }

    /**
     * Save a search expression and filters for quick reload.
     */
    public function storeSavedSearch(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'search_query' => 'nullable|string|max:1000',
            'boolean_enabled' => 'boolean',
            'filters' => 'nullable|array',
            'results_count' => 'nullable|integer',
        ]);

        $savedSearch = CandidateSavedSearch::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'search_query' => $validated['search_query'] ?? null,
            'boolean_enabled' => $validated['boolean_enabled'] ?? false,
            'filters' => $validated['filters'] ?? [],
            'results_count' => $validated['results_count'] ?? 0,
        ]);

        ActivityLogger::log('Candidate Search Saved', "Saved candidate search '{$savedSearch->name}'", CandidateSavedSearch::class, $savedSearch->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Search '{$savedSearch->name}' saved successfully!",
                'saved_search' => $savedSearch,
            ]);
        }

        return back()->with('success', "Search '{$savedSearch->name}' saved successfully.");
    }

    /**
     * Delete a saved search.
     */
    public function destroySavedSearch(CandidateSavedSearch $savedSearch)
    {
        if ($savedSearch->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized.');
        }

        $name = $savedSearch->name;
        $savedSearch->delete();

        ActivityLogger::log('Candidate Search Deleted', "Deleted saved search '{$name}'");

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Saved search '{$name}' deleted.",
            ]);
        }

        return back()->with('success', "Saved search '{$name}' deleted.");
    }
}
