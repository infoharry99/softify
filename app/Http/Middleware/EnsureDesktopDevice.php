<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDesktopDevice
{
    /**
     * Handle an incoming request.
     * Restricts platform access to Desktop / Laptop computers only for employees.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Never block public candidate application routes or APIs
        if ($this->isExemptRoute($request)) {
            return $next($request);
        }

        // 2. Check if the device is a mobile or tablet
        if (self::isMobileDevice($request)) {
            // Super-Admin is exempt so system administrators can inspect from mobile if needed
            if (Auth::check() && Auth::user()->hasRole('super-admin')) {
                return $next($request);
            }

            // If user is authenticated (employee, HR, Talent Acquisition, Data Entry, etc.)
            if (Auth::check()) {
                return response()->view('errors.desktop_only', [
                    'deviceInfo' => self::getDetectedMobileDevice($request),
                    'user' => Auth::user(),
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the incoming request is exempt from desktop restriction.
     */
    protected function isExemptRoute(Request $request): bool
    {
        // Dedicated Public Career Domain
        $publicCareerDomain = env('PUBLIC_CAREER_DOMAIN', 'apply.talentifyy.com');
        if ($request->getHost() === $publicCareerDomain) {
            return true;
        }

        // Public candidate application and status routes, logout, assets, and APIs
        if ($request->is(
            'careers/*',
            'apply',
            'apply/*',
            'candidate-registration',
            'success',
            'careers/success',
            'logout',
            'api/*',
            'run-*',
            'assets/*',
            'images/*',
            'css/*',
            'js/*'
        )) {
            return true;
        }

        return false;
    }

    /**
     * Detect whether request is coming from a mobile or tablet device.
     */
    public static function isMobileDevice(Request $request): bool
    {
        // 1. Modern Chromium Client Hints Header
        if ($request->header('Sec-CH-UA-Mobile') === '?1') {
            return true;
        }

        $userAgent = strtolower($request->header('User-Agent', ''));
        if (empty($userAgent)) {
            return false;
        }

        // 2. Mobile User Agent Keywords
        $mobileKeywords = [
            'mobile', 'android', 'iphone', 'ipod', 'ipad', 'silk/', 'kindle',
            'blackberry', 'opera mini', 'opera mobi', 'webos', 'windows phone',
            'iemobile', 'wpdesktop', 'fennec', 'series60', 'symbian', 'samsung',
            'nokia', 'motorola', 'huawei', 'oppo', 'vivo', 'xiaomi', 'redmi',
            'realme', 'oneplus', 'poco', 'miui'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        // 3. WAP Accept headers
        $accept = strtolower($request->header('Accept', ''));
        if (str_contains($accept, 'text/vnd.wap.wml') || str_contains($accept, 'application/vnd.wap.xhtml+xml')) {
            return true;
        }

        return false;
    }

    /**
     * Get human-readable description of detected mobile device.
     */
    public static function getDetectedMobileDevice(Request $request): string
    {
        $ua = $request->header('User-Agent', '');
        if (preg_match('/iPhone/i', $ua)) {
            return 'Apple iPhone (iOS)';
        }
        if (preg_match('/iPad/i', $ua)) {
            return 'Apple iPad (iPadOS)';
        }
        if (preg_match('/Android/i', $ua)) {
            if (preg_match('/Android\s+([0-9\.]+)/i', $ua, $matches)) {
                return 'Android Mobile Device (OS ' . $matches[1] . ')';
            }
            return 'Android Mobile Device';
        }
        if (preg_match('/Windows Phone/i', $ua)) {
            return 'Windows Phone';
        }
        return 'Mobile Phone / Tablet Device';
    }
}
