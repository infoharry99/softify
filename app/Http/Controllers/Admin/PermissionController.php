<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display permissions grouped by module.
     */
    public function index()
    {
        $permissionsByModule = Permission::withCount(['roles', 'users'])->get()->groupBy('module');
        return view('admin.permissions.index', compact('permissionsByModule'));
    }

    /**
     * Store a newly created custom permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'module' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['module'] . '.' . $validated['name']);

        if (Permission::where('slug', $validated['slug'])->exists()) {
            return back()->withErrors(['name' => 'A permission with this generated slug already exists.']);
        }

        $permission = Permission::create($validated);

        ActivityLogger::log('Permission Created', "Created custom permission '{$permission->name}' ({$permission->slug})", Permission::class, $permission->id);

        return back()->with('success', "Permission '{$permission->name}' created successfully.");
    }
}
