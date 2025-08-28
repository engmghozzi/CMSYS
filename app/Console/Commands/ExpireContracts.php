<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpireContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:expire 
                            {--dry-run : Show what would be expired without actually updating}
                            {--force : Skip confirmation in interactive mode}
                            {--date= : Use specific date instead of current date (format: Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire contracts when their duration ends';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Contract Expiration Check Started...');
        $this->newLine();

        // Get the reference date for checking expiration
        $referenceDate = $this->option('date') 
            ? Carbon::parse($this->option('date')) 
            : Carbon::now();

        $this->info("Checking contracts against date: {$referenceDate->format('Y-m-d')}");
        $this->newLine();

        // Find contracts that should be expired
        $expiredContracts = Contract::where('status', 'active')
            ->where('end_date', '<=', $referenceDate)
            ->get();

        if ($expiredContracts->isEmpty()) {
            $this->info('✅ No contracts need to be expired.');
            return 0;
        }

        $this->info("📋 Found {$expiredContracts->count()} contract(s) that should be expired:");
        $this->newLine();

        // Display contracts that will be expired
        $this->table(
            ['ID', 'Contract #', 'Client', 'Address', 'End Date', 'Days Overdue'],
            $expiredContracts->map(function ($contract) use ($referenceDate) {
                $daysOverdue = $referenceDate->diffInDays($contract->end_date, false);
                return [
                    $contract->id,
                    $contract->contract_num,
                    $contract->client->name ?? 'N/A',
                    $contract->address->address ?? 'N/A',
                    $contract->end_date->format('Y-m-d'),
                    $daysOverdue > 0 ? "{$daysOverdue} days" : "Expired " . abs($daysOverdue) . " days ago"
                ];
            })
        );

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->warn('🔍 DRY RUN MODE - No contracts were actually updated.');
            $this->info('Run without --dry-run to actually expire the contracts.');
            return 0;
        }

        // Confirm before proceeding only when interactive and not forced
        $shouldPrompt = $this->input->isInteractive() && !$this->option('force');
        if ($shouldPrompt) {
            if (!$this->confirm('Do you want to proceed with expiring these contracts?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->newLine();
        $this->info('⏳ Processing contract expirations...');

        $successCount = 0;
        $errorCount = 0;
        $processedContracts = [];

        DB::beginTransaction();

        try {
            foreach ($expiredContracts as $contract) {
                try {
                    // Store old status for logging
                    $oldStatus = $contract->status;
                    
                    // Update contract status to expired
                    $contract->update([
                        'status' => 'expired',
                        'updated_by' => 1 // System user ID, adjust as needed
                    ]);

                    // Log the action
                    LogService::logAction('status_change', 
                        "Contract automatically expired due to end date ({$contract->end_date->format('Y-m-d')})", 
                        $contract,
                        [
                            'old_values' => ['status' => $oldStatus],
                            'new_values' => ['status' => 'expired']
                        ]
                    );

                    $successCount++;
                    $processedContracts[] = [
                        'id' => $contract->id,
                        'contract_num' => $contract->contract_num,
                        'status' => 'expired'
                    ];

                    $this->info("✓ Contract {$contract->contract_num} expired successfully");

                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("✗ Failed to expire contract {$contract->contract_num}: {$e->getMessage()}");
                }
            }

            DB::commit();

            $this->newLine();
            $this->info('🎉 Contract Expiration Process Completed!');
            $this->info("✅ Successfully expired: {$successCount} contract(s)");
            
            if ($errorCount > 0) {
                $this->warn("⚠️  Failed to expire: {$errorCount} contract(s)");
            }

            // Show summary of processed contracts
            if (!empty($processedContracts)) {
                $this->newLine();
                $this->info('📊 Summary of Expired Contracts:');
                $this->table(
                    ['ID', 'Contract #', 'New Status'],
                    $processedContracts
                );
            }

            // Additional recommendations
            $this->newLine();
            $this->info('💡 Recommendations:');
            $this->line('  • Review expired contracts for renewal opportunities');
            $this->line('  • Check if any payments are still pending');
            $this->line('  • Consider sending expiration notifications to clients');
            $this->line('  • Schedule this command to run daily: php artisan schedule:work');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Fatal error during contract expiration: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
} 