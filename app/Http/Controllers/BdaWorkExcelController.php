<?php

namespace App\Http\Controllers;

use App\Models\BdaWork;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BdaWorkExcelController extends Controller
{
    /**
     * Display a listing of BDA Work Excel uploads with role-based visibility.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('super-admin') || $user->hasRole('admin');
        $isLead = $user->hasRole('bda-team-lead') || $user->hasRole('manager') || $isAdmin;

        $query = BdaWork::with(['uploadedBy.roles', 'uploadedBy.employee']);

        if ($isAdmin) {
            // Admin sees all BDA Work Excel uploads
        } elseif ($isLead) {
            // Team Lead sees work uploaded by themselves + their team members / BDAs
            $allowedUserIds = $this->getAllowedTeamUserIds($user);
            $query->whereIn('uploaded_by', $allowedUserIds);
        } else {
            // Standard BDA sees ONLY their own uploaded work
            $query->where('uploaded_by', $user->id);
        }

        // 1. Text Search Filter (Title or Description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Uploaded By Filter (For Lead & Admin)
        if ($request->filled('uploaded_by')) {
            $query->where('uploaded_by', $request->uploaded_by);
        }

        // 3. Date Filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $bdaWorks = $query->latest()->paginate(10)->withQueryString();

        // Get list of selectable users for filter dropdown
        $usersList = collect();
        if ($isAdmin) {
            $usersList = User::whereHas('roles', function ($rq) {
                $rq->whereIn('slug', ['bda', 'bda-team-lead', 'super-admin', 'admin']);
            })->orWhere('department', 'BDA')->orWhere('department', 'Sales')->get();
        } elseif ($isLead) {
            $allowedUserIds = $this->getAllowedTeamUserIds($user);
            $usersList = User::whereIn('id', $allowedUserIds)->get();
        }

        return view('bda.excel.index', compact('bdaWorks', 'usersList', 'isAdmin', 'isLead'));
    }

    /**
     * Show form for uploading a new BDA Work Excel file.
     */
    public function create()
    {
        return view('bda.excel.create');
    }

    /**
     * Store newly uploaded BDA Work Excel file or Google Sheet URL.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file_url' => 'nullable|url|max:2000',
            'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_url.url' => 'Please enter a valid Google Sheets or external URL (e.g. https://docs.google.com/spreadsheets/d/...).',
            'excel_file.mimes' => 'Only Excel (.xlsx, .xls) and CSV (.csv) files are allowed.',
            'excel_file.max' => 'The uploaded file size must not exceed 10 MB.',
        ]);

        if (empty($request->file_url) && !$request->hasFile('excel_file')) {
            return back()->withInput()->withErrors(['excel_file' => 'Please either upload an Excel file or provide a Google Sheets URL (or both).']);
        }

        $filePath = null;
        $originalName = null;

        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
            $originalName = $file->getClientOriginalName();
            $storedName = 'bda_work_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('bda_work_excels', $storedName, 'public');
        }

        $bdaWork = BdaWork::create([
            'title' => trim($validated['title']),
            'description' => isset($validated['description']) ? trim($validated['description']) : null,
            'file_url' => isset($validated['file_url']) ? trim($validated['file_url']) : null,
            'file_path' => $filePath,
            'file_name' => $originalName,
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLogger::log(
            'BDA Work Excel Uploaded',
            "Uploaded BDA work file/link for title '{$bdaWork->title}'",
            BdaWork::class,
            $bdaWork->id
        );

        return redirect()->route('bda.excel.index')
            ->with('success', "BDA Work '{$bdaWork->title}' saved successfully.");
    }

    /**
     * Display details of a specific BDA Work upload.
     */
    public function show(BdaWork $bdaWork)
    {
        $this->authorizeAccess($bdaWork);
        $bdaWork->load(['uploadedBy.roles', 'uploadedBy.employee']);

        return view('bda.excel.show', compact('bdaWork'));
    }

    /**
     * Show form for editing an uploaded BDA Work record.
     */
    public function edit(BdaWork $bdaWork)
    {
        $this->authorizeAccess($bdaWork);

        return view('bda.excel.edit', compact('bdaWork'));
    }

    /**
     * Update an uploaded BDA Work record (Title, Description, Google Sheet URL, or replacement Excel file).
     */
    public function update(Request $request, BdaWork $bdaWork)
    {
        $this->authorizeAccess($bdaWork);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file_url' => 'nullable|url|max:2000',
            'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_url.url' => 'Please enter a valid Google Sheets or external URL (e.g. https://docs.google.com/spreadsheets/d/...).',
            'excel_file.mimes' => 'Only Excel (.xlsx, .xls) and CSV (.csv) files are allowed.',
            'excel_file.max' => 'The uploaded file size must not exceed 10 MB.',
        ]);

        $updateData = [
            'title' => trim($validated['title']),
            'description' => isset($validated['description']) ? trim($validated['description']) : null,
            'file_url' => isset($validated['file_url']) ? trim($validated['file_url']) : null,
        ];

        // Handle replacement Excel file if uploaded
        if ($request->hasFile('excel_file')) {
            // Delete old file from storage
            if ($bdaWork->file_path && Storage::disk('public')->exists($bdaWork->file_path)) {
                Storage::disk('public')->delete($bdaWork->file_path);
            }

            $file = $request->file('excel_file');
            $originalName = $file->getClientOriginalName();
            $storedName = 'bda_work_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('bda_work_excels', $storedName, 'public');

            $updateData['file_path'] = $filePath;
            $updateData['file_name'] = $originalName;
        }

        $bdaWork->update($updateData);

        ActivityLogger::log(
            'BDA Work Excel Updated',
            "Updated BDA work record #{$bdaWork->id} title to '{$bdaWork->title}'",
            BdaWork::class,
            $bdaWork->id
        );

        return redirect()->route('bda.excel.index')
            ->with('success', "BDA Work record '{$bdaWork->title}' updated successfully.");
    }

    /**
     * Securely download the uploaded Excel file.
     */
    public function download(BdaWork $bdaWork)
    {
        $this->authorizeAccess($bdaWork);

        if (!$bdaWork->file_path || !Storage::disk('public')->exists($bdaWork->file_path)) {
            return back()->with('error', "Requested Excel file '{$bdaWork->file_name}' was not found on the server.");
        }

        return Storage::disk('public')->download($bdaWork->file_path, $bdaWork->file_name);
    }

    /**
     * Stream Excel / CSV file binary content for in-browser viewer.
     */
    public function streamFile(BdaWork $bdaWork)
    {
        $this->authorizeAccess($bdaWork);

        if (!$bdaWork->file_path || !Storage::disk('public')->exists($bdaWork->file_path)) {
            abort(404, 'Excel file not found on server.');
        }

        $filePath = Storage::disk('public')->path($bdaWork->file_path);
        $ext = $bdaWork->file_extension;
        $mimeType = match($ext) {
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel',
            'csv' => 'text/csv',
            default => 'application/octet-stream',
        };

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $bdaWork->file_name . '"',
        ]);
    }

    /**
     * Delete an uploaded BDA Work record.
     */
    public function destroy(BdaWork $bdaWork)
    {
        $this->authorizeAccess($bdaWork);

        $title = $bdaWork->title;

        // Delete stored file
        if ($bdaWork->file_path && Storage::disk('public')->exists($bdaWork->file_path)) {
            Storage::disk('public')->delete($bdaWork->file_path);
        }

        $bdaWork->delete();

        ActivityLogger::log(
            'BDA Work Excel Deleted',
            "Deleted BDA work record '{$title}'",
            BdaWork::class,
            0
        );

        return redirect()->route('bda.excel.index')
            ->with('success', "BDA Work record '{$title}' deleted successfully.");
    }

    /**
     * Helper: Check role-based authorization access for a BDA Work record.
     */
    private function authorizeAccess(BdaWork $bdaWork)
    {
        $user = auth()->user();

        // 1. Admin / Super Admin has full access
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return true;
        }

        // 2. Team Lead has access to own uploads + team members' uploads
        if ($user->hasRole('bda-team-lead') || $user->hasRole('manager')) {
            $allowedUserIds = $this->getAllowedTeamUserIds($user);
            if (in_array($bdaWork->uploaded_by, $allowedUserIds)) {
                return true;
            }
        }

        // 3. BDA Employee has access ONLY to their own uploads
        if ($bdaWork->uploaded_by == $user->id) {
            return true;
        }

        abort(403, 'Unauthorized access to this BDA Work Excel upload.');
    }

    /**
     * Helper: Get team member user IDs for a BDA Team Lead.
     */
    private function getAllowedTeamUserIds($user)
    {
        // Get employees reporting directly to this Team Lead
        $teamUserIds = User::whereHas('employee', function ($q) use ($user) {
            $q->where('reporting_manager_id', $user->employee->id ?? 0);
        })->pluck('id')->toArray();

        // Also include all users with 'bda' or 'bda-team-lead' roles or BDA/Sales department
        $bdaUserIds = User::whereHas('roles', function ($rq) {
            $rq->whereIn('slug', ['bda', 'bda-team-lead']);
        })->orWhere('department', 'BDA')->orWhere('department', 'Sales')->pluck('id')->toArray();

        return array_unique(array_merge([$user->id], $teamUserIds, $bdaUserIds));
    }
}
