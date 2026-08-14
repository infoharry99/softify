<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDocumentController extends Controller
{
    /**
     * Upload an employee document (Offer Letter, Appointment Letter, Contract, etc.).
     */
    public function upload(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'document_name' => 'required|string|max:150',
            'document_type' => 'required|in:Offer Letter,Joining Letter,Appointment Letter,Salary Letter,Experience Letter,Other',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'version' => 'nullable|string|max:20',
        ]);

        $path = $request->file('document_file')->store("employee_documents/{$employee->id}", 'public');

        $doc = EmployeeDocument::create([
            'employee_id' => $employee->id,
            'document_name' => $validated['document_name'],
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'version' => $validated['version'] ?? '1.0',
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLogger::log('Document Uploaded', "Uploaded document '{$doc->document_name}' for employee {$employee->user->name}", Employee::class, $employee->id);

        return back()->with('success', 'Employee document uploaded successfully.');
    }

    /**
     * Delete an employee document.
     */
    public function destroy(EmployeeDocument $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $docName = $document->document_name;
        $document->delete();

        ActivityLogger::log('Document Deleted', "Deleted document '{$docName}'");

        return back()->with('success', 'Document deleted successfully.');
    }
}
