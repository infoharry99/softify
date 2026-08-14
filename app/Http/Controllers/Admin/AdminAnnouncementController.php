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
            'audience' => 'required|in:All Employees,Department,Role,Selected Employees',
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'audience' => $validated['audience'],
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);

        ActivityLogger::log('Announcement Created', "Published company announcement '{$announcement->title}'", Announcement::class, $announcement->id);

        return back()->with('success', 'Company announcement published successfully.');
    }
}
