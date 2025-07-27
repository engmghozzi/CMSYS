<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Address;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractController extends Controller
{
    //index
    public function globalindex(Request $request)
    {
        $query = \App\Models\Contract::with('client');

        // Search by contract number or general search
        if ($request->filled('search')) {
            $query->where('contract_num', 'like', "%{$request->search}%");
        }

        // Filter by mobile number (main or alternative)
        if ($request->filled('mobile')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('mobile_number', 'like', "%{$request->mobile}%")
                  ->orWhere('alternate_mobile_number', 'like', "%{$request->mobile}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by contract type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by start date
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        // Filter by end date
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Filter by expiring within months
        if ($request->filled('expiring_months')) {
            $months = (int) $request->expiring_months;
            $today = now()->startOfDay();
            $futureDate = now()->addMonths($months)->endOfDay();
            
            $query->where('end_date', '>=', $today)
                  ->where('end_date', '<=', $futureDate);
        }

        $contracts = $query->latest()->paginate(10)->withQueryString();

        return view('pages.contracts.globalindex', compact('contracts'));
    }

    //create
    public function create(Request $request,Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add contracts to a blocked client.');
        }

        return view('pages.contracts.create', compact('client'));
    }
    
    //show
    public function show(Client $client, Contract $contract)
    {
        return view('pages.contracts.show', compact('client', 'contract'));
    }
    
    //store
    public function store(Request $request, Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add contracts to a blocked client.');
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id|unique:contracts,address_id',
            'type' => 'required|in:L,LS,C,Other',
            'centeral_machines' => 'required|numeric|min:0',
            'unit_machines' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'duration_months' => 'required|numeric|min:1',
            'total_amount' => 'required|numeric|min:0',
            'commission_amount' => 'nullable|numeric|min:0',
            'commission_type' => 'nullable|in:Incentive Bonus,Referral Commission,Other',
            'commission_recipient' => 'nullable|string|max:255',
            'commission_date' => 'nullable|date',
            'status' => 'required|in:draft,signed,active,expired,cancelled',
            'details' => 'required|string',
            'attachment_url' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Generate start & end date
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = (clone $startDate)->addMonths((int) $validated['duration_months']);

        // Generate contract number: CONT/YY/XYZ
        $year = $startDate->format('y');
        $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $contractNum = "CONT/{$year}/{$randomNumber}";

        // Handle file upload (optional)
        $filePath = null;
        if ($request->hasFile('attachment_url')) {
            $filePath = $request->file('attachment_url')->store('contracts', 'public');
        }

        // Save the contract
        Contract::create([
            'contract_num' => $contractNum,
            'client_id' => $client->id,
            'address_id' => $validated['address_id'],
            'type' => $validated['type'],
            'centeral_machines' => $validated['centeral_machines'],
            'unit_machines' => $validated['unit_machines'],
            'start_date' => $startDate->toDateString(),
            'duration_months' => $validated['duration_months'],
            'end_date' => $endDate->toDateString(),
            'total_amount' => $validated['total_amount'],
            'commission_amount' => $validated['commission_amount'],
            'commission_type' => $validated['commission_type'],
            'commission_recipient' => $validated['commission_recipient'],
            'commission_date' => $validated['commission_date'],
            'status' => $validated['status'],
            'details' => $validated['details'],
            'attachment_url' => $filePath,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('addresses.show', [$client->id, $validated['address_id']])
            ->with('success', 'Contract created successfully.');
    }

    //edit
    public function edit(Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot edit contracts for a blocked client.');
        }

        return view('pages.contracts.edit', compact('contract','client'));
    }

    //update
    public function update(Request $request, Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot update contracts for a blocked client.');
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id|unique:contracts,address_id,' . $contract->id,
            'type' => 'required|in:L,LS,C,Other',
            'centeral_machines' => 'required|numeric|min:0',
            'unit_machines' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'duration_months' => 'required|numeric|min:1',
            'total_amount' => 'required|numeric|min:0',
            'commission_amount' => 'nullable|numeric|min:0',
            'commission_type' => 'nullable|in:Incentive Bonus,Referral Commission,Other',
            'commission_recipient' => 'nullable|string|max:255',
            'commission_date' => 'nullable|date',
            'status' => 'required|in:draft,signed,active,expired,cancelled',
            'details' => 'required|string',
            'attachment_url' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Recalculate end date based on new start and duration
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = (clone $startDate)->addMonths((int) $validated['duration_months']);

        // Handle file upload (optional)
        $filePath = $contract->attachment_url; // retain old file unless a new one is uploaded
        if ($request->hasFile('attachment_url')) {
            $filePath = $request->file('attachment_url')->store('contracts', 'public');
        }

        $contract->update([
            'address_id' => $validated['address_id'],
            'type' => $validated['type'],
            'centeral_machines' => $validated['centeral_machines'],
            'unit_machines' => $validated['unit_machines'],
            'start_date' => $startDate->toDateString(),
            'duration_months' => $validated['duration_months'],
            'end_date' => $endDate->toDateString(),
            'total_amount' => $validated['total_amount'],
            'paid_amount' => $contract->paid_amount, // retain old paid amount
            'remaining_amount' => $contract->remaining_amount, // retain old remaining amount
            'commission_amount' => $validated['commission_amount'],
            'commission_type' => $validated['commission_type'],
            'commission_recipient' => $validated['commission_recipient'],
            'commission_date' => $validated['commission_date'],
            'status' => $validated['status'],
            'details' => $validated['details'],
            'attachment_url' => $filePath,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('contracts.show', [$client->id, $contract->id])
            ->with('success', 'Contract updated successfully.');
    }

    //destroy
    public function destroy(Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot delete contracts for a blocked client.');
        }

        $contract->delete();

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Contract deleted successfully.');
    }
}
