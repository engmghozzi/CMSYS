<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Machine;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Contract Statistics
        $totalContracts = Contract::count();
        $activeContracts = Contract::where('status', 'active')->count();
        $completedContracts = Contract::where('status', 'completed')->count();
        $pendingContracts = Contract::where('status', 'pending')->count();

        // Payment Statistics
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $totalPayments = Payment::sum('amount');
        $collectionRate = $totalPayments > 0 ? round(($totalRevenue / $totalPayments) * 100, 1) : 0;
        $outstandingAmount = Payment::where('status', '!=', 'paid')->sum('amount');
        $overduePayments = Payment::where('due_date', '<', now())->where('status', '!=', 'paid')->count();

        // Client Statistics
        $totalClients = Client::count();
        $newClientsThisMonth = Client::whereMonth('created_at', now()->month)->count();

        // Machine Statistics
        $totalMachines = Machine::count();
        $activeMachines = Machine::where('status', 'active')->count();
        $machineTypesCount = Machine::distinct('machine_type')->count();
        $machineBrandsCount = Machine::distinct('machine_brand')->count();
        
        // Most common machine type
        $mostCommonMachineType = Machine::select('machine_type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('machine_type')
            ->orderBy('count', 'desc')
            ->first();
        $mostCommonMachineType = $mostCommonMachineType ? $mostCommonMachineType->machine_type : 'N/A';
        
        // Top machine brand
        $topMachineBrand = Machine::select('machine_brand')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('machine_brand')
            ->orderBy('count', 'desc')
            ->first();
        $topMachineBrand = $topMachineBrand ? $topMachineBrand->machine_brand : 'N/A';
        
        // Average efficiency
        $averageEfficiency = Machine::whereNotNull('efficiency')->avg('efficiency');
        $averageEfficiency = round($averageEfficiency ?? 0, 1);
        
        // High efficiency machines (efficiency > 80%)
        $highEfficiencyMachines = Machine::where('efficiency', '>', 80)->count();

        // Recent Activity
        $recentContracts = Contract::with('client')->latest()->take(5)->get();
        $recentPayments = Payment::with('contract.client')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalContracts',
            'activeContracts',
            'completedContracts',
            'pendingContracts',
            'totalRevenue',
            'collectionRate',
            'outstandingAmount',
            'overduePayments',
            'totalClients',
            'newClientsThisMonth',
            'totalMachines',
            'activeMachines',
            'machineTypesCount',
            'machineBrandsCount',
            'mostCommonMachineType',
            'topMachineBrand',
            'averageEfficiency',
            'highEfficiencyMachines',
            'recentContracts',
            'recentPayments'
        ));
    }
} 