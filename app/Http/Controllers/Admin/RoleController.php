<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->latest()->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissionsByModule = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissionsByModule'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $role = Role::create($validated);

        if (!empty($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        }

        ActivityLogger::log('Role Created', "Created role '{$role->name}'", Role::class, $role->id);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' created successfully.");
    }

    /**
     * Display role details and assigned users.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        $permissionsByModule = $role->permissions->groupBy('module');

        return view('admin.roles.show', compact('role', 'permissionsByModule'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissionsByModule = Permission::all()->groupBy('module');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionsByModule', 'rolePermissionIds'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Do not change slug for super-admin
        if ($role->slug !== 'super-admin') {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $role->update($validated);
        $role->permissions()->sync($request->input('permissions', []));

        ActivityLogger::log('Role Updated', "Updated role '{$role->name}'", Role::class, $role->id);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' updated successfully.");
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'The Super Admin role cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role because it is currently assigned to users.');
        }

        $name = $role->name;
        $role->delete();

        ActivityLogger::log('Role Deleted', "Deleted role '{$name}'");

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$name}' deleted successfully.");
    }

    /**
     * Toggle role active/inactive status.
     */
    public function toggleStatus(Role $role)
    {
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'The Super Admin role status cannot be changed.');
        }

        $newStatus = $role->status === 'active' ? 'inactive' : 'active';
        $role->update(['status' => $newStatus]);

        ActivityLogger::log('Role Status Changed', "Changed status of role '{$role->name}' to {$newStatus}", Role::class, $role->id);

        return back()->with('success', "Role status updated to {$newStatus}.");
    }

    /**
     * View users assigned to a specific role.
     */
    public function users(Role $role)
    {
        $users = $role->users()->paginate(10);
        return view('admin.roles.users', compact('role', 'users'));
    }
}
