<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;
    protected $guarded= ['id'];

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
    
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Contract::class);
    }

    public function machines()
    {
        return $this->hasManyThrough(Machine::class, Contract::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
