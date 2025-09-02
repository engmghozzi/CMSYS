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
        
        $oldValues = null;
        $newValues = null;
        
        if ($action === 'update' && $model) {
            // Get the original values before changes
            $oldValues = self::getEssentialFields($model->getRawOriginal());
            
            // Get the new values after changes
            $newValues = self::getEssentialFields($model->getAttributes());
            
            // Only include changed fields for better readability
            $changedAttributes = $model->getDirty();
            if (!empty($changedAttributes)) {
                $oldValues = array_intersect_key($oldValues, $changedAttributes);
                $newValues = array_intersect_key($newValues, $changedAttributes);
            }
        } elseif ($action === 'create' && $model) {
            $newValues = self::getEssentialFields($model->getAttributes());
        } elseif ($action === 'delete' && $model) {
            $oldValues = self::getEssentialFields($model->getAttributes());
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

        // Define which fields to include based on model type
        $allowedFields = [
            'name', 'email', 'mobile_number', 'alternate_mobile_number',
            'title', 'amount', 'status', 'client_type', 'type',
            'serial_number', 'brand', 'capacity', 'cost', 'description',
            'address', 'city', 'state', 'postal_code', 'country',
            'start_date', 'end_date', 'payment_frequency', 'total_amount',

            'warranty_expiry', 'maintenance_schedule', 'notes'
        ];
        
        $essentialFields = [];
        
        foreach ($data as $key => $value) {
            // Skip if it's not a scalar value
            if (is_array($value) || is_object($value)) {
                continue;
            }
            
            // Skip timestamp fields
            if (str_contains($key, '_at')) {
                continue;
            }
            
            // Skip foreign key fields (but keep some important ones)
            if (str_contains($key, '_by') || 
                (str_contains($key, '_id') && !in_array($key, ['id', 'client_id', 'contract_id']))) {
                continue;
            }
            
            // Only include allowed essential fields
            if (in_array($key, $allowedFields)) {
                $essentialFields[$key] = $value;
            }
        }
        
        return $essentialFields;
    }

    protected static function getActionDescription($action, $model = null)
    {
        if (!$model) {
            return ucfirst($action) . ' action performed';
        }

        $modelName = class_basename($model);
        
        switch ($action) {
            case 'create':
                $identifier = self::getModelIdentifier($model);
                return "Created new {$modelName}" . ($identifier ? ": {$identifier}" : "");
            case 'update':
                $identifier = self::getModelIdentifier($model);
                return "Updated {$modelName}" . ($identifier ? " {$identifier}" : " #{$model->id}");
            case 'delete':
                $identifier = self::getModelIdentifier($model);
                return "Deleted {$modelName}" . ($identifier ? " {$identifier}" : " #{$model->id}");
            default:
                return ucfirst($action) . " {$modelName} #{$model->id}";
        }
    }

    protected static function getModelIdentifier($model)
    {
        // Try to get a meaningful identifier for the model
        if (isset($model->name)) {
            return $model->name;
        }
        if (isset($model->title)) {
            return $model->title;
        }
        if (isset($model->serial_number)) {
            return "SN: {$model->serial_number}";
        }
        if (isset($model->email)) {
            return $model->email;
        }
        return null;
    }

    // Manual logging method for custom actions
    public static function logCustomAction($action, $description, $model = null, $additionalData = [])
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
            'old_values' => $additionalData['old_values'] ?? null,
            'new_values' => $additionalData['new_values'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    // Method to log specific field changes
    public static function logFieldChange($model, $field, $oldValue, $newValue, $description = null)
    {
        if (!Auth::check()) {
            return;
        }

        $desc = $description ?: "Changed {$field} from '{$oldValue}' to '{$newValue}'";
        
        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $desc,
            'old_values' => [$field => $oldValue],
            'new_values' => [$field => $newValue],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
} 