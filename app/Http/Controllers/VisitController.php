<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Visit;
use App\Models\Client;
use App\Models\Contract;
use App\Models\User;

class VisitController extends Controller
{
    public function globalIndex(Request $request)
    {
        $query = Visit::with(['client', 'contract', 'technician', 'createdBy', 'updatedBy', 'address']);

        // Search by contract number
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->whereHas('contract', function($q) use ($searchTerm) {
                $q->where('contract_num', 'like', $searchTerm);
            });
        }

        // Filter by visit type
        if ($request->filled('visit_type') && $request->visit_type !== '') {
            $query->where('visit_type', $request->visit_type);
        }

        // Filter by visit status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('visit_status', $request->status);
        }

        $visits = $query->latest()->paginate(10)->withQueryString();
        $users = User::all();

        return view('pages.visits.globalindex', compact('visits', 'users'));
    }


    public function create(Client $client, Contract $contract)
    {
        $technicians = User::all();
        return view('pages.visits.create', compact('client', 'contract', 'technicians'));
    }

    public function store(Request $request, Client $client, Contract $contract) 
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
            'visit_scheduled_date' => 'required|date',
            'visit_actual_date' => 'nullable|date',
            'visit_type' => 'required|in:proactive,maintenance,repair,installation,other',
            'visit_status' => 'required|in:scheduled,completed,cancelled',
            'visit_notes' => 'nullable|string',

        ]);

        $validated['client_id'] = $client->id;
        $validated['address_id'] = $contract->address_id;
        $validated['contract_id'] = $contract->id;
        $validated['created_by'] =Auth::id();
        $validated['updated_by'] = Auth::id();

        $visit = Visit::create($validated);
        return redirect()->route('contracts.show', [$client, $contract])
            ->with('success', 'Visit created successfully.');
    }

    public function show(Client $client, Contract $contract, Visit $visit)
    {
        $technicians = User::all();
        return view('pages.visits.show', compact('visit', 'client', 'contract', 'technicians'));
    }

    public function edit(Client $client, Contract $contract, Visit $visit)
    {
        if ($visit->visit_status === 'completed') {
            return redirect()->route('pages.visits.show', [$client, $contract, $visit])
                ->with('error', 'Cannot edit a completed visit.');
        }

        $technicians = User::all();
        return view('pages.visits.edit', compact('visit', 'client', 'contract', 'technicians'));
    }

    public function update(Request $request, Client $client, Contract $contract, Visit $visit)
    {
        if ($visit->visit_status === 'completed') {
            return redirect()->route('pages.visits.show', [$client, $contract, $visit])
                ->with('error', 'Cannot update a completed visit.');
        }

        try {
            $validated = $request->validate([
                'technician_id' => 'required|exists:users,id',
                'visit_scheduled_date' => 'required|date',
                'visit_actual_date' => 'nullable|date',
                'visit_type' => 'required|in:proactive,maintenance,repair,installation,other',
                'visit_status' => 'required|in:scheduled,completed,cancelled',
                'visit_notes' => 'nullable|string',
            ]);

            $validated['updated_by'] = Auth::id();
            $visit->update($validated);
            
            return redirect()->route('pages.visits.globalindex')
                ->with('success', 'Visit updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update visit: ' . $e->getMessage()]);
        }
    }

    public function destroy(Client $client, Contract $contract, Visit $visit)
    {
        if ($visit->visit_status === 'completed') {
            return redirect()->route('pages.visits.show', [$client, $contract, $visit])
                ->with('error', 'Cannot delete a completed visit.');
        }

        $visit->delete();
        return redirect()->route('pages.visits.globalindex');
    }

}