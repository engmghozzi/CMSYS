<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use Carbon\Carbon;

class ExpireContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set contracts to expired if their end_date has passed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::today();
        // Update all contracts whose end_date has passed and status is not already expired
        $count = Contract::where('status', '!=', 'expired')
            ->whereDate('end_date', '<', $now)
            ->update(['status' => 'expired']);

        $this->info("Expired $count contracts.");
        return 0;
    }
} 