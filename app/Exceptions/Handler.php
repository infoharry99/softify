<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Smart 404 Redirection:
        // If user is authenticated, redirect to their role dashboard.
        // If user is a guest, redirect to login page.
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Not Found'], 404);
            }

            $publicCareerDomain = env('PUBLIC_CAREER_DOMAIN', 'apply.talentifyy.com');
            if ($request->getHost() === $publicCareerDomain) {
                return redirect()->route('careers.apply.domain');
            }

            if (auth()->check()) {
                $user = auth()->user();
                if (env('PORTAL_MODE') === 'ats') {
                    return redirect()->route('admin.candidate_search.form');
                }
                if ($user && $user->hasRole('super-admin')) {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('employee.dashboard');
            }

            return redirect()->route('login');
        });
    }
}
