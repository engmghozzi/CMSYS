<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Loggable;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Loggable;

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

    // Features are now inherited from role only - no per-user customization
    // public function features() - REMOVED

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

    public function hasFeature($featureCode)
    {
        // Super admin has all features
        if ($this->role && $this->role->name === 'super_admin') {
            return true;
        }

        // Check if user's role has the feature
        if ($this->role) {
            return $this->role
                ->features()
                ->where('name', $featureCode)
                ->wherePivot('is_granted', true)
                ->exists();
        }

        return false;
    }

    public function hasAnyFeature($featureNames)
    {
        // Super admin has all features
        if ($this->role && $this->role->name === 'super_admin') {
            return true;
        }

        // Check if user's role has any of the features
        if ($this->role) {
            return $this->role
                ->features()
                ->whereIn('name', $featureNames)
                ->wherePivot('is_granted', true)
                ->exists();
        }

        return false;
    }

    public function hasPermission($permission)
    {
        // Cache key for this permission check
        $cacheKey = "user_permission_{$this->id}_{$permission}";
        
        // Try to get from cache first
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Super admin has all permissions
        if ($this->role && $this->role->name === 'super_admin') {
            Cache::put($cacheKey, true, now()->addMinutes(30));
            return true;
        }

        // Check if user's role has the permission
        $hasPermission = false;
        if ($this->role) {
            // Check if role has this feature granted
            $roleFeature = $this->role->features()->where('features.name', $permission)->first();
            if ($roleFeature && $roleFeature->pivot->is_granted) {
                $hasPermission = true;
            }
            
            // Also check the legacy permissions array for backward compatibility
            if (!$hasPermission && $this->role->hasPermission($permission)) {
                $hasPermission = true;
            }
        }

        // Cache the result for 30 minutes
        Cache::put($cacheKey, $hasPermission, now()->addMinutes(30));
        
        return $hasPermission;
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

    // User-specific feature management removed - features come only from roles
    // public function grantFeature() - REMOVED
    // public function revokeFeature() - REMOVED

    // Enhanced permission management methods
    public function getAllPermissions()
    {
        $permissions = collect();
        
        // Get role permissions only
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
        // Check if role has permission
        if ($this->role) {
            $roleFeature = $this->role->features()->where('features.name', $permissionName)->first();
            if ($roleFeature && $roleFeature->pivot->is_granted) {
                return 'role_inherited';
            }
        }
        
        return 'none';
    }

    // User-specific permission management removed - permissions come only from roles
    // public function syncPermissions() - REMOVED
    // public function clearPermissionOverrides() - REMOVED

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

    /**
     * Check if the user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
