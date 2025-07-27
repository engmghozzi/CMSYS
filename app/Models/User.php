<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'user_features')
                    ->withPivot('is_granted')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Permission methods
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasAnyRole($roleNames)
    {
        return $this->role && in_array($this->role->name, $roleNames);
    }

    public function hasFeature($featureName)
    {
        return $this->features()->where('features.name', $featureName)->wherePivot('is_granted', true)->exists();
    }

    public function hasAnyFeature($featureNames)
    {
        return $this->features()->whereIn('features.name', $featureNames)->wherePivot('is_granted', true)->exists();
    }

    public function hasPermission($permission)
    {
        // First check if user has this specific feature granted
        $userFeature = $this->features()->where('features.name', $permission)->first();
        
        // If user has this feature explicitly granted, return true
        if ($userFeature && $userFeature->pivot->is_granted) {
            return true;
        }
        
        // If user has this feature explicitly revoked (is_granted = false), return false
        if ($userFeature && !$userFeature->pivot->is_granted) {
            return false;
        }
        
        // If user doesn't have this feature explicitly set, check role permissions
        if ($this->role) {
            // Check if role has this feature granted
            $roleFeature = $this->role->features()->where('features.name', $permission)->first();
            if ($roleFeature && $roleFeature->pivot->is_granted) {
            return true;
            }
            
            // Also check the legacy permissions array for backward compatibility
            if ($this->role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAnyOfPermissions($permissions)
    {
        return $this->hasAnyPermission($permissions);
    }

    public function grantFeature($featureId)
    {
        $this->features()->syncWithoutDetaching([$featureId => ['is_granted' => true]]);
    }

    public function revokeFeature($featureId)
    {
        $this->features()->updateExistingPivot($featureId, ['is_granted' => false]);
    }

    // Enhanced permission management methods
    public function getAllPermissions()
    {
        $permissions = collect();
        
        // Get role permissions
        if ($this->role) {
            $rolePermissions = $this->role->features()->wherePivot('is_granted', true)->get();
            foreach ($rolePermissions as $permission) {
                $permissions->push([
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                    'category' => $permission->category,
                    'source' => 'role',
                    'role_name' => $this->role->name
                ]);
            }
        }
        
        // Get user-specific permissions (overrides)
        $userPermissions = $this->features()->get();
        foreach ($userPermissions as $permission) {
            $permissions->push([
                'name' => $permission->name,
                'display_name' => $permission->display_name,
                'category' => $permission->category,
                'source' => 'user',
                'is_granted' => $permission->pivot->is_granted
            ]);
        }
        
        return $permissions->unique('name');
    }

    public function getEffectivePermissions()
    {
        $effectivePermissions = collect();
        $allFeatures = Feature::all();
        
        foreach ($allFeatures as $feature) {
            $hasPermission = $this->hasPermission($feature->name);
            $effectivePermissions->push([
                'name' => $feature->name,
                'display_name' => $feature->display_name,
                'category' => $feature->category,
                'has_permission' => $hasPermission,
                'source' => $this->getPermissionSource($feature->name)
            ]);
        }
        
        return $effectivePermissions;
    }

    private function getPermissionSource($permissionName)
    {
        // Check if user has explicit override
        $userFeature = $this->features()->where('features.name', $permissionName)->first();
        if ($userFeature) {
            return $userFeature->pivot->is_granted ? 'user_granted' : 'user_revoked';
        }
        
        // Check if role has permission
        if ($this->role) {
            $roleFeature = $this->role->features()->where('features.name', $permissionName)->first();
            if ($roleFeature && $roleFeature->pivot->is_granted) {
                return 'role_inherited';
            }
        }
        
        return 'none';
    }

    public function syncPermissions($permissions)
    {
        $allFeatures = Feature::all();
        
        foreach ($allFeatures as $feature) {
            if (in_array($feature->id, $permissions)) {
                $this->grantFeature($feature->id);
            } else {
                $this->revokeFeature($feature->id);
            }
        }
    }

    public function clearPermissionOverrides()
    {
        $this->features()->detach();
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        if (!$this->name) {
            return 'U';
        }
        
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
