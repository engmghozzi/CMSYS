<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use App\Models\Address;
use App\Models\Contract;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    //index
    public function index(Request $request, Client $client, User $user,Address $address, Contract $contract)
    {
        $query = Client::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('mobile_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('alternate_mobile_number', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by client type
        if ($request->filled('client_type')) {
            $query->where('client_type', $request->get('client_type'));
        }

        // Filter by created by
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->get('created_by'));
        }

        // Filter by updated by
        if ($request->filled('updated_by')) {
            $query->where('updated_by', $request->get('updated_by'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $clients = $query->orderByDesc('id')->paginate(10)->withQueryString();
        $users = User::orderBy('name')->get();
        
        return view('pages.clients.index',compact('clients', 'client', 'user', 'address', 'contract', 'users'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        
    }

    //show
    public function show(Client $client, Request $request){
         return view('pages.clients.show', compact('client'));
    }

    //destroy
    public function destroy($client)
    {
        $client = Client::findOrFail($client);
        $client->delete();
        
        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully'); 
    }

    //edit
    public function edit(Client $client)
    {
        return view('pages.clients.edit', [
            'client' => $client,
            'editing' => true
        ]);
    }

    //update
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'alternate_mobile_number' => 'nullable|string|max:20',
            'client_type' => 'required|in:Client,Company,Contractor,Other',
            'status' => 'required|in:vip,ordinary,blocked',
        ]);

        $validated['updated_by'] = Auth::id();

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client updated successfully');
    }

    //create
    public function create()
    {
        return view('pages.clients.create');
    }

    //store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            //mobile number must be 8 digits
            'mobile_number' => 'required|string|max:8|min:8',
            'alternate_mobile_number' => 'nullable|string|max:8|min:8',
            'client_type' => 'required|in:Client,Company,Contractor,Other',
            'status' => 'required|in:vip,ordinary,blocked',
        ]);

        $userId = Auth::id();

        Client::create([
            ...$validated,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully!');
    }


  
}
