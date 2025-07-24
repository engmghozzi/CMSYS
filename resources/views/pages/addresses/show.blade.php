<x-layouts.app :title="__('Address Details')">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Address Details') }}</h1>
                <p class="text-gray-600 mt-1">{{ $client->name }} - {{ $address->area }}, {{ $address->block }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('clients.show', $client->id) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back to Client') }}
                </a>
                <a href="{{ route('addresses.edit', [$client->id, $address->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                    </svg>
                    Edit Address
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Address Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Address Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Area</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $address->area }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Block</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $address->block }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Street</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $address->street }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">House Number</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $address->house_num ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Floor Number</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $address->floor_num ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Flat Number</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $address->flat_num ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">PACI Number</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $address->paci_num ?: 'Not specified' }}</p>
                    </div>
                    @if($address->address_notes)
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="text-sm font-medium text-gray-500">Address Notes</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $address->address_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Contracts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $address->contracts->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Payments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $address->payments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Machines</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $address->machines->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contracts Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Contracts</h2>
                <a href="{{ route('contracts.create', $client->id) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Contract
                </a>
            </div>
            <div class="p-6">
                @if ($address->contracts->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No contracts</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a contract for this address.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($address->contracts as $contract)
                            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $contract->contract_num }}</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($contract->status === 'active') bg-green-100 text-green-800
                                                @elseif($contract->status === 'signed') bg-blue-100 text-blue-800
                                                @elseif($contract->status === 'expired') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($contract->status) }}
                                            </span>
                                            @if ($contract->is_fully_collected)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Fully Collected
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600">{{ $contract->type }} Contract</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('contracts.show', [$client->id, $contract->id]) }}"
                                           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Details
                                        </a>
                                        <a href="{{ route('contracts.edit', [$client->id, $contract->id]) }}"
                                           class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('contracts.destroy', [$client->id, $contract->id]) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this contract?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-md bg-red-100 px-3 py-2 text-sm text-red-700 hover:bg-red-200 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Financial Summary</label>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm"><span class="font-medium">Total:</span> {{ number_format($contract->total_amount, 3) }} KWD</p>
                                            <p class="text-sm"><span class="font-medium">Paid:</span> {{ number_format($contract->paid_amount, 3) }} KWD</p>
                                            <p class="text-sm"><span class="font-medium">Remaining:</span> {{ number_format($contract->remaining_amount, 3) }} KWD</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Contract Details</label>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm"><span class="font-medium">Duration:</span> {{ $contract->duration_months }} months</p>
                                            <p class="text-sm"><span class="font-medium">Start:</span> {{ \Carbon\Carbon::parse($contract->start_date)->format('M d, Y') }}</p>
                                            <p class="text-sm"><span class="font-medium">End:</span> {{ \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Machines</label>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm"><span class="font-medium">Central:</span> {{ $contract->centeral_machines }}</p>
                                            <p class="text-sm"><span class="font-medium">Units:</span> {{ $contract->unit_machines }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Commission</label>
                                        <div class="mt-2 space-y-1">
                                            @if($contract->commission_amount)
                                                <p class="text-sm"><span class="font-medium">Amount:</span> {{ number_format($contract->commission_amount, 3) }} KWD</p>
                                                <p class="text-sm"><span class="font-medium">Type:</span> {{ $contract->commission_type }}</p>
                                                <p class="text-sm"><span class="font-medium">Recipient:</span> {{ $contract->commission_recipient }}</p>
                                            @else
                                                <p class="text-sm text-gray-500">No commission</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                @if($contract->attachment_url)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <a href="{{ asset('storage/' . $contract->attachment_url) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            View Contract Document
                                        </a>
                                    </div>
                                @endif
                                
                                @if($contract->details)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <label class="text-sm font-medium text-gray-500">Notes</label>
                                        <p class="mt-1 text-sm text-gray-700">{{ Str::limit($contract->details, 200) }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app> 