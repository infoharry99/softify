<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    /**
     * Display my company documents (Offer Letter, Appointment Letter, Contract).
     */
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $documents = EmployeeDocument::where('employee_id', $employee->id)
            ->where('status', 'Active')
            ->latest()
            ->get();

        return view('employee.documents.index', compact('documents'));
    }

    /**
     * Download document file.
     */
    public function download(EmployeeDocument $document)
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        if ($document->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to document.');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Requested file is not available on server.');
        }

        $ext = pathinfo($document->file_path, PATHINFO_EXTENSION);
        return Storage::disk('public')->download($document->file_path, $document->document_name . '.' . $ext);
    }

    /**
     * Preview document file in browser inline.
     */
    public function preview(EmployeeDocument $document)
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        if ($document->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to document.');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Requested file is not available on server.');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        $mimeType = Storage::disk('public')->mimeType($document->file_path);
        $ext = pathinfo($document->file_path, PATHINFO_EXTENSION);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->document_name . '.' . $ext . '"'
        ]);
    }
}
