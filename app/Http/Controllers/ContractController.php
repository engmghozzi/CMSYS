<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Address;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\ContractsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Filter by status - use dynamic status logic
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                // For active status, we want contracts that are truly active (not expired and not superseded)
                $query->where('status', 'active')
                      ->where('end_date', '>', now())
                      ->whereNotExists(function ($subQuery) {
                          $subQuery->select(DB::raw(1))
                              ->from('contracts as newer_contracts')
                              ->whereColumn('newer_contracts.address_id', 'contracts.address_id')
                              ->where('newer_contracts.status', 'active')
                              ->whereColumn('newer_contracts.created_at', '>', 'contracts.created_at');
                      });
            } elseif ($request->status === 'expired') {
                // For expired status, we want contracts that are expired OR superseded by newer contracts
                $query->where(function ($q) {
                    $q->where('end_date', '<=', now())
                      ->orWhere(function ($subQ) {
                          $subQ->where('status', 'active')
                               ->whereExists(function ($existsQuery) {
                                   $existsQuery->select(DB::raw(1))
                                       ->from('contracts as newer_contracts')
                                       ->whereColumn('newer_contracts.address_id', 'contracts.address_id')
                                       ->where('newer_contracts.status', 'active')
                                       ->whereColumn('newer_contracts.created_at', '>', 'contracts.created_at');
                               });
                      });
                });
            } else {
                // For cancelled status, use the original logic
                $query->where('status', $request->status);
            }
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

        // Note: Renewed contract logic is now handled within the status filtering above

        $contracts = $query->latest()->paginate(10)->withQueryString();

        return view('pages.contracts.globalindex', compact('contracts'));
    }

    //create
    public function create(Request $request, Client $client, Contract $contract = null)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add contracts to a blocked client.');
        }

        // If renewal_contract_id is provided via query, resolve it
        if (!$contract && $request->filled('renewal_contract_id')) {
            $contract = Contract::find($request->input('renewal_contract_id'));
        }

        // If contract is provided, it's a renewal scenario
        if ($contract) {
            // Check if contract can be renewed (expired or cancelled)
            if (!in_array($contract->status, ['expired', 'cancelled']) && !$contract->is_expired) {
                return redirect()->route('contracts.show', [$client, $contract])
                    ->with('error', 'Only expired or cancelled contracts can be renewed.');
            }

            // Check if there's already an active contract for this address
            $activeContract = Contract::where('address_id', $contract->address_id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->first();

            if ($activeContract) {
                return redirect()->route('contracts.show', [$client, $contract])
                    ->with('error', 'Cannot renew contract. There is already an active contract for this address.');
            }

            return view('pages.contracts.create', compact('client', 'contract'));
        }

        return view('pages.contracts.create', compact('client'));
    }

    //createFromAddress
    public function createFromAddress(Request $request, Client $client, $addressId)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot add contracts to a blocked client.');
        }

        // Find the address
        $address = Address::where('id', $addressId)
            ->where('client_id', $client->id)
            ->first();

        if (!$address) {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Invalid address for this client.');
        }

        return view('pages.contracts.create', compact('client', 'address'));
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

        // Check if this is a renewal scenario
        $isRenewal = $request->has('renewal_contract_id');
        $oldContract = null;
        
        if ($isRenewal) {
            $oldContract = Contract::find($request->input('renewal_contract_id'));
            if (!$oldContract || $oldContract->client_id !== $client->id) {
                return redirect()->back()->with('error', 'Invalid renewal contract.');
            }
            
            // Check if old contract can be renewed (expired or cancelled)
            if (!in_array($oldContract->status, ['expired', 'cancelled']) && !$oldContract->is_expired) {
                return redirect()->back()->with('error', 'Only expired or cancelled contracts can be renewed.');
            }
            
            // Check if there's already an active contract for this address
            $activeContract = Contract::where('address_id', $oldContract->address_id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->first();

            if ($activeContract) {
                return redirect()->back()->with('error', 'Cannot renew contract. There is already an active contract for this address.');
            }
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
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
            'status' => 'required|in:active,expired,cancelled',
            'details' => 'nullable|string',
            'attachment_url' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Check if address already has an active contract (only for new contracts, not renewals)
        if (!$isRenewal) {
            $address = Address::find($validated['address_id']);
            if (!$address) {
                return redirect()->back()
                    ->withErrors(['address_id' => 'Address not found.'])
                    ->withInput();
            }

            if (!$address->canHaveNewContract()) {
                return redirect()->back()
                    ->withErrors(['address_id' => 'This address already has an active contract. Only expired contracts can be renewed.'])
                    ->withInput();
            }
        } else {
            // For renewals, use the address from the old contract
            $validated['address_id'] = $oldContract->address_id;
        }

        // Generate start & end date
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = (clone $startDate)->addMonths((int) $validated['duration_months']);

        // Generate contract number: CONT/YY/XYZ
        $year = $startDate->format('y');
        
        // Check if contract number already exists
        do {
            $lastContract = Contract::where('contract_num', 'like', "CONT/{$year}/%")
                ->orderBy('contract_num', 'desc')
                ->first();
                
            if ($lastContract) {
                $lastNumber = (int) substr($lastContract->contract_num, -3);
                $nextNumber = $lastNumber + 1;
                if ($nextNumber > 999) {
                    throw new \Exception('Contract number limit reached for this year');
                }
            } else {
                $nextNumber = 1;
            }
            
            $serialNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $contractNum = "CONT/{$year}/{$serialNumber}";
            
            // Check if this contract number already exists
            $exists = Contract::where('contract_num', $contractNum)->exists();
            
        } while($exists); // Keep trying until we get a unique number

        // Handle file upload to S3 (optional)
        $filePath = null;
        if ($request->hasFile('attachment_url')) {
            $filePath = $request->file('attachment_url')->store('contracts', 's3_contracts');
        }

        // Save the contract
        $newContract = Contract::create([
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
            'details' => $validated['details'] ?? null,
            'attachment_url' => $filePath,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        if ($isRenewal) {
            return redirect()
                ->route('contracts.show', [$client, $newContract])
                ->with('success', 'Contract renewed successfully.');
        }

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

        // Check if contract is expired
        if ($contract->status === 'expired') {
            return redirect()->route('contracts.show', [$client, $contract])
                ->with('error', 'Cannot edit expired contracts.');
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

        // Check if contract is expired
        if ($contract->status === 'expired') {
            return redirect()->route('contracts.show', [$client, $contract])
                ->with('error', 'Cannot update expired contracts.');
        }





        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
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
            'status' => 'required|in:active,expired,cancelled',
            'details' => 'nullable|string',
            'attachment_url' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Enforce only one truly active contract per address when saving an active contract
        if ($validated['status'] === 'active') {
            $hasOtherActive = Contract::where('address_id', $validated['address_id'])
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->where('id', '!=', $contract->id)
                ->exists();
            if ($hasOtherActive) {
                return redirect()->back()
                    ->withErrors(['address_id' => 'This address already has an active contract.'])
                    ->withInput();
            }
        }

        // Recalculate end date based on new start and duration
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = (clone $startDate)->addMonths((int) $validated['duration_months']);

        // Handle file upload/deletion to S3
        $filePath = $contract->attachment_url; // retain old file unless changed
        
        // Check if user wants to delete the attachment
        if ($request->input('delete_attachment') === '1') {
            // Delete the old file from S3 if it exists
            if ($contract->attachment_url) {
                $originalPath = $contract->getRawOriginal('attachment_url');
                Log::info('Attempting to delete attachment from S3', [
                    'contract_id' => $contract->id,
                    'original_path' => $originalPath,
                    'attachment_url' => $contract->attachment_url
                ]);

                try {
                    // Check if file exists before deletion
                    $exists = Storage::disk('s3_contracts')->exists($originalPath);
                    Log::info('File existence check', [
                        'file_path' => $originalPath,
                        'exists' => $exists
                    ]);

                    if ($exists) {
                        $deleted = Storage::disk('s3_contracts')->delete($originalPath);
                        Log::info('S3 deletion result', [
                            'file_path' => $originalPath,
                            'deleted' => $deleted
                        ]);

                        if ($deleted) {
                            Log::info('File successfully deleted from S3', [
                                'contract_id' => $contract->id,
                                'file_path' => $originalPath
                            ]);
                        } else {
                            Log::error('Failed to delete file from S3 - delete() returned false', [
                                'contract_id' => $contract->id,
                                'file_path' => $originalPath
                            ]);
                        }
                    } else {
                        Log::warning('File does not exist in S3', [
                            'contract_id' => $contract->id,
                            'file_path' => $originalPath
                        ]);
                        
                        // Try to list files in the contracts directory to debug
                        try {
                            $files = Storage::disk('s3_contracts')->files('contracts');
                            Log::info('Files in contracts directory', [
                                'total_files' => count($files),
                                'files' => array_slice($files, 0, 10) // Log first 10 files
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to list files in contracts directory', [
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to delete attachment from S3', [
                        'contract_id' => $contract->id,
                        'file_path' => $originalPath,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::info('No attachment to delete', [
                    'contract_id' => $contract->id
                ]);
            }
            $filePath = null; // Set to null to remove attachment
        }
        // Check if a new file is uploaded
        elseif ($request->hasFile('attachment_url')) {
            // Delete the old file from S3 if it exists
            if ($contract->attachment_url) {
                $originalPath = $contract->getRawOriginal('attachment_url');
                Log::info('Attempting to delete old attachment before upload', [
                    'contract_id' => $contract->id,
                    'original_path' => $originalPath
                ]);

                try {
                    $exists = Storage::disk('s3_contracts')->exists($originalPath);
                    if ($exists) {
                        $deleted = Storage::disk('s3_contracts')->delete($originalPath);
                        Log::info('Old file deletion result', [
                            'file_path' => $originalPath,
                            'deleted' => $deleted
                        ]);
                    } else {
                        Log::warning('Old file does not exist in S3', [
                            'file_path' => $originalPath
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to delete old attachment from S3', [
                        'contract_id' => $contract->id,
                        'file_path' => $originalPath,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Upload new file
            $filePath = $request->file('attachment_url')->store('contracts', 's3_contracts');
            Log::info('New file uploaded to S3', [
                'contract_id' => $contract->id,
                'new_file_path' => $filePath,
                'file_size' => $request->file('attachment_url')->getSize(),
                'file_name' => $request->file('attachment_url')->getClientOriginalName()
            ]);
        }

        try {
            // Log the data being updated
            Log::info('Updating contract', [
                'contract_id' => $contract->id,
                'filePath' => $filePath,
                'delete_attachment' => $request->input('delete_attachment')
            ]);

            $updateData = [
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
            ];

            Log::info('Update data prepared', $updateData);

            $contract->update($updateData);

            Log::info('Contract updated successfully', [
                'contract_id' => $contract->id,
                'new_attachment_url' => $contract->fresh()->attachment_url
            ]);

            return redirect()
                ->route('contracts.show', [$client, $contract])
                ->with('success', 'Contract updated successfully.');
        } catch (\Exception $e) {
            Log::error('Contract update failed', [
                'contract_id' => $contract->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update contract: ' . $e->getMessage());
        }
    }

    //destroy
    public function destroy(Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot delete contracts for a blocked client.');
        }

        // Check if contract is expired
        if ($contract->status === 'expired') {
            return redirect()->route('contracts.show', [$client, $contract])
                ->with('error', 'Cannot delete expired contracts.');
        }

        $contract->delete();

        return redirect()
            ->route('clients.show', $client->id)
            ->with('success', 'Contract deleted successfully.');
    }
    
    /**
     * Test S3 connectivity and file operations
     */
    public function testS3Operations(Contract $contract)
    {
        try {
            $disk = Storage::disk('s3_contracts');
            $originalPath = $contract->getRawOriginal('attachment_url');
            
            $result = [
                'contract_id' => $contract->id,
                'original_path' => $originalPath,
                'attachment_url' => $contract->attachment_url,
                'disk_exists' => $disk->exists($originalPath),
                'disk_size' => $disk->exists($originalPath) ? $disk->size($originalPath) : null,
                'bucket_name' => config('filesystems.disks.s3_contracts.bucket'),
                'region' => config('filesystems.disks.s3_contracts.region'),
            ];
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Renew an expired or cancelled contract by redirecting to the create form
     */
    public function renew(Client $client, Contract $contract)
    {
        // Check if client is blocked
        if ($client->status === 'blocked') {
            return redirect()->route('clients.show', $client)
                ->with('error', 'Cannot renew contracts for a blocked client.');
        }

        // Check if contract can be renewed (expired or cancelled)
        if (!in_array($contract->status, ['expired', 'cancelled']) && !$contract->is_expired) {
            return redirect()->route('contracts.show', [$client, $contract])
                ->with('error', 'Only expired or cancelled contracts can be renewed.');
        }

        // Check if there's already an active contract for this address
        $activeContract = Contract::where('address_id', $contract->address_id)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();

        if ($activeContract) {
            return redirect()->route('contracts.show', [$client, $contract])
                ->with('error', 'Cannot renew contract. There is already an active contract for this address.');
        }

        // Redirect to create form with renewal flag via query parameter
        return redirect()->route('contracts.create', [
            'client' => $client->id,
            'renewal_contract_id' => $contract->id,
        ]);
    }

    /**
     * Export contracts to Excel
     */
    public function export(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new ContractsExport($filters), 'contracts-' . now()->format('Y-m-d-H-i-s') . '.xlsx');
    }

    /**
     * Print contracts as PDF
     */
    public function print(Request $request)
    {
        $query = \App\Models\Contract::with(['client', 'address']);

        // Apply the same filters as globalindex
        if ($request->filled('search')) {
            $query->where('contract_num', 'like', "%{$request->search}%");
        }

        if ($request->filled('mobile')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('mobile_number', 'like', "%{$request->mobile}%")
                  ->orWhere('alternate_mobile_number', 'like', "%{$request->mobile}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                // For active status, we want contracts that are truly active (not expired and not superseded)
                $query->where('status', 'active')
                      ->where('end_date', '>', now())
                      ->whereNotExists(function ($subQuery) {
                          $subQuery->select(DB::raw(1))
                              ->from('contracts as newer_contracts')
                              ->whereColumn('newer_contracts.address_id', 'contracts.address_id')
                              ->where('newer_contracts.status', 'active')
                              ->whereColumn('newer_contracts.created_at', '>', 'contracts.created_at');
                      });
            } elseif ($request->status === 'expired') {
                // For expired status, we want contracts that are expired OR superseded by newer contracts
                $query->where(function ($q) {
                    $q->where('end_date', '<=', now())
                      ->orWhere(function ($subQ) {
                          $subQ->where('status', 'active')
                               ->whereExists(function ($existsQuery) {
                                   $existsQuery->select(DB::raw(1))
                                       ->from('contracts as newer_contracts')
                                       ->whereColumn('newer_contracts.address_id', 'contracts.address_id')
                                       ->where('newer_contracts.status', 'active')
                                       ->whereColumn('newer_contracts.created_at', '>', 'contracts.created_at');
                               });
                      });
                });
            } else {
                // For cancelled status, use the original logic
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        if ($request->filled('expiring_months')) {
            $months = (int) $request->expiring_months;
            $today = now()->startOfDay();
            $futureDate = now()->addMonths($months)->endOfDay();
            
            $query->where('end_date', '>=', $today)
                  ->where('end_date', '<=', $futureDate);
        }

        // Note: Renewed contract logic is now handled within the status filtering above

        $contracts = $query->latest()->get();
        $filters = $request->all();

        $pdf = Pdf::loadView('pages.contracts.print', compact('contracts', 'filters'));
        return $pdf->download('contracts-' . now()->format('Y-m-d-H-i-s') . '.pdf');
    }
}
