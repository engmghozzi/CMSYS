<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService
{
    /**
     * Log a custom action with detailed information
     */
    public static function logAction($action, $description, $model = null, $additionalData = [])
    {
        if (!Auth::check()) {
            return;
        }

        return Log::create([
            'user_id' => Auth::id(),
            'action_type' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'old_values' => $additionalData['old_values'] ?? null,
            'new_values' => $additionalData['new_values'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a login action
     */
    public static function logLogin($user, $description = null)
    {
        $desc = $description ?: "User {$user->name} logged in";
        
        return self::logAction('login', $desc, $user);
    }

    /**
     * Log a logout action
     */
    public static function logLogout($user, $description = null)
    {
        $desc = $description ?: "User {$user->name} logged out";
        
        return self::logAction('logout', $desc, $user);
    }

    /**
     * Log a view action
     */
    public static function logView($model, $description = null)
    {
        $modelName = class_basename($model);
        $desc = $description ?: "Viewed {$modelName} #{$model->id}";
        
        return self::logAction('view', $desc, $model);
    }

    /**
     * Log a bulk action
     */
    public static function logBulkAction($action, $modelType, $affectedIds, $description, $additionalData = [])
    {
        $desc = $description ?: "Bulk {$action} on {$modelType} records: " . count($affectedIds) . " affected";
        
        return self::logAction($action, $desc, null, array_merge($additionalData, [
            'affected_ids' => $affectedIds,
            'model_type' => $modelType,
        ]));
    }

    /**
     * Log a system action (no user context)
     */
    public static function logSystemAction($action, $description, $additionalData = [])
    {
        return Log::create([
            'user_id' => null,
            'action_type' => $action,
            'model_type' => null,
            'model_id' => null,
            'description' => $description,
            'old_values' => $additionalData['old_values'] ?? null,
            'new_values' => $additionalData['new_values'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a permission change
     */
    public static function logPermissionChange($user, $feature, $granted, $description = null)
    {
        $action = $granted ? 'grant' : 'revoke';
        $desc = $description ?: "Permission '{$feature}' {$action}ed for user {$user->name}";
        
        return self::logAction($action, $desc, $user, [
            'feature' => $feature,
            'granted' => $granted,
        ]);
    }

    /**
     * Log a role change
     */
    public static function logRoleChange($user, $oldRole, $newRole, $description = null)
    {
        $oldRoleName = $oldRole ? $oldRole->name : 'None';
        $newRoleName = $newRole ? $newRole->name : 'None';
        $desc = $description ?: "Role changed from '{$oldRoleName}' to '{$newRoleName}' for user {$user->name}";
        
        return self::logAction('role_change', $desc, $user, [
            'old_role' => $oldRoleName,
            'new_role' => $newRoleName,
        ]);
    }
}
