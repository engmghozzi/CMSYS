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
        return view('pages.addresses.create', compact('client'));
    }
    
    //show
    public function show(Client $client, Address $address)
    {
        return view('pages.addresses.show', compact('client', 'address'));
    }
    
    //store
    public function store(Request $request, Client $client)
    {
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
        return view('pages.addresses.edit', compact('address','client'));
    }

    //update
    public function update(Request $request,Client $client, Address $address)
    {
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
        $address->delete();

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Address deleted successfully.');
    }
}
