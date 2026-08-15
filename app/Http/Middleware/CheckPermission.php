<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        if (!$user || $user->status !== 'active') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is inactive or unauthenticated.'
                ], 403);
            }
            abort(403, 'Account is inactive or unauthenticated.');
        }

        // Super Admin bypasses all checks
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // HR role has access to all HR permissions
        if ($user->hasRole('hr')) {
            foreach ($permissions as $permission) {
                if (str_starts_with($permission, 'hr.')) {
                    return $next($request);
                }
            }
        }

        // Data Entry role has access to candidate view and create
        if ($user->hasRole('data-entry')) {
            foreach ($permissions as $permission) {
                if (in_array($permission, ['hr.view', 'hr.create'])) {
                    return $next($request);
                }
            }
        }

        // Talent Acquisition role has access to candidate view
        if ($user->hasRole('talent-acquisition')) {
            foreach ($permissions as $permission) {
                if ($permission === 'hr.view') {
                    return $next($request);
                }
            }
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
