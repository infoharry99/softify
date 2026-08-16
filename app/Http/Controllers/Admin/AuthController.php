<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogger;

class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact an administrator.',
                ]);
            }

            $user->update(['last_login_at' => now()]);
            ActivityLogger::log('Login', 'User logged in successfully');

            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Redirect user based on exact role:
     * - Super Admin ➔ Admin Panel (/admin/dashboard)
     * - All Employees & HR ➔ Employee Panel (/employee/dashboard)
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.dashboard');
        }

        // All employees (including HR) log into Employee Panel
        return redirect()->route('employee.dashboard');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                \App\Services\AttendanceService::recordLogout($employee);
            }

            ActivityLogger::log('Logout', 'User logged out');
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show user profile page.
     */
    public function profile()
    {
        $user = Auth::user()->load(['roles.permissions', 'permissions']);
        return view('admin.profile', compact('user'));
    }

    /**
     * Update user profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => ['nullable', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|max:2048',
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210).',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $user->update($validated);
        ActivityLogger::log('Update Profile', 'User updated their basic profile information', User::class, $user->id);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        ActivityLogger::log('Password Changed', 'User changed their account password', User::class, $user->id);

        return back()->with('success', 'Password changed successfully.');
    }
}
