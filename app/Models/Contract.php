<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Contract extends Model
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory, Loggable;
    protected $guarded = ['id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->where('status', 'Paid')->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        $remaining = $this->total_amount - $this->paid_amount;
        return max($remaining, 0); // Prevent negative values
    }

    public function getIsFullyCollectedAttribute()
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }



}
