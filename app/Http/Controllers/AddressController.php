<?php

namespace App\Http\Controllers;
use App\Models\Address;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    //create
    public function create(Request $request,Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add addresses to a blocked client.');
        }

        return view('pages.addresses.create', compact('client'));
    }
    
    //show
    public function show(Client $client, Address $address)
    {
        // Eager load contracts with all related data
        $address->load([
            'contracts' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'contracts.payments',

            'contracts.creator',
            'contracts.updater'
        ]);
        
        return view('pages.addresses.show', compact('client', 'address'));
    }
    
    //store
    public function store(Request $request, Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add addresses to a blocked client.');
        }

        $validated = $request->validate([
            'area' => 'required|string|max:255',
            'block' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_num' => 'nullable|string|max:255',
            'floor_num' => 'nullable|string|max:255',
            'flat_num' => 'nullable|string|max:255',
            'paci_num' => 'nullable|integer',
            'address_notes' => 'nullable|string',
        ]);
        
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $client->addresses()->create($validated);

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Address created successfully.');
    }

    //edit
    public function edit(Client $client, Address $address)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot edit addresses for a blocked client.');
        }

        // Check if address has active contract
        if (!$address->canHaveNewContract()) {
            return redirect()->route('addresses.show', [$client->id, $address->id])
                ->with('error', 'Cannot edit address with an active contract.');
        }

        return view('pages.addresses.edit', compact('address','client'));
    }

    //update
    public function update(Request $request,Client $client, Address $address)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot update addresses for a blocked client.');
        }

        // Check if address has active contract
        if (!$address->canHaveNewContract()) {
            return redirect()->route('addresses.show', [$client->id, $address->id])
                ->with('error', 'Cannot update address with an active contract.');
        }

        $validated = $request->validate([
            'area' => 'required|string|max:255',
            'block' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_num' => 'nullable|string|max:255',
            'floor_num' => 'nullable|string|max:255',
            'flat_num' => 'nullable|string|max:255',
            'paci_num' => 'nullable|integer',
            'address_notes' => 'nullable|string',
        ]);

        $validated['updated_by'] = Auth::id();

        $address->update($validated);

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Address updated successfully.');
    }

    //destroy
    public function destroy(Client $client, Address $address)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot delete addresses for a blocked client.');
        }

        // Check if address has active contract
        if (!$address->canHaveNewContract()) {
            return redirect()->route('addresses.show', [$client->id, $address->id])
                ->with('error', 'Cannot delete address with an active contract.');
        }

        $address->delete();

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Address deleted successfully.');
    }
}
