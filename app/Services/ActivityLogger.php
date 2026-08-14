<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Record an activity log entry.
     *
     * @param string $action
     * @param string|null $description
     * @param string|null $targetType
     * @param int|null $targetId
     * @return ActivityLog
     */
    public static function log(string $action, ?string $description = null, ?string $targetType = null, ?int $targetId = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
