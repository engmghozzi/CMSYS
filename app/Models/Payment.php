<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory, Loggable;
    protected $guarded = ['id'];
    
    protected $casts = [
        'payment_date' => 'date',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    // This method is used to get the contract number for display purposes
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

  
}
