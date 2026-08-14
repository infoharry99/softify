<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceRequirement;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class FinanceRequirementController extends Controller
{
    /**
     * Display finance requirements directory with multi-filters.
     */
    public function index(Request $request)
    {
        $query = FinanceRequirement::with('creator');

        // 1. Text Search (Vendor Name, Company Name, Vendor Location)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('vendor_location', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filter by Company Name
        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', "%{$request->company_name}%");
        }

        // 4. Filter by Budget Range
        if ($request->filled('min_budget')) {
            $query->where('budget', '>=', (float) $request->min_budget);
        }
        if ($request->filled('max_budget')) {
            $query->where('budget', '<=', (float) $request->max_budget);
        }

        // Summary Stats
        $totalCount = FinanceRequirement::count();
        $totalBudget = FinanceRequirement::sum('budget');
        $totalRemaining = FinanceRequirement::sum('remaining_payment');
        $pendingCount = FinanceRequirement::whereIn('status', ['No Update', 'In Progress'])->count();
        $closedCount = FinanceRequirement::where('status', 'Closed')->count();

        $requirements = $query->latest()->paginate(10)->withQueryString();
        $companies = FinanceRequirement::whereNotNull('company_name')->pluck('company_name')->unique();

        return view('admin.finance.index', compact(
            'requirements',
            'companies',
            'totalCount',
            'totalBudget',
            'totalRemaining',
            'pendingCount',
            'closedCount'
        ));
    }

    /**
     * Show form for adding a new finance requirement.
     */
    public function create()
    {
        return view('admin.finance.create');
    }

    /**
     * Store a new finance requirement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_location' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'selected_candidates_count' => 'required|integer|min:0',
            'budget' => 'required|numeric|min:0',
            'date' => 'required|date',
            'remaining_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:No Update,In Progress,Closed',
            'note' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['remaining_payment'] = $validated['remaining_payment'] ?? 0;

        $finance = FinanceRequirement::create($validated);

        ActivityLogger::log('Finance Requirement Created', "Added finance requirement for vendor '{$finance->vendor_name}'", FinanceRequirement::class, $finance->id);

        return redirect()->route('admin.finance.index')
            ->with('success', "Finance requirement for '{$finance->vendor_name}' created successfully.");
    }

    /**
     * Display finance requirement details.
     */
    public function show(FinanceRequirement $finance)
    {
        $finance->load('creator');
        return view('admin.finance.show', compact('finance'));
    }

    /**
     * Show form for editing a finance requirement.
     */
    public function edit(FinanceRequirement $finance)
    {
        return view('admin.finance.edit', compact('finance'));
    }

    /**
     * Update a finance requirement.
     */
    public function update(Request $request, FinanceRequirement $finance)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_location' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'selected_candidates_count' => 'required|integer|min:0',
            'budget' => 'required|numeric|min:0',
            'date' => 'required|date',
            'remaining_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:No Update,In Progress,Closed',
            'note' => 'nullable|string',
        ]);

        $validated['remaining_payment'] = $validated['remaining_payment'] ?? 0;

        $finance->update($validated);

        ActivityLogger::log('Finance Requirement Updated', "Updated finance requirement ID {$finance->id} for vendor '{$finance->vendor_name}'", FinanceRequirement::class, $finance->id);

        return redirect()->route('admin.finance.index')
            ->with('success', "Finance requirement for '{$finance->vendor_name}' updated successfully.");
    }

    /**
     * Remove a finance requirement.
     */
    public function destroy(FinanceRequirement $finance)
    {
        $vendor = $finance->vendor_name;
        $finance->delete();

        ActivityLogger::log('Finance Requirement Deleted', "Deleted finance requirement for vendor '{$vendor}'");

        return redirect()->route('admin.finance.index')
            ->with('success', "Finance requirement for '{$vendor}' deleted successfully.");
    }
}
