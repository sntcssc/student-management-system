<?php

namespace App\Logging;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class UserActivityLogger
{
    public static function log($userId, $action, $description)
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
        ]);

        Log::info("Activity: {$action}", [
            'user_id' => $userId,
            'description' => $description,
        ]);
    }
}