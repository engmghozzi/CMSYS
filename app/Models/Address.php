<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory, Loggable;
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



    /**
     * Check if this address can have a new contract
     * Returns true if there are no active contracts
     */
    public function canHaveNewContract()
    {
        return !$this->contracts()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->exists();
    }

    /**
     * Get the active contract for this address
     */
    public function getActiveContract()
    {
        return $this->contracts()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();
    }

    /**
     * Get all contracts for this address ordered by creation date
     */
    public function getAllContractsOrdered()
    {
        return $this->contracts()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all contracts for this address with no status filtering
     * This ensures we get active, expired, and cancelled contracts
     */
    public function getAllContracts()
    {
        return $this->contracts()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get contracts by status
     */
    public function getContractsByStatus($status)
    {
        return $this->contracts()
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get expired contracts that can be renewed
     * Returns contracts that are expired or cancelled and have no active contracts for this address
     */
    public function getRenewableContracts()
    {
        if ($this->getActiveContract()) {
            return collect(); // No contracts can be renewed if there's an active one
        }

        return $this->contracts()
            ->whereIn('status', ['expired', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if this address has any renewable contracts
     */
    public function hasRenewableContracts()
    {
        return $this->getRenewableContracts()->isNotEmpty();
    }

    /**
     * Get the most recent expired or cancelled contract for renewal
     */
    public function getMostRecentExpiredContract()
    {
        return $this->contracts()
            ->whereIn('status', ['expired', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
