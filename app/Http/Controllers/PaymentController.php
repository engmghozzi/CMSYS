<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function globalindex(Request $request)
    {
        $payments = \App\Models\Payment::with(['client', 'contract'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('contract', function ($q) use ($search) {
                    $q->where('contract_num', 'like', "%{$search}%");
                });
            })
            ->when($request->mobile, function ($query, $mobile) {
                $query->whereHas('client', function ($q) use ($mobile) {
                    $q->where('mobile_number', 'like', "%{$mobile}%")
                    ->orWhere('alternate_mobile_number', 'like', "%{$mobile}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->method, function ($query, $method) {
                $query->where('method', $method);
            })
            ->when($request->contract_number, function ($query, $contractNumber) {
                $query->whereHas('contract', function ($q) use ($contractNumber) {
                    $q->where('contract_num', 'like', "%{$contractNumber}%");
                });
            })
            ->when($request->mobile_alt, function ($query, $mobileAlt) {
                $query->whereHas('client', function ($q) use ($mobileAlt) {
                    $q->where('mobile_number', 'like', "%{$mobileAlt}%")
                    ->orWhere('alternate_mobile_number', 'like', "%{$mobileAlt}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('pages.payments.globalindex', compact('payments'));
    }

    // Create a new payment for a client
    public function create(Request $request, Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add payments to a blocked client.');
        }

        $contracts = $client->contracts; // fetch contracts related to this client
        return view('payments.create', compact('client', 'contracts'));
    }

    // Create a new payment for a specific contract
    public function createFromContract(Request $request, Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add payments to a blocked client.');
        }

        // Check if contract is fully collected
        if ($contract->is_fully_collected) {
            return redirect()->route('contracts.show', [$client, $contract])
                ->with('error', 'This contract is already fully collected. You cannot add more payments.');
        }

        return view('pages.payments.create-from-contract', compact('client', 'contract'));
    }

    // Store a new payment
    public function store(Request $request, Client $client)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add payments to a blocked client.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date_format:d-m-Y',
            'due_date' => 'required|date_format:d-m-Y',
            'method' => 'required|in:Cash,KNET,Cheque,Wamd,other',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:Unpaid,Paid,Other',
        ]);

        // Only validate payment date if it's provided
        if (!empty($validated['payment_date'])) {
            $paymentDate = \App\Helpers\DateHelper::parseDate($validated['payment_date']);
            $dueDate = \App\Helpers\DateHelper::parseDate($validated['due_date']);
            
            if ($paymentDate->lt($dueDate)) {
                return redirect()->back()
                    ->withErrors(['payment_date' => 'Payment date must be on or after the due date.'])
                    ->withInput();
            }
        }

        $contractId = $request->input('contract_id');
        $contract = \App\Models\Contract::with('payments')->findOrFail($contractId);

        // Calculate total paid so far
        $totalPaid = $contract->payments->sum('amount');
        $remaining = $contract->total_amount - $totalPaid;

        // Prevent overpayment
        if ($remaining <= 0) {
            return redirect()->back()
                ->withErrors(['amount' => 'This contract is already fully collected. You cannot add more payments.'])
                ->withInput();
        }

        // Optional: prevent partial overpayment (e.g., remaining = 200, user submits 250)
        if ($validated['amount'] > $remaining) {
            return redirect()->back()
                ->withErrors(['amount' => "Only {$remaining} is remaining. Please enter a valid amount."])
                ->withInput();
        }

        $validated['client_id'] = $client->id;
        $validated['contract_id'] = $contractId;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Payment::create($validated);

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Payment created successfully.');
    }

    // Store a new payment from a specific contract
    public function storeFromContract(Request $request, Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add payments to a blocked client.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date_format:d-m-Y',
            'due_date' => 'required|date_format:d-m-Y',
            'method' => 'required|in:Cash,KNET,Cheque,Wamd,other',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:Unpaid,Paid,Other',
        ]);

        // Only validate payment date if it's provided
        if (!empty($validated['payment_date'])) {
            $paymentDate = \App\Helpers\DateHelper::parseDate($validated['payment_date']);
            $dueDate = \App\Helpers\DateHelper::parseDate($validated['due_date']);
            
            if ($paymentDate->lt($dueDate)) {
                return redirect()->back()
                    ->withErrors(['payment_date' => 'Payment date must be on or after the due date.'])
                    ->withInput();
            }
        }

        // Calculate total paid so far
        $totalPaid = $contract->payments->sum('amount');
        $remaining = $contract->total_amount - $totalPaid;

        // Prevent overpayment
        if ($remaining <= 0) {
            return redirect()->back()
                ->withErrors(['amount' => 'This contract is already fully collected. You cannot add more payments.'])
                ->withInput();
        }

        // Optional: prevent partial overpayment (e.g., remaining = 200, user submits 250)
        if ($validated['amount'] > $remaining) {
            return redirect()->back()
                ->withErrors(['amount' => "Only {$remaining} is remaining. Please enter a valid amount."])
                ->withInput();
        }

        $validated['client_id'] = $client->id;
        $validated['contract_id'] = $contract->id;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Payment::create($validated);

        return redirect()
            ->route('contracts.show', [$client, $contract])
            ->with('success', 'Payment created successfully.');
    }

    public function show(Client $client, Payment $payment)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot view payments for a blocked client.');
        }

        return view('pages.payments.show', compact('payment', 'client'));
    }

    public function edit(Request $request, Client $client, Payment $payment)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot edit payments for a blocked client.');
        }

        // Check if contract is fully collected
        if ($payment->contract->is_fully_collected) {
            return redirect()->route('contracts.show', [$client, $payment->contract])
                ->with('error', 'This contract is already fully collected. You cannot edit payments.');
        }

        $client = $payment->client;
        $contracts = $client->contracts; // fetch contracts related to this client
        return view('pages.payments.edit', compact('payment', 'client', 'contracts'));
    }

    public function update(Request $request, Client $client, Payment $payment)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot update payments for a blocked client.');
        }

        // Check if contract is fully collected
        if ($payment->contract->is_fully_collected) {
            return redirect()->route('contracts.show', [$client, $payment->contract])
                ->with('error', 'This contract is already fully collected. You cannot update payments.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date_format:d-m-Y',
            'due_date' => 'required|date_format:d-m-Y',
            'method' => 'required|in:Cash,KNET,Cheque,Wamd,other',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:Unpaid,Paid,Other',
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['contract_id'] = $request->input('contract_id'); // Ensure contract_id is provided

        $payment->update($validated);

        // Redirect to contract show page with payments tab active
        return redirect()->route('contracts.show', [$client, $payment->contract])->with('success', 'Payment updated successfully.')->with('active_tab', 'payments');
    }

    public function destroy(Client $client, Payment $payment)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot delete payments for a blocked client.');
        }

        // Check if contract is fully collected
        if ($payment->contract->is_fully_collected) {
            return redirect()->route('contracts.show', [$client, $payment->contract])
                ->with('error', 'This contract is already fully collected. You cannot delete payments.');
        }

        $payment->delete();
        return redirect()->route('clients.show', $client->id)->with('success', 'Payment deleted successfully.');
    }

}
