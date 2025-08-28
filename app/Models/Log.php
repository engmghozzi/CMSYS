<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_type',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    // Action type constants
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_VIEW = 'view';
    const ACTION_GRANT = 'grant';
    const ACTION_REVOKE = 'revoke';
    const ACTION_ROLE_CHANGE = 'role_change';
    const ACTION_BULK_UPDATE = 'bulk_update';
    const ACTION_BULK_DELETE = 'bulk_delete';
    const ACTION_SYSTEM = 'system';

    // Get action types for filtering
    public static function getActionTypes()
    {
        return [
            self::ACTION_CREATE => 'Create',
            self::ACTION_UPDATE => 'Update',
            self::ACTION_DELETE => 'Delete',
            self::ACTION_LOGIN => 'Login',
            self::ACTION_LOGOUT => 'Logout',
            self::ACTION_VIEW => 'View',
            self::ACTION_GRANT => 'Grant Permission',
            self::ACTION_REVOKE => 'Revoke Permission',
            self::ACTION_ROLE_CHANGE => 'Role Change',
            self::ACTION_BULK_UPDATE => 'Bulk Update',
            self::ACTION_BULK_DELETE => 'Bulk Delete',
            self::ACTION_SYSTEM => 'System Action',
        ];
    }

    // Get model types for filtering
    public static function getModelTypes()
    {
        return [
            'App\Models\Contract' => 'Contract',
            'App\Models\Payment' => 'Payment',
            'App\Models\Machine' => 'Machine',
            'App\Models\Client' => 'Client',
            'App\Models\User' => 'User',
            'App\Models\Address' => 'Address',
            'App\Models\Role' => 'Role',
            'App\Models\Feature' => 'Feature',
            'App\Models\Visit' => 'Visit',
        ];
    }

    // Scope for filtering by action type
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    // Scope for filtering by model type
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    // Scope for filtering by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope for filtering by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Get formatted action type
    public function getFormattedActionTypeAttribute()
    {
        return self::getActionTypes()[$this->action_type] ?? ucfirst($this->action_type);
    }

    // Get formatted model type
    public function getFormattedModelTypeAttribute()
    {
        $modelTypes = self::getModelTypes();
        return $modelTypes[$this->model_type] ?? class_basename($this->model_type);
    }

    // Check if log has changes
    public function getHasChangesAttribute()
    {
        return !empty($this->old_values) || !empty($this->new_values);
    }

    // Get change summary for display
    public function getChangeSummaryAttribute()
    {
        if ($this->action_type === 'create' && $this->new_values) {
            return 'Created with ' . count($this->new_values) . ' fields';
        } elseif ($this->action_type === 'update' && $this->old_values && $this->new_values) {
            return 'Updated ' . count($this->new_values) . ' fields';
        } elseif ($this->action_type === 'delete' && $this->old_values) {
            return 'Deleted with ' . count($this->old_values) . ' fields';
        }
        
        return 'No changes recorded';
    }
}
