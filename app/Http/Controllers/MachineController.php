<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Machine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\MachineBrand;
use App\Enums\MachineType;

class MachineController extends Controller
{
    public function index(Client $client)
    {
        $machines = $client->machines()->with(['contract', 'creator', 'updater'])->get();
        return view('pages.clients.show', compact('client', 'machines'));
    }
    public function create(Request $request,Client $client,Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add machines to a blocked client.');
        }

        return view('pages.machines.create', compact('client', 'contract'));
    }

    // Create a new machine for a specific contract
    public function createFromContract(Request $request, Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add machines to a blocked client.');
        }

        return view('pages.machines.create-from-contract', compact('client', 'contract'));
    }

    // Store a new machine from a specific contract
    public function storeFromContract(Request $request, Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add machines to a blocked client.');
        }

        $data = $request->validate([
            'serial_number' => 'required|string|min:0',
            'brand' => ['required', 'string', Rule::in(array_column(MachineBrand::cases(), 'value'))],
            'type'=>['required','string',Rule::in(array_column(MachineType::cases(), 'value'))],
            'UOM' => 'required|string|max:50',
            'capacity' => 'required|numeric|min:0',
            'current_efficiency' => 'required|numeric|min:0|max:100',
            'cost' => 'required|numeric|min:0',
            'assessment' => 'nullable|string|max:500',
        ]);

        $data['client_id'] = $client->id;
        $data['contract_id'] = $contract->id;
        $data['address_id'] = $contract->address_id; // Auto-use contract's address
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        Machine::create($data);

        return redirect()->route('contracts.show', [$client, $contract])
            ->with('success', 'Machine created successfully.');
    }

    public function store(Request $request, Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add machines to a blocked client.');
        }

        $data = $request->validate([
            'serial_number' => 'required|string|max:100',
            'brand' => ['required', 'string', Rule::in(array_column(MachineBrand::cases(), 'value'))],
            'type'=>['required','string',Rule::in(array_column(MachineType::cases(), 'value'))],
            'UOM' => 'required|string|max:50',
            'capacity' => 'required|numeric|min:0',
            'current_efficiency' => 'required|numeric|min:0|max:100',
            'cost' => 'required|numeric|min:0',
            'assessment' => 'nullable|string|max:500',
            'contract_id' => 'required|exists:contracts,id',
            'address_id' => 'required|exists:addresses,id',
        ]);

        $data['client_id'] = $client->id;
        $data['address_id'] = $request->input('address_id'); 
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        Machine::create($data);

        return redirect()->route('clients.show', $client)->with('success', 'Machine created successfully.');
    }

    public function destroy(Client $client, Machine $machine)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot delete machines for a blocked client.');
        }

        $machine->delete();
        return redirect()->route('clients.show', $client)->with('success', 'Machine deleted successfully.');
    }
    public function edit(Client $client, Machine $machine)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot edit machines for a blocked client.');
        }

        return view('pages.machines.edit', compact('client', 'machine'));
    }
    public function update(Request $request, Client $client, Machine $machine)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot update machines for a blocked client.');
        }

        $data = $request->validate([
            'serial_number' => 'required|string|max:100',
            'brand' => ['required', 'string', Rule::in(array_column(MachineBrand::cases(), 'value'))],
            'type'=>['required','string',Rule::in(array_column(MachineType::cases(), 'value'))],
            'UOM' => 'required|string|max:50',
            'capacity' => 'required|numeric|min:0',
            'current_efficiency' => 'required|numeric|min:0|max:100',
            'cost' => 'required|numeric|min:0',
            'assessment' => 'nullable|string|max:500',
            'contract_id' => 'required|exists:contracts,id',
            'address_id' => 'required|exists:addresses,id',
        ]);

        $data['updated_by'] = Auth::id();

        $machine->update($data);

        // Redirect to contract show page with machines tab active
        return redirect()->route('contracts.show', [$client, $machine->contract])->with('success', 'Machine updated successfully.')->with('active_tab', 'machines');
    }
    public function show(Client $client, Machine $machine)
    {
        return view('pages.machines.show', compact('client', 'machine'));
    }

}


