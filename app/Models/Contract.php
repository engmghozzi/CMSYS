<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Contract extends Model
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory, Loggable;
    protected $guarded = ['id'];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'commission_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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



    /**
     * Determine if the contract is expired (end_date in the past).
     */
    public function getIsExpiredAttribute()
    {
        return $this->end_date < now();
    }

    /**
     * Get the dynamic status: returns 'expired' if expired, otherwise the stored status.
     */
    public function getDynamicStatusAttribute()
    {
        return $this->is_expired ? 'expired' : $this->status;
    }

    /**
     * Validate and correct contract status based on expiration date.
     * This method ensures the status matches the actual expiration state.
     */
    public function validateStatus()
    {
        $calculatedStatus = $this->calculateStatusFromDates();
        
        // If the stored status doesn't match the calculated status, update it
        if ($this->status !== $calculatedStatus) {
            $this->status = $calculatedStatus;
            $this->save();
            
            \Illuminate\Support\Facades\Log::info('Contract status auto-corrected', [
                'contract_id' => $this->id,
                'contract_number' => $this->contract_number,
                'old_status' => $this->getOriginal('status'),
                'new_status' => $calculatedStatus,
                'end_date' => $this->end_date,
                'is_expired' => $this->is_expired
            ]);
        }
        
        return $calculatedStatus;
    }

    /**
     * Calculate the correct status based on start_date, end_date, and duration.
     */
    public function calculateStatusFromDates()
    {
        $now = now();
        
        // If contract is cancelled, keep it cancelled regardless of dates
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }
        
        // If end_date is in the past, contract should be expired
        if ($this->end_date < $now) {
            return 'expired';
        }
        
        // If start_date is in the future, contract should be active (not yet started)
        if ($this->start_date > $now) {
            return 'active';
        }
        
        // If we're between start and end dates, contract should be active
        if ($this->start_date <= $now && $this->end_date >= $now) {
            return 'active';
        }
        
        // Default fallback
        return 'active';
    }

    /**
     * Boot method to automatically validate status on model events
     */
    protected static function boot()
    {
        parent::boot();
        
        // Validate status before saving
        static::saving(function ($contract) {
            $contract->validateStatus();
        });
        
        // Validate status after creating
        static::created(function ($contract) {
            $contract->validateStatus();
        });
        
        // Validate status after updating
        static::updated(function ($contract) {
            $contract->validateStatus();
        });
    }

    /**
     * Get the S3 URL for the contract attachment
     */
    public function getAttachmentUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Generate pre-signed URL with long expiration (1 year)
        try {
            // Use AWS SDK directly to create pre-signed URL
            $s3Client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region'  => config('filesystems.disks.s3_contracts.region'),
                'credentials' => [
                    'key'    => config('filesystems.disks.s3_contracts.key'),
                    'secret' => config('filesystems.disks.s3_contracts.secret'),
                ],
            ]);
            
            $command = $s3Client->getCommand('GetObject', [
                'Bucket' => config('filesystems.disks.s3_contracts.bucket'),
                'Key'    => $value
            ]);
            
            // Generate URL with 1 year expiration
            $request = $s3Client->createPresignedRequest($command, '+1 year');
            
            return (string) $request->getUri();
        } catch (\Exception $e) {
            Log::error('Failed to generate pre-signed URL', [
                'file_path' => $value,
                'error' => $e->getMessage()
            ]);
            
            // Fallback to direct URL if pre-signed fails
            $bucket = config('filesystems.disks.s3_contracts.bucket');
            $region = config('filesystems.disks.s3_contracts.region');
            $url = config('filesystems.disks.s3_contracts.url');
            
            if ($url) {
                return rtrim($url, '/') . '/' . $value;
            } else {
                return "https://{$bucket}.s3.{$region}.amazonaws.com/{$value}";
            }
        }
    }

    /**
     * Get the file name from the attachment URL
     */
    public function getAttachmentFileNameAttribute()
    {
        if (!$this->attachment_url) {
            return null;
        }

        // Extract filename from the full path
        $path = $this->attachment_url;
        
        // If it's a pre-signed URL, extract the key from the URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($path);
            if (isset($parsedUrl['path'])) {
                $path = ltrim($parsedUrl['path'], '/');
            }
        }
        
        return basename($path);
    }

    /**
     * Get the original attachment path (not the pre-signed URL)
     */
    public function getOriginalAttachmentPathAttribute()
    {
        return $this->getRawOriginal('attachment_url');
    }

    /**
     * Get the original file path for S3 operations
     */
    public function getS3FilePathAttribute()
    {
        $originalPath = $this->getRawOriginal('attachment_url');
        
        if (!$originalPath) {
            return null;
        }

        // Log the original path for debugging
        \Illuminate\Support\Facades\Log::info('S3 File Path Debug', [
            'contract_id' => $this->id,
            'original_path' => $originalPath,
            'is_url' => filter_var($originalPath, FILTER_VALIDATE_URL)
        ]);

        // If it's already a full URL, extract the key
        if (filter_var($originalPath, FILTER_VALIDATE_URL)) {
            $extractedPath = $this->extractS3KeyFromUrl($originalPath);
            if ($extractedPath) {
                \Illuminate\Support\Facades\Log::info('Extracted S3 path from URL', [
                    'contract_id' => $this->id,
                    'extracted_path' => $extractedPath
                ]);
                return $extractedPath;
            }
        }

        return $originalPath;
    }


    /**
     * Check if this contract can be renewed
     * A contract can be renewed if it's expired or cancelled and there are no active contracts for the same address
     */
    public function canBeRenewed()
    {
        // Only expired or cancelled contracts can be renewed
        if (!in_array($this->status, ['expired', 'cancelled'])) {
            return false;
        }

        // Check if there's already an active contract for this address
        $activeContract = Contract::where('address_id', $this->address_id)
            ->where('status', 'active')
            ->where('id', '!=', $this->id)
            ->first();

        return !$activeContract;
    }

    /**
     * Accessor to check if this contract can be renewed
     * This provides a cleaner way to check renewal eligibility in views
     */
    public function getCanRenewAttribute()
    {
        return $this->canBeRenewed();
    }

    /**
     * Get the renewal status message
     */
    public function getRenewalStatusMessage()
    {
        if ($this->status === 'active') {
            return 'Contract is active and cannot be renewed';
        }
        
        if (in_array($this->status, ['expired', 'cancelled'])) {
            if ($this->canBeRenewed()) {
                return 'Contract can be renewed';
            } else {
                return 'Contract cannot be renewed - address has active contract';
            }
        }
        
        return 'Contract status unknown';
    }

}
