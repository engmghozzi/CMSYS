<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Feature extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
        'action',
        'resource',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships - users removed since features are now role-based only
    // public function users() - REMOVED

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_features')
                    ->withPivot('is_granted')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByResource($query, $resource)
    {
        return $query->where('resource', $resource);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->action && $this->resource 
            ? "{$this->action}_{$this->resource}" 
            : $this->name;
    }

    // Enhanced feature management methods
    public function getUsageStats()
    {
        $roleCount = $this->roles()->count();
        $grantedRoleCount = $this->roles()->wherePivot('is_granted', true)->count();
        
        // Calculate user statistics based on roles
        $totalUsers = 0;
        $grantedUsers = 0;
        $revokedUsers = 0;
        
        if ($roleCount > 0) {
            // Get all users who have roles that include this feature
            $usersWithFeature = \App\Models\User::whereHas('role', function($query) {
                $query->whereHas('features', function($subQuery) {
                    $subQuery->where('features.id', $this->id);
                });
            })->count();
            
            $usersWithGrantedFeature = \App\Models\User::whereHas('role', function($query) {
                $query->whereHas('features', function($subQuery) {
                    $subQuery->where('features.id', $this->id)
                             ->where('role_features.is_granted', true);
                });
            })->count();
            
            $totalUsers = $usersWithFeature;
            $grantedUsers = $usersWithGrantedFeature;
            $revokedUsers = $totalUsers - $grantedUsers;
        }
        
        return [
            'total_users' => $totalUsers,
            'granted_users' => $grantedUsers,
            'revoked_users' => $revokedUsers,
            'total_roles' => $roleCount,
            'granted_roles' => $grantedRoleCount,
            'revoked_roles' => $roleCount - $grantedRoleCount
        ];
    }

    // User assignment methods removed since features are now role-based only
    // public function getAssignedUsers() - REMOVED

    public function getAssignedRoles()
    {
        return $this->roles()->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'is_granted' => $role->pivot->is_granted
            ];
        });
    }

    public function canBeDeleted()
    {
        return $this->roles()->count() === 0;
    }

    public function getCategoryDisplayName()
    {
        return ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getActionDisplayName()
    {
        $actions = [
            'create' => 'Create',
            'read' => 'View',
            'update' => 'Edit',
            'delete' => 'Delete',
            'manage' => 'Manage'
        ];
        
        return $actions[$this->action] ?? ucfirst($this->action);
    }

    public function getResourceDisplayName()
    {
        $resources = [
            'users' => 'Users',
            'roles' => 'Roles',
            'features' => 'Features',
            'clients' => 'Clients',
            'contracts' => 'Contracts',
            'payments' => 'Payments',

            'addresses' => 'Addresses',
            'reports' => 'Reports',
            'logs' => 'Logs',
            'settings' => 'Settings'
        ];
        
        return $resources[$this->resource] ?? ucfirst($this->resource);
    }
}
