<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

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

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_features')
                    ->withPivot('is_granted')
                    ->withTimestamps();
    }

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
        $userCount = $this->users()->count();
        $roleCount = $this->roles()->count();
        $grantedUserCount = $this->users()->wherePivot('is_granted', true)->count();
        $grantedRoleCount = $this->roles()->wherePivot('is_granted', true)->count();
        
        return [
            'total_users' => $userCount,
            'total_roles' => $roleCount,
            'granted_users' => $grantedUserCount,
            'granted_roles' => $grantedRoleCount,
            'revoked_users' => $userCount - $grantedUserCount,
            'revoked_roles' => $roleCount - $grantedRoleCount
        ];
    }

    public function getAssignedUsers()
    {
        return $this->users()->with('role')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->name : null,
                'is_granted' => $user->pivot->is_granted
            ];
        });
    }

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
        return $this->users()->count() === 0 && $this->roles()->count() === 0;
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
            'machines' => 'Machines',
            'addresses' => 'Addresses',
            'reports' => 'Reports',
            'logs' => 'Logs',
            'settings' => 'Settings'
        ];
        
        return $resources[$this->resource] ?? ucfirst($this->resource);
    }
}
