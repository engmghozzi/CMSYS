<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Ensure date is a Carbon instance
     */
    private function ensureCarbonDate($date)
    {
        if (is_null($date)) {
            return now();
        }
        if (is_string($date)) {
            return Carbon::parse($date);
        }
        return $date;
    }

    public function index()
    {
        // Contract Statistics
        $totalContracts = Contract::count();
        $activeContracts = Contract::where('status', 'active')->count();
        $expiredContracts = Contract::where('status', 'expired')->count();
        $cancelledContracts = Contract::where('status', 'cancelled')->count();
        $totalContractsAmount = Contract::sum('total_amount');
        
        // Contract Types Statistics - Always show all types even if count is 0
        $contractTypeL = Contract::where('type', 'L')->count();
        $contractTypeLS = Contract::where('type', 'LS')->count();
        $contractTypeC = Contract::where('type', 'C')->count();
        $contractTypeOther = Contract::where('type', 'Other')->count();
        
        // Contract Status Statistics - Always show all statuses even if count is 0
        $contractStatusActive = Contract::where('status', 'active')->count();
        $contractStatusExpired = Contract::where('status', 'expired')->count();
        $contractStatusCancelled = Contract::where('status', 'cancelled')->count();
        
        // Client Types Statistics - Always show all types even if count is 0
        $clientTypes = [
            'Client' => Client::where('client_type', 'Client')->count(),
            'Company' => Client::where('client_type', 'Company')->count(),
            'Contractor' => Client::where('client_type', 'Contractor')->count(),
            'Other' => Client::where('client_type', 'Other')->count(),
        ];
        
        // Client Status Statistics - Always show all statuses even if count is 0
        $clientStatus = [
            'vip' => Client::where('status', 'vip')->count(),
            'ordinary' => Client::where('status', 'ordinary')->count(),
            'blocked' => Client::where('status', 'blocked')->count(),
        ];

        // Enhanced Payment Statistics
        $totalRevenue = Contract::sum('total_amount'); // Total Revenue = sum of all contracts amount
        $paid = Payment::where('status', 'Paid')->sum('amount'); // Paid = all paid transactions
        $unpaid = Payment::where('status', 'Unpaid')->sum('amount'); // Unpaid transactions only
        $collectionRate = $totalRevenue > 0 ? round(($paid / $totalRevenue) * 100, 1) : 0;
        $overduePayments = Payment::where('due_date', '<', now())->where('status', '!=', 'Paid')->count();

        // Financial KPIs
        $averageContractValue = $totalContracts > 0 ? round($totalRevenue / $totalContracts, 3) : 0;
        $monthlyRevenue = Contract::whereMonth('created_at', now()->month)->sum('total_amount');
        $yearlyRevenue = Contract::whereYear('created_at', now()->year)->sum('total_amount');
        $lastMonthRevenue = Contract::whereMonth('created_at', now()->subMonth()->month)->sum('total_amount');
        $revenueGrowth = $lastMonthRevenue > 0 ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        // Monthly Revenue Trends (Last 12 months)
        $monthlyRevenueTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenueTrends[] = [
                'month' => $month->format('M Y'),
                'revenue' => Contract::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_amount')
            ];
        }

        // Top Revenue Clients
        $topRevenueClients = Contract::with('client')
            ->select('client_id', DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('client_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // Revenue by Contract Type
        $revenueByContractType = Contract::select('type', DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('type')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Payment Performance Metrics
        $paymentPerformance = [
            'on_time' => Payment::where('status', 'Paid')->where('paid_date', '<=', DB::raw('due_date'))->count(),
            'late' => Payment::where('status', 'Paid')->where('paid_date', '>', DB::raw('due_date'))->count(),
        ];

        // Cash Flow Analysis
        $currentMonthPayments = Payment::whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->where('status', 'Paid')
            ->sum('amount');
        
        $expectedNextMonth = Payment::whereMonth('due_date', now()->addMonth()->month)
            ->whereYear('due_date', now()->addMonth()->year)
            ->where('status', '!=', 'Paid')
            ->sum('amount');

        // Outstanding Payments by Age
        $outstandingPaymentsByAge = [
            'current' => Payment::where('status', '!=', 'Paid')
                ->where('due_date', '>=', now())
                ->sum('amount'),
            '30_days' => Payment::where('status', '!=', 'Paid')
                ->where('due_date', '<', now())
                ->where('due_date', '>=', now()->subDays(30))
                ->sum('amount'),
            '60_days' => Payment::where('status', '!=', 'Paid')
                ->where('due_date', '<', now()->subDays(30))
                ->where('due_date', '>=', now()->subDays(60))
                ->sum('amount'),
            '90_days' => Payment::where('status', '!=', 'Paid')
                ->where('due_date', '<', now()->subDays(60))
                ->where('due_date', '>=', now()->subDays(90))
                ->sum('amount'),
            'over_90_days' => Payment::where('status', '!=', 'Paid')
                ->where('due_date', '<', now()->subDays(90))
                ->sum('amount'),
        ];

        // Recent Financial Activities
        $recentFinancialActivities = collect();
        
        // Recent payments
        $recentPayments = Payment::with('contract.client')
            ->where('status', 'Paid')
            ->whereNotNull('paid_date')
            ->latest('paid_date')
            ->take(5)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'payment',
                    'title' => __('Payment received'),
                    'description' => __('Payment of :amount KWD from :client', [
                        'amount' => number_format($payment->amount, 3),
                        'client' => $payment->contract->client->name ?? 'N/A'
                    ]),
                    'amount' => $payment->amount,
                    'date' => $this->ensureCarbonDate($payment->paid_date),
                    'status' => 'positive'
                ];
            });
        
        // Recent contracts
        $recentContracts = Contract::with('client')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($contract) {
                return [
                    'type' => 'contract',
                    'title' => __('New contract created'),
                    'description' => __('Contract :type for :amount KWD with :client', [
                        'type' => $contract->type,
                        'amount' => number_format($contract->total_amount, 3),
                        'client' => $contract->client->name ?? 'N/A'
                    ]),
                    'amount' => $contract->total_amount,
                    'date' => $contract->created_at,
                    'status' => 'positive'
                ];
            });
        
        $recentFinancialActivities = $recentPayments->toBase()->merge($recentContracts->toBase())
            ->sortByDesc('date')
            ->take(10);

        // Financial Alerts
        $financialAlerts = collect();
        
        // Overdue payments
        $overduePayments = Payment::with('contract.client')
            ->where('status', '!=', 'Paid')
            ->where('due_date', '<', now())
            ->get();
        
        foreach($overduePayments as $payment) {
            $daysOverdue = now()->diffInDays($this->ensureCarbonDate($payment->due_date));
            $financialAlerts->push([
                'type' => 'overdue',
                'title' => __('Overdue Payment'),
                'description' => __('Payment of :amount KWD from :client is :days days overdue', [
                    'amount' => number_format($payment->amount, 3),
                    'client' => $payment->contract->client->name ?? 'N/A',
                    'days' => $daysOverdue
                ]),
                'severity' => $daysOverdue > 90 ? 'critical' : ($daysOverdue > 60 ? 'high' : 'medium'),
                'date' => $this->ensureCarbonDate($payment->due_date)
            ]);
        }
        
        // Low collection rate alert
        if ($collectionRate < 70) {
            $financialAlerts->push([
                'type' => 'collection_rate',
                'title' => __('Low Collection Rate'),
                'description' => __('Collection rate is :rate% which is below the target of 70%', [
                    'rate' => $collectionRate
                ]),
                'severity' => 'high',
                'date' => now()
            ]);
        }
        
        // High outstanding payments alert
        $totalOutstanding = array_sum($outstandingPaymentsByAge);
        if ($totalOutstanding > ($totalRevenue * 0.3)) {
            $financialAlerts->push([
                'type' => 'outstanding',
                'title' => __('High Outstanding Payments'),
                'description' => __('Outstanding payments of :amount KWD represent :percentage% of total revenue', [
                    'amount' => number_format($totalOutstanding, 3),
                    'percentage' => round(($totalOutstanding / $totalRevenue) * 100, 1)
                ]),
                'severity' => 'high',
                'date' => now()
            ]);
        }

        // Financial Health Score
        $financialHealthScore = 100;
        
        // Deduct points for low collection rate
        if ($collectionRate < 90) $financialHealthScore -= (90 - $collectionRate) * 2;
        if ($collectionRate < 70) $financialHealthScore -= 20;
        
        // Deduct points for overdue payments
        $overduePercentage = $totalRevenue > 0 ? ($outstandingPaymentsByAge['over_90_days'] / $totalRevenue) * 100 : 0;
        if ($overduePercentage > 10) $financialHealthScore -= $overduePercentage * 2;
        
        // Deduct points for negative growth
        if ($revenueGrowth < 0) $financialHealthScore -= abs($revenueGrowth);
        
        $financialHealthScore = max(0, min(100, $financialHealthScore));
        
        $financialHealthStatus = $financialHealthScore >= 80 ? 'excellent' : 
                               ($financialHealthScore >= 60 ? 'good' : 
                               ($financialHealthScore >= 40 ? 'fair' : 'poor'));

        // Client Statistics
        $totalClients = Client::count();
        $newClientsThisMonth = Client::whereMonth('created_at', now()->month)->count();



        // Payment Transaction Statistics
        $totalPaymentTransactions = Payment::count();
        $paidTransactions = Payment::where('status', 'Paid')->count();
        $unpaidTransactions = Payment::where('status', 'Unpaid')->count();
        $overdueTransactions = Payment::where('due_date', '<', now())->where('status', '!=', 'Paid')->count();
        
        // Payment Amount Statistics
        $totalPaymentAmount = Payment::sum('amount');
        $averagePaymentAmount = $totalPaymentTransactions > 0 ? round($totalPaymentAmount / $totalPaymentTransactions, 3) : 0;
        $largestPayment = Payment::max('amount') ?? 0;
        $smallestPayment = Payment::min('amount') ?? 0;
        
        // Payment Timing Statistics
        $onTimePayments = Payment::where('status', 'Paid')
            ->where('paid_date', '<=', DB::raw('due_date'))
            ->count();
        $latePayments = Payment::where('status', 'Paid')
            ->where('paid_date', '>', DB::raw('due_date'))
            ->count();
        
        // Payment Method Statistics
        $paymentsByMethod = Payment::select('method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('method')
            ->orderBy('count', 'desc')
            ->get();
            

        
        // Recent Activity
        $recentContracts = Contract::with('client')->latest()->take(5)->get();
        $recentPayments = Payment::with('contract.client')->latest()->take(5)->get();
        


        return view('dashboard', compact(
            'totalContracts',
            'activeContracts',
            'expiredContracts',
            'cancelledContracts',
            'totalContractsAmount',
            'contractTypeL',
            'contractTypeLS',
            'contractTypeC',
            'contractTypeOther',
            'contractStatusActive',
            'contractStatusExpired',
            'contractStatusCancelled',
            'clientTypes',
            'clientStatus',
            'totalRevenue',
            'paid',
            'unpaid',
            'collectionRate',
            'overduePayments',
            'totalClients',
            'newClientsThisMonth',
            'recentContracts',
            'recentPayments',
            // New Financial Data
            'averageContractValue',
            'monthlyRevenue',
            'yearlyRevenue',
            'revenueGrowth',
            'monthlyRevenueTrends',
            'topRevenueClients',
            'revenueByContractType',
            'paymentPerformance',
            'currentMonthPayments',
            'expectedNextMonth',
            'outstandingPaymentsByAge',
            'recentFinancialActivities',
            'financialAlerts',
            'financialHealthScore',
            'financialHealthStatus',
            // Payment Transaction Statistics
            'totalPaymentTransactions',
            'paidTransactions',
            'unpaidTransactions',
            'overdueTransactions',
            'totalPaymentAmount',
            'averagePaymentAmount',
            'largestPayment',
            'smallestPayment',
            'onTimePayments',
            'latePayments',
            'paymentsByMethod'
        ));
    }

    private function cacheStats($stats)
    {
        // Cache individual components for better granularity
        Cache::put('dashboard.contract_stats', [
            'total_contracts' => $stats['totalContracts'],
            'active_contracts' => $stats['activeContracts'],
            'expired_contracts' => $stats['expiredContracts'],
            'cancelled_contracts' => $stats['cancelledContracts'],
            'total_amount' => $stats['totalContractsAmount'],
        ], now()->addHour());

        Cache::put('dashboard.payment_stats', [
            'total_revenue' => $stats['totalRevenue'],
            'paid_amount' => $stats['paid'],
            'unpaid_amount' => $stats['unpaid'],
            'collection_rate' => $stats['collectionRate'],
            'overdue_count' => $stats['overduePayments'],
        ], now()->addHour());

        Cache::put('dashboard.financial_stats', [
            'monthly_revenue' => $stats['monthlyRevenue'],
            'yearly_revenue' => $stats['yearlyRevenue'],
            'revenue_growth' => $stats['revenueGrowth'],
            'monthly_trends' => $stats['monthlyRevenueTrends'],
            'top_revenue_clients' => $stats['topRevenueClients'],
        ], now()->addHour());
    }

    private function formatStatsForView($contractStats, $paymentStats, $clientStats, $userStats, $financialStats)
    {
        // Format cached stats to match the view expectations
        return [
            'totalContracts' => $contractStats['total_contracts'],
            'activeContracts' => $contractStats['active_contracts'],
            'expiredContracts' => $contractStats['expired_contracts'],
            'cancelledContracts' => $contractStats['cancelled_contracts'],
            'totalContractsAmount' => $contractStats['total_amount'],
            'totalRevenue' => $paymentStats['total_revenue'],
            'paid' => $paymentStats['paid_amount'],
            'unpaid' => $paymentStats['unpaid_amount'],
            'collectionRate' => $paymentStats['collection_rate'],
            'overduePayments' => $paymentStats['overdue_count'],
            'monthlyRevenue' => $financialStats['monthly_revenue'],
            'yearlyRevenue' => $financialStats['yearly_revenue'],
            'revenueGrowth' => $financialStats['revenue_growth'],
            'monthlyRevenueTrends' => $financialStats['monthly_trends'],
            'topRevenueClients' => $financialStats['top_revenue_clients'],
            // Add other required variables with default values
            'totalClients' => $clientStats['total_clients'] ?? 0,
        ];
    }
}