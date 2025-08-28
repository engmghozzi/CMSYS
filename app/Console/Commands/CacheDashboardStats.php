<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Client;
use App\Models\User;
use App\Models\Machine;
use App\Models\Visit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CacheDashboardStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:cache {--force : Force refresh all caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache dashboard statistics for improved performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Caching dashboard statistics...');

        if ($this->option('force')) {
            $this->info('Force refreshing all caches...');
            Cache::flush();
        }

        $this->cacheContractStats();
        $this->cachePaymentStats();
        $this->cacheClientStats();
        $this->cacheUserStats();
        $this->cacheMachineStats();
        $this->cacheVisitStats();
        $this->cacheFinancialStats();

        $this->info('✅ Dashboard statistics cached successfully!');
        $this->info('Cache TTL: 1 hour (configurable)');
        
        return Command::SUCCESS;
    }

    private function cacheContractStats()
    {
        $this->info('Caching contract statistics...');
        
        $stats = [
            'total_contracts' => Contract::count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'expired_contracts' => Contract::where('status', 'expired')->count(),
            'cancelled_contracts' => Contract::where('status', 'cancelled')->count(),
            'total_amount' => Contract::sum('total_amount'),
            'by_type' => Contract::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_status' => Contract::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'expiring_soon' => Contract::where('status', 'active')
                ->where('end_date', '<=', now()->addMonths(3))
                ->where('end_date', '>', now())
                ->count(),
        ];

        Cache::put('dashboard.contract_stats', $stats, now()->addHour());
    }

    private function cachePaymentStats()
    {
        $this->info('Caching payment statistics...');
        
        $stats = [
            'total_revenue' => Contract::sum('total_amount'),
            'paid_amount' => Payment::where('status', 'Paid')->sum('amount'),
            'unpaid_amount' => Payment::where('status', '!=', 'Paid')->sum('amount'),
            'collection_rate' => $this->calculateCollectionRate(),
            'overdue_count' => Payment::where('due_date', '<', now())
                ->where('status', '!=', 'Paid')
                ->count(),
            'by_status' => Payment::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_method' => Payment::select('method', DB::raw('count(*) as count'))
                ->groupBy('method')
                ->pluck('count', 'method')
                ->toArray(),
        ];

        Cache::put('dashboard.payment_stats', $stats, now()->addHour());
    }

    private function cacheClientStats()
    {
        $this->info('Caching client statistics...');
        
        $stats = [
            'total_clients' => Client::count(),
            'by_type' => Client::select('client_type', DB::raw('count(*) as count'))
                ->groupBy('client_type')
                ->pluck('count', 'client_type')
                ->toArray(),
            'by_status' => Client::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'vip_clients' => Client::where('status', 'vip')->count(),
            'blocked_clients' => Client::where('status', 'blocked')->count(),
        ];

        Cache::put('dashboard.client_stats', $stats, now()->addHour());
    }

    private function cacheUserStats()
    {
        $this->info('Caching user statistics...');
        
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'by_role' => User::with('role')
                ->get()
                ->groupBy('role.name')
                ->map(function ($users) {
                    return $users->count();
                })
                ->toArray(),
        ];

        Cache::put('dashboard.user_stats', $stats, now()->addHour());
    }

    private function cacheMachineStats()
    {
        $this->info('Caching machine statistics...');
        
        $stats = [
            'total_machines' => Machine::count(),
            'by_type' => Machine::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_brand' => Machine::select('brand', DB::raw('count(*) as count'))
                ->groupBy('brand')
                ->pluck('count', 'brand')
                ->toArray(),
            'by_uom' => Machine::select('UOM', DB::raw('count(*) as count'))
                ->groupBy('UOM')
                ->pluck('count', 'UOM')
                ->toArray(),
        ];

        Cache::put('dashboard.machine_stats', $stats, now()->addHour());
    }

    private function cacheVisitStats()
    {
        $this->info('Caching visit statistics...');
        
        $stats = [
            'total_visits' => Visit::count(),
            'by_status' => Visit::select('visit_status', DB::raw('count(*) as count'))
                ->groupBy('visit_status')
                ->pluck('count', 'visit_status')
                ->toArray(),
            'by_type' => Visit::select('visit_type', DB::raw('count(*) as count'))
                ->groupBy('visit_type')
                ->pluck('count', 'visit_type')
                ->toArray(),
            'scheduled_visits' => Visit::where('visit_status', 'scheduled')->count(),
            'completed_visits' => Visit::where('visit_status', 'completed')->count(),
        ];

        Cache::put('dashboard.visit_stats', $stats, now()->addHour());
    }

    private function cacheFinancialStats()
    {
        $this->info('Caching financial statistics...');
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastYear = now()->subMonth()->year;

        $stats = [
            'monthly_revenue' => Contract::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('total_amount'),
            'yearly_revenue' => Contract::whereYear('created_at', $currentYear)
                ->sum('total_amount'),
            'last_month_revenue' => Contract::whereMonth('created_at', $lastMonth)
                ->whereYear('created_at', $lastYear)
                ->sum('total_amount'),
            'revenue_growth' => $this->calculateRevenueGrowth(),
            'monthly_trends' => $this->getMonthlyRevenueTrends(),
            'top_revenue_clients' => $this->getTopRevenueClients(),
        ];

        Cache::put('dashboard.financial_stats', $stats, now()->addHour());
    }

    private function calculateCollectionRate()
    {
        $totalRevenue = Contract::sum('total_amount');
        $paidAmount = Payment::where('status', 'Paid')->sum('amount');
        
        return $totalRevenue > 0 ? round(($paidAmount / $totalRevenue) * 100, 1) : 0;
    }

    private function calculateRevenueGrowth()
    {
        $currentMonth = Contract::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        
        $lastMonth = Contract::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');
        
        return $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;
    }

    private function getMonthlyRevenueTrends()
    {
        $trends = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $trends[] = [
                'month' => $month->format('M Y'),
                'revenue' => Contract::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_amount')
            ];
        }
        return $trends;
    }

    private function getTopRevenueClients()
    {
        return Contract::with('client')
            ->select('client_id', DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('client_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($contract) {
                return [
                    'client_name' => $contract->client->name ?? 'N/A',
                    'total_revenue' => $contract->total_revenue
                ];
            });
    }
}
