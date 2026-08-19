<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class AdminAnnouncementController extends Controller
{
    /**
     * Display company announcements list.
     */
    public function index()
    {
        $announcements = Announcement::with('publisher')->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Store new announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'audience' => 'nullable|string',
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'audience' => $validated['audience'] ?? 'All Employees',
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);

        ActivityLogger::log('Announcement Created', "Published company announcement '{$announcement->title}'", Announcement::class, $announcement->id);

        return back()->with('success', 'Company announcement published successfully.');
    }

    /**
     * Update existing announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'audience' => 'nullable|string',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'audience' => $validated['audience'] ?? ($announcement->audience ?? 'All Employees'),
        ]);

        ActivityLogger::log('Announcement Updated', "Updated company announcement '{$announcement->title}'", Announcement::class, $announcement->id);

        return back()->with('success', 'Company announcement updated successfully.');
    }

    /**
     * Delete announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $title = $announcement->title;
        $id = $announcement->id;
        $announcement->delete();

        ActivityLogger::log('Announcement Deleted', "Deleted company announcement '{$title}'", Announcement::class, $id);

        return back()->with('success', 'Company announcement deleted successfully.');
    }
}
