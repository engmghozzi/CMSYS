<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory, Loggable;
    protected $guarded=['id'];

    protected $fillable = [
        'name',
        'mobile_number',
        'alternate_mobile_number',
        'client_type',
        'status',
        'created_by',
        'updated_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    public function getStatusBadgeClasses()
    {
        return match($this->status) {
            'vip' => 'bg-yellow-100 text-yellow-800',
            'ordinary' => 'bg-blue-100 text-blue-800',
            'blocked' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusIcon()
    {
        return match($this->status) {
            'vip' => 'VIP',
            'ordinary' => 'Ordinary',
            'blocked' => 'Blocked',
            default => 'Unknown'
        };
    }

    /**
     * Check if the client is blocked
     */
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }
}
