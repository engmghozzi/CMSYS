<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'role_features')
                    ->withPivot('is_granted')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }
        
        return in_array($permission, $this->permissions);
    }

    public function hasAnyPermission($permissions)
    {
        if (!$this->permissions) {
            return false;
        }
        
        return !empty(array_intersect($permissions, $this->permissions));
    }

    public function grantFeature($featureId)
    {
        $this->features()->syncWithoutDetaching([$featureId => ['is_granted' => true]]);
    }

    public function revokeFeature($featureId)
    {
        $this->features()->updateExistingPivot($featureId, ['is_granted' => false]);
    }

    // Enhanced role management methods
    public function getAllPermissions()
    {
        $permissions = collect();
        
        // Get feature-based permissions
        $featurePermissions = $this->features()->wherePivot('is_granted', true)->get();
        foreach ($featurePermissions as $permission) {
            $permissions->push([
                'name' => $permission->name,
                'display_name' => $permission->display_name,
                'category' => $permission->category,
                'source' => 'feature'
            ]);
        }
        
        // Get legacy array-based permissions
        if ($this->permissions) {
            foreach ($this->permissions as $permission) {
                $permissions->push([
                    'name' => $permission,
                    'display_name' => $permission,
                    'category' => 'legacy',
                    'source' => 'array'
                ]);
            }
        }
        
        return $permissions->unique('name');
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

    public function getUsersCount()
    {
        return $this->users()->count();
    }

    public function canBeDeleted()
    {
        return $this->users()->count() === 0;
    }

    public function getEffectivePermissions()
    {
        $effectivePermissions = collect();
        $allFeatures = Feature::all();
        
        foreach ($allFeatures as $feature) {
            $hasPermission = $this->features()->where('features.name', $feature->name)->wherePivot('is_granted', true)->exists();
            $effectivePermissions->push([
                'name' => $feature->name,
                'display_name' => $feature->display_name,
                'category' => $feature->category,
                'has_permission' => $hasPermission
            ]);
        }
        
        return $effectivePermissions;
    }
}
