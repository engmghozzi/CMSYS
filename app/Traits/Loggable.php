<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Loggable
{
    protected static function bootLoggable()
    {
        static::created(function ($model) {
            self::logAction('create', $model);
        });

        static::updated(function ($model) {
            self::logAction('update', $model);
        });

        static::deleted(function ($model) {
            self::logAction('delete', $model);
        });
    }

    protected static function logAction($action, $model = null)
    {
        if (!Auth::check()) {
            return;
        }

        $description = self::getActionDescription($action, $model);
        
        // Only record essential fields instead of all model data
        $oldValues = null;
        $newValues = null;
        
        if ($action === 'update' && $model) {
            // Get only the basic database attributes, exclude all relationships
            $oldValues = self::getEssentialFields($model->getRawOriginal());
            $newValues = self::getEssentialFields($model->getRawOriginal());
            
            // Only include the changed attributes
            $changedAttributes = $model->getDirty();
            if (!empty($changedAttributes)) {
                $newValues = self::getEssentialFields(array_merge($model->getRawOriginal(), $changedAttributes));
            }
        } elseif ($action === 'create' && $model) {
            $newValues = self::getEssentialFields($model->getRawOriginal());
        }
        
        Log::create([
            'user_id' => Auth::id(),
            'action_type' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected static function getEssentialFields($data)
    {
        if (!$data) {
            return null;
        }

        // Only include essential scalar fields, exclude ALL relationships and complex data
        $essentialFields = ['id'];
        
        // Define which fields to include based on model type
        $allowedFields = [
            'name', 'email', 'mobile_number', 'alternate_mobile_number',
            'title', 'amount', 'status', 'client_type', 'type',
            'serial_number', 'brand', 'capacity', 'cost'
        ];
        
        foreach ($data as $key => $value) {
            // Skip if it's not a scalar value
            if (is_array($value) || is_object($value)) {
                continue;
            }
            
            // Skip timestamp fields
            if (str_contains($key, '_at')) {
                continue;
            }
            
            // Skip foreign key fields
            if (str_contains($key, '_by') || str_contains($key, '_id')) {
                continue;
            }
            
            // Only include allowed essential fields
            if (in_array($key, $allowedFields)) {
                $essentialFields[] = $key;
            }
        }
        
        // Return only the essential fields
        return array_intersect_key($data, array_flip($essentialFields));
    }

    protected static function getActionDescription($action, $model = null)
    {
        if (!$model) {
            return ucfirst($action) . ' action performed';
        }

        $modelName = class_basename($model);
        
        switch ($action) {
            case 'create':
                return "Created new {$modelName}";
            case 'update':
                return "Updated {$modelName} #{$model->id}";
            case 'delete':
                return "Deleted {$modelName} #{$model->id}";
            default:
                return ucfirst($action) . " {$modelName} #{$model->id}";
        }
    }

    // Manual logging method for custom actions
    public static function logCustomAction($action, $description, $model = null)
    {
        if (!Auth::check()) {
            return;
        }

        Log::create([
            'user_id' => Auth::id(),
            'action_type' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
} 