<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateContractEndDates extends Command
{
    protected $signature = 'contracts:update-end-dates';
    protected $description = 'Update all existing contract end dates to subtract one day';

    public function handle()
    {
        $this->info('Updating Contract End Dates...');
        $this->info('============================');

        // Get all contracts
        $contracts = DB::table('contracts')->get();

        if ($contracts->isEmpty()) {
            $this->info('No contracts found in the database.');
            return;
        }

        $this->info("Found " . $contracts->count() . " contracts to update.");

        $updatedCount = 0;
        $bar = $this->output->createProgressBar($contracts->count());

        foreach ($contracts as $contract) {
            // Calculate the correct end date based on start date and duration
            $startDate = Carbon::parse($contract->start_date);
            $durationMonths = $contract->duration_months;
            
            // New logic: add months then subtract 1 day
            $correctEndDate = (clone $startDate)->addMonths($durationMonths)->subDay();
            
            // Update the contract with the corrected end date
            DB::table('contracts')
                ->where('id', $contract->id)
                ->update([
                    'end_date' => $correctEndDate->toDateString(),
                    'updated_at' => now()
                ]);
            
            $updatedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Successfully updated {$updatedCount} contracts!");
        $this->info("The index page should now show the corrected end dates.");
    }
}
