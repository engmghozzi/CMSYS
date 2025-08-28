<?php

namespace App\Observers;

use App\Models\Contract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContractObserver
{
    /**
     * Handle the Contract "created" event.
     */
    public function created(Contract $contract): void
    {
        // Log contract creation for audit trail
        Log::info('Contract created', [
            'contract_id' => $contract->id,
            'contract_num' => $contract->contract_num,
            'client_id' => $contract->client_id,
            'total_amount' => $contract->total_amount,
            'created_by' => $contract->created_by
        ]);
    }

    /**
     * Handle the Contract "updated" event.
     */
    public function updated(Contract $contract): void
    {
        // Log contract updates for audit trail
        Log::info('Contract updated', [
            'contract_id' => $contract->id,
            'contract_num' => $contract->contract_num,
            'client_id' => $contract->client_id,
            'updated_by' => $contract->updated_by,
            'changes' => $contract->getChanges()
        ]);
    }

    /**
     * Handle the Contract "deleted" event.
     */
    public function deleted(Contract $contract): void
    {
        // Clean up attachment file when contract is deleted
        $this->deleteAttachmentFile($contract);
        
        // Log contract deletion for audit trail
        Log::info('Contract deleted', [
            'contract_id' => $contract->id,
            'contract_num' => $contract->contract_num,
            'client_id' => $contract->client_id
        ]);
    }

    /**
     * Handle the Contract "restored" event.
     */
    public function restored(Contract $contract): void
    {
        // Log contract restoration for audit trail
        Log::info('Contract restored', [
            'contract_id' => $contract->id,
            'contract_num' => $contract->contract_num,
            'client_id' => $contract->client_id
        ]);
    }

    /**
     * Handle the Contract "force deleted" event.
     */
    public function forceDeleted(Contract $contract): void
    {
        // Clean up attachment file when contract is force deleted
        $this->deleteAttachmentFile($contract);
        
        // Log contract force deletion for audit trail
        Log::info('Contract force deleted', [
            'contract_id' => $contract->id,
            'contract_num' => $contract->contract_num,
            'client_id' => $contract->client_id
        ]);
    }

    /**
     * Delete attachment file from S3 storage
     */
    private function deleteAttachmentFile(Contract $contract): void
    {
        if ($contract->attachment_url) {
            try {
                // Get the original path (not the accessor value)
                $originalPath = $contract->getRawOriginal('attachment_url');
                
                Log::info('Attempting to delete contract attachment from S3', [
                    'contract_id' => $contract->id,
                    'original_path' => $originalPath,
                    'attachment_url' => $contract->attachment_url
                ]);
                
                // Check if file exists before deletion
                $exists = Storage::disk('s3_contracts')->exists($originalPath);
                Log::info('File existence check in observer', [
                    'file_path' => $originalPath,
                    'exists' => $exists
                ]);
                
                if ($exists) {
                    $deleted = Storage::disk('s3_contracts')->delete($originalPath);
                    Log::info('S3 deletion result in observer', [
                        'file_path' => $originalPath,
                        'deleted' => $deleted
                    ]);
                    
                    if ($deleted) {
                        Log::info('Contract attachment successfully deleted from S3', [
                            'contract_id' => $contract->id,
                            'file_path' => $originalPath
                        ]);
                    } else {
                        Log::error('Failed to delete contract attachment from S3 - delete() returned false', [
                            'contract_id' => $contract->id,
                            'file_path' => $originalPath
                        ]);
                    }
                } else {
                    Log::warning('Contract attachment file does not exist in S3', [
                        'contract_id' => $contract->id,
                        'file_path' => $originalPath
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete contract attachment from S3', [
                    'contract_id' => $contract->id,
                    'file_path' => $contract->attachment_url,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }
}
