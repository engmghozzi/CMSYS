<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all contracts
        $contracts = DB::table('contracts')->get();
        
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
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all contracts
        $contracts = DB::table('contracts')->get();
        
        foreach ($contracts as $contract) {
            // Revert to old logic: add months without subtracting day
            $startDate = Carbon::parse($contract->start_date);
            $durationMonths = $contract->duration_months;
            
            // Old logic: just add months
            $oldEndDate = (clone $startDate)->addMonths($durationMonths);
            
            // Update the contract back to old end date
            DB::table('contracts')
                ->where('id', $contract->id)
                ->update([
                    'end_date' => $oldEndDate->toDateString(),
                    'updated_at' => now()
                ]);
        }
    }
};
