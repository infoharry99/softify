<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if (!auth()->user()->hasRole('super-admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('slug', 'super-admin');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::where('status', 'active')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::where('status', 'active')->get();
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.users.create', compact('roles', 'permissionsByModule'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => ['nullable', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'profile_photo' => 'nullable|image|max:2048',
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210).',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if (!empty($request->roles)) {
            $user->roles()->sync($request->roles);
        }

        if (!empty($request->permissions)) {
            $user->permissions()->sync($request->permissions);
        }

        ActivityLogger::log('User Created', "Created user {$user->name} ({$user->email})", User::class, $user->id);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User created successfully with assigned roles and permissions.');
    }

    /**
     * Display the specified user details.
     */
    public function show(User $user)
    {
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Only Super Admin can view or edit Super Admin accounts.');
        }

        $user->load(['roles.permissions', 'permissions']);
        $directPermissions = $user->permissions;
        $rolePermissions = Permission::whereHas('roles', function ($q) use ($user) {
            $q->whereIn('roles.id', $user->roles->pluck('id'));
        })->get();

        return view('admin.users.show', compact('user', 'directPermissions', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Only Super Admin can view or edit Super Admin accounts.');
        }

        $user->load(['roles', 'permissions']);
        $roles = Role::get();
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.users.edit', compact('user', 'roles', 'permissionsByModule'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Only Super Admin can view or edit Super Admin accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['nullable', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($validated);
        $user->roles()->sync($request->input('roles', []));
        $user->permissions()->sync($request->input('permissions', []));

        ActivityLogger::log('User Updated', "Updated user details for {$user->name}", User::class, $user->id);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        ActivityLogger::log('User Deleted', "Deleted user account {$name}");

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$name}' deleted successfully.");
    }

    /**
     * Toggle active/inactive user status.
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        ActivityLogger::log('User Status Changed', "Changed status of {$user->name} to {$newStatus}", User::class, $user->id);

        return back()->with('success', "User status updated to {$newStatus}.");
    }

    /**
     * Manage direct user permissions screen.
     */
    public function permissions(User $user)
    {
        $user->load(['roles.permissions', 'permissions']);
        $permissionsByModule = Permission::all()->groupBy('module');

        $rolePermissionIds = Permission::whereHas('roles', function ($q) use ($user) {
            $q->whereIn('roles.id', $user->roles->pluck('id'));
        })->pluck('id')->toArray();

        $directPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('admin.users.permissions', compact('user', 'permissionsByModule', 'rolePermissionIds', 'directPermissionIds'));
    }

    /**
     * Update direct user permissions.
     */
    public function updatePermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($request->input('permissions', []));

        ActivityLogger::log('Direct Permissions Updated', "Updated direct permissions for {$user->name}", User::class, $user->id);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Direct user permissions updated successfully.');
    }

    /**
     * Admin password reset.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        ActivityLogger::log('Password Reset', "Admin reset password for {$user->name}", User::class, $user->id);

        return back()->with('success', "Password for {$user->name} reset successfully.");
    }
}
