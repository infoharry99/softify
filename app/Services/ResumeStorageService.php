<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResumeStorageService
{
    /**
     * Resolve the absolute local filepath for a candidate resume.
     * Searches:
     *  1. Local public storage disk
     *  2. Configured shared storage path in .env (SHARED_STORAGE_PATH)
     *  3. Sibling folders on disk (e.g. apply.talentifyy.com, app.talentifyy.com, resdex.talentifyy.com)
     *  4. HTTP Remote Sync from other sibling domains (apply, resdex, app)
     *
     * @param string|null $fileKey e.g. "candidate_resumes/xyz.pdf"
     * @return string|null Absolute filesystem path to the file, or null if not found
     */
    public static function resolveResumeFilePath(?string $fileKey): ?string
    {
        if (empty($fileKey)) {
            return null;
        }

        // Clean file key against traversal
        $fileKey = ltrim(str_replace(['../', '..\\', "\0"], '', $fileKey), '/\\');

        // 1. Check local public disk
        if (Storage::disk('public')->exists($fileKey)) {
            return Storage::disk('public')->path($fileKey);
        }

        // 2. Check explicit shared storage path if defined in .env
        $sharedRoot = env('SHARED_STORAGE_PATH') ?: env('CANDIDATE_RESUME_STORAGE_PATH');
        if (!empty($sharedRoot)) {
            $sharedRoot = rtrim($sharedRoot, '/\\');
            $candidates = [
                $sharedRoot . '/' . $fileKey,
                $sharedRoot . '/app/public/' . $fileKey,
                $sharedRoot . '/public/storage/' . $fileKey,
            ];
            foreach ($candidates as $candPath) {
                if (file_exists($candPath) && is_file($candPath)) {
                    self::cacheFileLocally($fileKey, file_get_contents($candPath));
                    return Storage::disk('public')->path($fileKey);
                }
            }
        }

        // 3. Auto-discover sibling domain folders on the server
        $baseDir = base_path();
        $parentDir = dirname($baseDir);

        if ($parentDir && is_dir($parentDir)) {
            $siblingFolderNames = [
                'apply.talentifyy.com',
                'resdex.talentifyy.com',
                'app.talentifyy.com',
                'public_html',
                'softify.physiopii.in',
            ];

            // Also scan parent directory for matching domain names
            try {
                $dirEntries = @scandir($parentDir) ?: [];
                foreach ($dirEntries as $entry) {
                    if ($entry !== '.' && $entry !== '..' && is_dir($parentDir . '/' . $entry)) {
                        if (stripos($entry, 'talentifyy') !== false || stripos($entry, 'apply') !== false || stripos($entry, 'resdex') !== false) {
                            if (!in_array($entry, $siblingFolderNames)) {
                                $siblingFolderNames[] = $entry;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Ignore scan permission restrictions
            }

            foreach ($siblingFolderNames as $sibling) {
                $siblingBase = $parentDir . '/' . $sibling;
                if ($siblingBase === $baseDir || !is_dir($siblingBase)) {
                    continue;
                }

                $siblingPaths = [
                    $siblingBase . '/storage/app/public/' . $fileKey,
                    $siblingBase . '/public/storage/' . $fileKey,
                ];

                foreach ($siblingPaths as $sFile) {
                    if (file_exists($sFile) && is_file($sFile)) {
                        $content = @file_get_contents($sFile);
                        if ($content !== false && strlen($content) > 0) {
                            self::cacheFileLocally($fileKey, $content);
                            return Storage::disk('public')->path($fileKey);
                        }
                    }
                }
            }
        }

        // 4. Remote HTTP Sync from other sibling domains
        $currentHost = request()->getHost();
        $remoteDomains = array_filter(array_map('trim', explode(',', env('RESUME_REMOTE_DOMAINS', ''))));
        if (empty($remoteDomains)) {
            $remoteDomains = [
                'https://apply.talentifyy.com',
                'https://resdex.talentifyy.com',
                'https://app.talentifyy.com',
                'https://softify.physiopii.in',
            ];
        }

        $syncToken = self::generateSyncToken($fileKey);

        foreach ($remoteDomains as $domain) {
            $parsedHost = parse_url($domain, PHP_URL_HOST);
            if ($parsedHost === $currentHost) {
                continue;
            }

            $endpoints = [
                rtrim($domain, '/') . '/internal/sync-resume?file=' . urlencode($fileKey) . '&token=' . $syncToken,
                rtrim($domain, '/') . '/storage/' . $fileKey,
            ];

            foreach ($endpoints as $url) {
                try {
                    $response = Http::withoutVerifying()
                        ->timeout(4)
                        ->get($url);

                    if ($response->successful()) {
                        $body = $response->body();
                        // Verify body is not an error HTML page
                        if (strlen($body) > 100 && !str_starts_with(trim($body), '<!DOCTYPE') && !str_starts_with(trim($body), '<html')) {
                            self::cacheFileLocally($fileKey, $body);
                            return Storage::disk('public')->path($fileKey);
                        }
                    }
                } catch (\Throwable $e) {
                    // Continue to next endpoint/domain
                }
            }
        }

        return null;
    }

    /**
     * Cache file contents locally on public disk so future requests are instant.
     */
    public static function cacheFileLocally(string $fileKey, string $content): void
    {
        try {
            Storage::disk('public')->put($fileKey, $content);
        } catch (\Throwable $e) {
            Log::warning("Could not cache resume locally: {$fileKey} - " . $e->getMessage());
        }
    }

    /**
     * Immediately synchronize newly uploaded resume across detected sibling folders.
     * Called whenever a resume is uploaded on apply, resdex, or app.
     */
    public static function syncUploadedResume(?string $fileKey): void
    {
        if (empty($fileKey)) {
            return;
        }

        $fileKey = ltrim(str_replace(['../', '..\\', "\0"], '', $fileKey), '/\\');

        if (!Storage::disk('public')->exists($fileKey)) {
            return;
        }

        $localPath = Storage::disk('public')->path($fileKey);
        $content = @file_get_contents($localPath);
        if ($content === false || strlen($content) === 0) {
            return;
        }

        // Sync to explicit shared path if configured
        $sharedRoot = env('SHARED_STORAGE_PATH') ?: env('CANDIDATE_RESUME_STORAGE_PATH');
        if (!empty($sharedRoot)) {
            $sharedDest = rtrim($sharedRoot, '/\\') . '/app/public/' . $fileKey;
            @mkdir(dirname($sharedDest), 0755, true);
            @file_put_contents($sharedDest, $content);
        }

        // Sync to sibling domain folders on the server
        $baseDir = base_path();
        $parentDir = dirname($baseDir);

        if ($parentDir && is_dir($parentDir)) {
            $siblingFolderNames = [
                'apply.talentifyy.com',
                'resdex.talentifyy.com',
                'app.talentifyy.com',
                'public_html',
                'softify.physiopii.in',
            ];

            try {
                $dirEntries = @scandir($parentDir) ?: [];
                foreach ($dirEntries as $entry) {
                    if ($entry !== '.' && $entry !== '..' && is_dir($parentDir . '/' . $entry)) {
                        if (stripos($entry, 'talentifyy') !== false || stripos($entry, 'apply') !== false || stripos($entry, 'resdex') !== false) {
                            if (!in_array($entry, $siblingFolderNames)) {
                                $siblingFolderNames[] = $entry;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            foreach ($siblingFolderNames as $sibling) {
                $siblingBase = $parentDir . '/' . $sibling;
                if ($siblingBase === $baseDir || !is_dir($siblingBase)) {
                    continue;
                }

                $targetPath = $siblingBase . '/storage/app/public/' . $fileKey;
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                @file_put_contents($targetPath, $content);

                // Also sync to public/storage if symlink is not in place
                $publicTargetPath = $siblingBase . '/public/storage/' . $fileKey;
                $publicTargetDir = dirname($publicTargetPath);
                if (!is_dir($publicTargetDir) && !is_link($siblingBase . '/public/storage')) {
                    @mkdir($publicTargetDir, 0755, true);
                    @file_put_contents($publicTargetPath, $content);
                }
            }
        }
    }

    /**
     * Generate security token for internal resume sync.
     */
    public static function generateSyncToken(string $fileKey): string
    {
        $secret = env('INTERNAL_SYNC_SECRET', 'talentifyy_ats_sync_key_2026');
        return hash('sha256', $fileKey . '|' . $secret);
    }

    /**
     * Validate incoming token for internal resume sync.
     */
    public static function validateSyncToken(string $fileKey, ?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $expected = self::generateSyncToken($fileKey);
        if (hash_equals($expected, $token)) {
            return true;
        }

        // Secondary fallback checking app key
        $appKeyExpected = hash('sha256', $fileKey . '|' . config('app.key'));
        return hash_equals($appKeyExpected, $token);
    }
}
