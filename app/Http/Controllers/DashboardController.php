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
        $totalContractsAmount = Contract::sum('total_amount');
        
        // Contract Types Statistics - Always show all types even if count is 0
        $contractTypeL = Contract::where('type', 'L')->count();
        $contractTypeLS = Contract::where('type', 'LS')->count();
        $contractTypeC = Contract::where('type', 'C')->count();
        $contractTypeOther = Contract::where('type', 'Other')->count();
        
        // Contract Status Statistics - Always show all statuses even if count is 0
        $contractStatusDraft = Contract::where('status', 'draft')->count();
        $contractStatusSigned = Contract::where('status', 'signed')->count();
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

        // Payment Statistics
        $totalRevenue = Contract::sum('total_amount'); // Total Revenue = sum of all contracts amount
        $paid = Payment::where('status', 'Paid')->sum('amount'); // Paid = all paid transactions
        $unpaid = Payment::where('status', 'Unpaid')->sum('amount'); // Unpaid transactions only
        $pending = Payment::where('status', 'Pending')->sum('amount'); // Pending transactions only
        $overdue = Payment::where('status', 'Overdue')->sum('amount'); // Overdue transactions only
        $collectionRate = $totalRevenue > 0 ? round(($paid / $totalRevenue) * 100, 1) : 0;
        $overduePayments = Payment::where('due_date', '<', now())->where('status', '!=', 'Paid')->count();

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
        
        // Machines by Brand Statistics
        $machinesByBrand = Machine::select('brand', DB::raw('count(*) as count'))
            ->groupBy('brand')
            ->pluck('count', 'brand')
            ->toArray();
            
        // Machines by Type Statistics
        $machinesByType = Machine::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return view('dashboard', compact(
            'totalContracts',
            'activeContracts',
            'completedContracts',
            'pendingContracts',
            'totalContractsAmount',
            'contractTypeL',
            'contractTypeLS',
            'contractTypeC',
            'contractTypeOther',
            'contractStatusDraft',
            'contractStatusSigned',
            'contractStatusActive',
            'contractStatusExpired',
            'contractStatusCancelled',
            'clientTypes',
            'clientStatus',
            'totalRevenue',
            'paid',
            'unpaid',
            'pending',
            'overdue',
            'collectionRate',
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
            'recentPayments',
            'machinesByBrand',
            'machinesByType'
        ));
    }
} 