<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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
        return $this->features()->where('name', $featureName)->wherePivot('is_granted', true)->exists();
    }

    public function hasAnyFeature($featureNames)
    {
        return $this->features()->whereIn('name', $featureNames)->wherePivot('is_granted', true)->exists();
    }

    public function hasPermission($permission)
    {
        // First check if user has this specific feature granted
        $userFeature = $this->features()->where('name', $permission)->first();
        
        // If user has this feature explicitly granted, return true
        if ($userFeature && $userFeature->pivot->is_granted) {
            return true;
        }
        
        // If user has this feature explicitly revoked (is_granted = false), return false
        if ($userFeature && !$userFeature->pivot->is_granted) {
            return false;
        }
        
        // If user doesn't have this feature explicitly set, check role permissions
        if ($this->role && $this->role->hasPermission($permission)) {
            return true;
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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
