<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Machine extends Model
{
    /** @use HasFactory<\Database\Factories\MachineFactory> */
    use HasFactory, Loggable;
    protected $guarded = ['id'];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
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
