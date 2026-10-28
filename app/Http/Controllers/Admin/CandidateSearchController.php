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
     * Display the Naukri Resdex Advance Search Form Page (Full details fill page).
     */
    public function form(Request $request)
    {
        $totalCandidatesCount = Candidate::count();

        // AI / Suggested skills based on database
        $suggestedSkills = [
            'Python', 'Java Developer', 'AWS', 'J2EE', 'Javascript', 'SQL', 'Kafka',
            'Java Full Stack Developer', 'Java Architect', 'Architect', 'React.js',
            'Node.js', 'PHP', 'Laravel', 'Docker', 'DevOps', 'Microservices', 'Spring Boot'
        ];

        // Popular locations in database
        $popularLocations = Candidate::whereNotNull('location')
            ->select('location', DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('location')
            ->toArray();

        // Saved searches for current user
        $savedSearches = CandidateSavedSearch::where('user_id', auth()->id())->latest()->get();

        // Recent searches from session
        $recentSearches = session('recent_candidate_searches', []);

        return view('admin.candidate_search.form', compact(
            'totalCandidatesCount',
            'suggestedSkills',
            'popularLocations',
            'savedSearches',
            'recentSearches'
        ));
    }

    /**
     * Live database autocomplete suggestions as user types (e.g. typing 'java').
     */
    public function suggestions(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $suggestions = collect();

        // 1. Job Titles matching $q
        $jobTitles = Candidate::whereNotNull('job_title')
            ->where('job_title', 'like', "%{$q}%")
            ->select('job_title')
            ->distinct()
            ->limit(8)
            ->pluck('job_title');
        foreach ($jobTitles as $title) {
            $clean = trim($title);
            if ($clean && strlen($clean) <= 45 && !in_array(strtolower($clean), ['n/a', 'null', '`skills`', '`job_title`'])) {
                $suggestions->push(['text' => $clean, 'type' => 'Job Title']);
            }
        }

        // 2. Candidate Skills parsed tokens from DB
        $skillsRaw = Candidate::whereNotNull('skills')
            ->where('skills', 'like', "%{$q}%")
            ->limit(60)
            ->pluck('skills');

        foreach ($skillsRaw as $raw) {
            $clean = preg_replace('/(Golang:|PHP:|Python:|iOS:|Databases:|Web Technologies:|Cloud & DevOps:|CI\/CD:|Others:|Microservices:|Framework and Libraries:|Frameworks:|Scripting Languages:|Unix Programming:|Tools:)/i', '', $raw);
            $tokens = preg_split('/[,\|\;\n\r\t\/\•\·\>\<\:\*\&]+/', $clean);
            foreach ($tokens as $token) {
                $trimmed = trim(preg_replace('/^[\:\s\¦\.\-\(\)\[\]]+|[\:\s\¦\.\-\(\)\[\]]+$/u', '', $token));
                if (strlen($trimmed) >= 2 && strlen($trimmed) <= 35 && stripos($trimmed, $q) !== false) {
                    if (!preg_match('/(such as|version|experience|knowledge of|proficient in)/i', $trimmed)) {
                        if (!$suggestions->contains(fn($item) => strtolower($item['text']) === strtolower($trimmed))) {
                            $suggestions->push(['text' => $trimmed, 'type' => 'Skill']);
                        }
                    }
                }
            }
        }

        // 3. Known Tech Dictionary
        $techDictionary = [
            'Java', 'Core Java', 'JavaScript', 'J2EE', 'JavaFX', 'Java Developer', 'Java Full Stack Developer', 'Java Architect',
            'Python', 'Python Developer', 'Django', 'Flask', 'React', 'React.js', 'React Native', 'Node.js', 'Express.js',
            'PHP', 'Laravel', 'CodeIgniter', 'Symfony', 'WordPress', 'Angular', 'AngularJS', 'Vue.js',
            'C#', '.NET', '.NET Core', 'ASP.NET', 'Entity Framework', 'AWS', 'Amazon Web Services', 'Azure', 'GCP',
            'Docker', 'Kubernetes', 'DevOps', 'CI/CD', 'Jenkins', 'Git', 'GitHub', 'SQL', 'MySQL', 'PostgreSQL',
            'MongoDB', 'Oracle', 'Redis', 'Kafka', 'Microservices', 'REST API', 'GraphQL', 'HTML', 'CSS', 'Bootstrap',
            'Tailwind CSS', 'TypeScript', 'Next.js', 'Spring Boot', 'Hibernate', 'Selenium', 'Flutter'
        ];
        foreach ($techDictionary as $tech) {
            if (stripos($tech, $q) !== false) {
                if (!$suggestions->contains(fn($item) => strtolower($item['text']) === strtolower($tech))) {
                    $suggestions->push(['text' => $tech, 'type' => 'Skill']);
                }
            }
        }

        // Sort: exact match first, then starts with, then contains
        $sorted = $suggestions->sortBy(function($item) use ($q) {
            $text = strtolower($item['text']);
            $qLower = strtolower($q);
            if ($text === $qLower) return 0;
            if (str_starts_with($text, $qLower)) return 1;
            return 2;
        })->values()->take(10);

        return response()->json($sorted);
    }

    /**
     * Live database autocomplete suggestions for Candidate Locations.
     * ONLY returns locations that actually exist in the candidates database!
     */
    public function locations(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $suggestions = collect();

        // Query distinct candidate locations strictly from database
        $dbLocations = Candidate::whereNotNull('location')
            ->where('location', '!=', '')
            ->where('location', 'not like', '%`location`%')
            ->where('location', 'like', "%{$q}%")
            ->select('location')
            ->distinct()
            ->limit(50)
            ->pluck('location');

        foreach ($dbLocations as $loc) {
            $cleanLoc = trim(str_replace(['`', '"', "'"], '', $loc));
            if (empty($cleanLoc) || strtolower($cleanLoc) === 'location' || strtolower($cleanLoc) === 'n/a') {
                continue;
            }

            // Whole location string match
            if (stripos($cleanLoc, $q) !== false) {
                $formatted = ucwords(strtolower($cleanLoc));
                if (!$suggestions->contains(fn($item) => strtolower($item['text']) === strtolower($formatted))) {
                    $suggestions->push([
                        'text' => $formatted,
                        'type' => 'Candidate Location'
                    ]);
                }
            }

            // Split comma-separated tokens (e.g. "Mumbai, Maharashtra" -> "Mumbai")
            $tokens = preg_split('/[,|\/\;]+/', $cleanLoc);
            if (count($tokens) > 1) {
                foreach ($tokens as $token) {
                    $trimmed = trim($token);
                    if (strlen($trimmed) >= 2 && strlen($trimmed) <= 40 && stripos($trimmed, $q) !== false) {
                        $formattedToken = ucwords(strtolower($trimmed));
                        if (!$suggestions->contains(fn($item) => strtolower($item['text']) === strtolower($formattedToken))) {
                            $suggestions->push([
                                'text' => $formattedToken,
                                'type' => 'Candidate Location'
                            ]);
                        }
                    }
                }
            }
        }

        // Sort exact match first, then prefix, then contains
        $sorted = $suggestions->sortBy(function($item) use ($q) {
            $text = strtolower($item['text']);
            $qLower = strtolower($q);
            if ($text === $qLower) return 0;
            if (str_starts_with($text, $qLower)) return 1;
            return 2;
        })->values()->take(10);

        return response()->json($sorted);
    }

    /**
     * Display the Naukri Resdex style candidate search interface & results.
     */
    public function index(Request $request)
    {
        $booleanMode = $request->boolean('boolean_mode', false);
        $booleanQuery = $request->input('boolean_query', '');
        $keyword = $request->input('q', '');
        $keywordMode = $request->boolean('mandatory_keywords', false) ? 'all' : $request->input('keyword_mode', 'any');

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

        // 2. Exclude Keywords (NOT logic)
        if ($request->filled('exclude_keywords')) {
            $excludes = array_filter(array_map('trim', preg_split('/[,;|]+/', $request->input('exclude_keywords'))));
            foreach ($excludes as $exTerm) {
                $query->where(function ($sub) use ($exTerm) {
                    $sub->where('skills', 'not like', "%{$exTerm}%")
                        ->where('job_title', 'not like', "%{$exTerm}%")
                        ->where('name', 'not like', "%{$exTerm}%")
                        ->where('company_name', 'not like', "%{$exTerm}%")
                        ->where('note', 'not like', "%{$exTerm}%");
                });
            }
        }

        // 3. Advanced Filters Sidebar Integration
        // Experience Filter
        if ($request->filled('min_experience')) {
            $query->where('experience', '>=', (float) $request->input('min_experience'));
        }
        if ($request->filled('max_experience')) {
            $query->where('experience', '<=', (float) $request->input('max_experience'));
        }

        // Current CTC Filter (Supports Lacs e.g. 5 or Exact Rupees e.g. 500000)
        if ($request->filled('min_current_ctc')) {
            $val = (float) $request->input('min_current_ctc');
            $val = ($val > 0 && $val < 100) ? $val * 100000 : $val;
            $query->where('current_ctc', '>=', $val);
        }
        if ($request->filled('max_current_ctc')) {
            $val = (float) $request->input('max_current_ctc');
            $val = ($val > 0 && $val < 100) ? $val * 100000 : $val;
            $query->where('current_ctc', '<=', $val);
        }

        // Expected CTC / Annual Salary Filter (Supports Lacs e.g. 1 to 5, or Raw Rupees e.g. 100000 to 500000)
        $minExpected = null;
        $rawMin = $request->input('min_salary', $request->input('min_expected_ctc'));
        if ($rawMin !== null && $rawMin !== '') {
            $val = (float) $rawMin;
            if ($val > 0) {
                $minExpected = ($val < 100) ? $val * 100000 : $val;
            }
        }

        $maxExpected = null;
        $rawMax = $request->input('max_salary', $request->input('max_expected_ctc'));
        if ($rawMax !== null && $rawMax !== '') {
            $val = (float) $rawMax;
            if ($val > 0) {
                $maxExpected = ($val < 100) ? $val * 100000 : $val;
            }
        }

        if ($minExpected !== null) {
            $query->where('expected_ctc', '>=', $minExpected);
        }
        if ($maxExpected !== null) {
            $query->where('expected_ctc', '<=', $maxExpected);
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

        // Base search query snapshot (Search Keywords, Experience, Salary, and Location applied)
        // Used to compute dynamic facet counts exclusively for the matching candidates
        $baseFacetQuery = clone $query;

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

        // 4. Record Recent Search into Session
        $recentKey = trim($booleanQuery ?: $keyword);
        if (!empty($recentKey)) {
            $recentSearches = session('recent_candidate_searches', []);
            $newItem = [
                'query' => $recentKey,
                'boolean_mode' => $booleanMode,
                'filters' => $request->except(['_token', 'page']),
                'time' => now()->diffForHumans(),
            ];
            $filteredRecent = array_filter($recentSearches, fn($item) => ($item['query'] ?? '') !== $recentKey);
            array_unshift($filteredRecent, $newItem);
            session(['recent_candidate_searches' => array_slice($filteredRecent, 0, 5)]);
        }

        // 5. Sorting Engine
        $sortBy = $request->input('sort_by', 'relevance');
        if ($sortBy === 'relevance' && !empty($highlightTerms)) {
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

        // 6. Pagination
        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50], true) ? (int) $request->input('per_page') : 10;
        $candidates = $query->paginate($perPage)->withQueryString();

        // 7. Dynamic Facet Counts (Counts strictly within matching candidates)
        $totalCandidatesCount = Candidate::count();

        // Work Mode facet count within matching search candidates
        $jtFacetQuery = clone $baseFacetQuery;
        if ($request->filled('notice_period')) {
            $jtFacetQuery->whereIn('notice_period', (array) $request->input('notice_period'));
        }
        if ($request->filled('status')) {
            $jtFacetQuery->whereIn('status', (array) $request->input('status'));
        }
        if ($request->filled('job_title')) {
            $jtFacetQuery->where('job_title', 'like', "%{$request->input('job_title')}%");
        }
        if ($request->filled('company_name')) {
            $jtFacetQuery->where('company_name', 'like', "%{$request->input('company_name')}%");
        }
        if ($request->boolean('shortlisted_only')) {
            $jtFacetQuery->where('is_highlighted', true);
        }
        $jobTypeCounts = $jtFacetQuery->select('job_type', DB::raw('count(*) as count'))
            ->groupBy('job_type')
            ->pluck('count', 'job_type')
            ->toArray();

        // Notice Period facet count within matching search candidates
        $npFacetQuery = clone $baseFacetQuery;
        if ($request->filled('job_type')) {
            $npFacetQuery->whereIn('job_type', (array) $request->input('job_type'));
        }
        if ($request->filled('status')) {
            $npFacetQuery->whereIn('status', (array) $request->input('status'));
        }
        if ($request->filled('job_title')) {
            $npFacetQuery->where('job_title', 'like', "%{$request->input('job_title')}%");
        }
        if ($request->filled('company_name')) {
            $npFacetQuery->where('company_name', 'like', "%{$request->input('company_name')}%");
        }
        if ($request->boolean('shortlisted_only')) {
            $npFacetQuery->where('is_highlighted', true);
        }
        $noticePeriodCounts = $npFacetQuery->select('notice_period', DB::raw('count(*) as count'))
            ->groupBy('notice_period')
            ->pluck('count', 'notice_period')
            ->toArray();

        // Pipeline Stage / Status facet count within matching search candidates
        $stFacetQuery = clone $baseFacetQuery;
        if ($request->filled('job_type')) {
            $stFacetQuery->whereIn('job_type', (array) $request->input('job_type'));
        }
        if ($request->filled('notice_period')) {
            $stFacetQuery->whereIn('notice_period', (array) $request->input('notice_period'));
        }
        if ($request->filled('job_title')) {
            $stFacetQuery->where('job_title', 'like', "%{$request->input('job_title')}%");
        }
        if ($request->filled('company_name')) {
            $stFacetQuery->where('company_name', 'like', "%{$request->input('company_name')}%");
        }
        if ($request->boolean('shortlisted_only')) {
            $stFacetQuery->where('is_highlighted', true);
        }
        $statusCounts = $stFacetQuery->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $shortlistedCount = (clone $query)->where('is_highlighted', true)->count();

        $facets = [
            'job_types' => $jobTypeCounts,
            'notice_periods' => $noticePeriodCounts,
            'statuses' => $statusCounts,
            'shortlisted_count' => $shortlistedCount,
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
