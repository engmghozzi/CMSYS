<x-layouts.app :title="__('Address Details')">

    <x-ui.form-layout
        :title="__('Address Details')"
        :description="$client->name . ' - ' . $address->area . ', ' . $address->block"
        :back-url="route('clients.show', $client->id)"
        :back-label="__('Back to Client')"
    >
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-6">
            @if(auth()->user()->hasPermission('addresses.update'))
                <a href="{{ route('addresses.edit', [$client->id, $address->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm text-white hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                    </svg>
                    {{ __('Edit Address') }}
                </a>
            @endif
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-4 sm:px-6 py-3 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Address Information') }}</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Area') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->area }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Block') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->block }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Street') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->street }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('House Number') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->house_num ?: __('Not specified') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Floor Number') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->floor_num ?: __('Not specified') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Flat Number') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->flat_num ?: __('Not specified') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('PACI Number') }}</label>
                        <p class="text-sm text-gray-900">{{ $address->paci_num ?: __('Not specified') }}</p>
                    </div>
                    @if($address->address_notes)
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="text-xs font-medium text-gray-500">{{ __('Address Notes') }}</label>
                            <p class="text-sm text-gray-900">{{ $address->address_notes }}</p>
                        </div>
                    @endif
                </div>
                <div class="flex flex-wrap justify-between text-xs text-gray-500 mt-3 pt-3 border-t border-gray-200">
                    <div class="space-x-1">
                        <span>{{ __('Created by') }}: {{ $address->creator->name }}</span>
                        <span>•</span>
                        <span>{{ $address->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="space-x-1">
                        <span>{{ __('Updated by') }}: {{ $address->updater->name }}</span>
                        <span>•</span>
                        <span>{{ $address->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ __('Contracts') }}</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $address->contracts->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ __('Total Payments') }}</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $address->payments->count() }}</p>
                    </div>
                </div>
            </div>


        </div>

        <!-- Contracts Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('Contracts') }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ __('All contracts for this address') }} ({{ $address->contracts->count() }} {{ __('total') }})</p>
                </div>
                @if(auth()->user()->hasPermission('contracts.create') && !$address->getActiveContract())
                    <a href="{{ route('contracts.create.from-address', [$client->id, $address->id]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Add Contract') }}
                    </a>
                @endif
            </div>
            <div class="p-4 sm:p-6">
                @if ($address->contracts->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No contracts') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Get started by creating a contract for this address.') }}</p>
                    </div>
                @else
                    <!-- Contract Status Summary (time-aware) -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $now = now();
                            $activeContracts = $address->contracts->filter(function($contract) use ($now) {
                                return $contract->status === 'active' && $contract->end_date > $now;
                            })->count();
                            $expiredContracts = $address->contracts->filter(function($contract) use ($now) {
                                return $contract->status === 'expired' || ($contract->status === 'active' && $contract->end_date <= $now);
                            })->count();
                            $cancelledContracts = $address->contracts->where('status', 'cancelled')->count();
                            $pastDueContracts = $address->contracts->filter(function($contract) use ($now) {
                                return $contract->status === 'active' && $contract->end_date <= $now;
                            })->count();
                        @endphp
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ __('Active Contracts') }}</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $activeContracts }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ __('Expired Contracts') }}</p>
                                    <p class="text-2xl font-bold text-red-900">{{ $expiredContracts }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-800">{{ __('Cancelled Contracts') }}</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $cancelledContracts }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Contracts List -->
                    <div class="space-y-3">
                        @foreach ($address->contracts->sortByDesc('created_at') as $contract)
                            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200
                                @if($contract->dynamic_status === 'active') border-l-4 border-l-green-500
                                @elseif($contract->dynamic_status === 'expired') border-l-4 border-l-red-500
                                @elseif($contract->dynamic_status === 'cancelled') border-l-4 border-l-gray-500
                                @endif">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                                    <!-- Contract Basic Info -->
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-base font-semibold text-gray-900">{{ $contract->contract_num }}</h3>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($contract->dynamic_status === 'active') bg-green-100 text-green-800
                                                    @elseif($contract->dynamic_status === 'expired') bg-red-100 text-red-800
                                                    @elseif($contract->dynamic_status === 'cancelled') bg-gray-100 text-gray-800
                                                    @else bg-orange-100 text-orange-800
                                                    @endif">
                                                    {{ __($contract->dynamic_status) }}
                                                </span>
                                                @if ($contract->is_fully_collected)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ __('Fully Collected') }}
                                                    </span>
                                                @endif
                                                @if($contract->is_expired && $contract->status === 'active')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        {{ __('Past Due') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $contract->type }} {{ __('Contract') }}</p>
                                        </div>
                                    </div>

                                    <!-- Financial Summary -->
                                    <div class="flex flex-wrap items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">{{ __('Total') }}:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ number_format($contract->total_amount, 3) }} {{ __('KWD') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">{{ __('Paid') }}:</span>
                                            <span class="text-sm font-semibold 
                                                @if($contract->is_fully_collected) text-green-600 @else text-gray-900 @endif">
                                                {{ number_format($contract->paid_amount, 3) }} {{ __('KWD') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">{{ __('Remaining') }}:</span>
                                            <span class="text-sm font-semibold 
                                                @if($contract->remaining_amount > 0) text-red-600 @else text-green-600 @endif">
                                                {{ number_format($contract->remaining_amount, 3) }} {{ __('KWD') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-0.5">
                                        @if(auth()->user()->hasPermission('contracts.read'))
                                            <a href="{{ route('contracts.show', [$client, $contract]) }}"
                                            class="p-1.5 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                            title="{{ __('View Contract') }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('contracts.update') && ($contract->status === 'active' && !$contract->is_expired || (auth()->user()->role && auth()->user()->role->name === 'super_admin' && $contract->status === 'expired')))
                                            <a href="{{ route('contracts.edit', [$client->id, $contract->id]) }}"
                                            class="p-1.5 rounded-md bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors"
                                            title="{{ __('Edit Contract') }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                </svg>
                                            </a>
                                        @elseif(auth()->user()->hasPermission('contracts.update') && ($contract->status !== 'active' || $contract->is_expired) && !(auth()->user()->role && auth()->user()->role->name === 'super_admin' && $contract->status === 'expired'))
                                            <span class="p-1.5 rounded-md bg-gray-50 text-gray-300 cursor-not-allowed" 
                                                  title="{{ __('Cannot edit') }} {{ $contract->is_expired ? __('expired') : __($contract->status) }} {{ __('contracts') }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                </svg>
                                            </span>
                                        @endif
                                        @if(auth()->user()->hasPermission('contracts.destroy') && ($contract->status === 'active' && !$contract->is_expired || (auth()->user()->role && auth()->user()->role->name === 'super_admin' && $contract->status === 'expired')))
                                            <form method="POST" action="{{ route('contracts.destroy', [$client->id, $contract->id]) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this contract?');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-1.5 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                        title="{{ __('Delete Contract') }}">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                                    </svg>
                                                </button>                        
                                            </form> 
                                        @elseif(auth()->user()->hasPermission('contracts.destroy') && ($contract->status !== 'active' || $contract->is_expired) && !(auth()->user()->role && auth()->user()->role->name === 'super_admin' && $contract->status === 'expired'))
                                            <span class="p-1.5 rounded-md bg-gray-50 text-gray-300 cursor-not-allowed" 
                                                  title="{{ __('Cannot delete') }} {{ $contract->is_expired ? __('expired') : __($contract->status) }} {{ __('contracts') }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                                </svg>
                                            </span>
                                        @endif

                                        <!-- Renew Button for Expired Contracts -->
                                        @if(in_array($contract->status, ['expired', 'cancelled']) && auth()->user()->hasPermission('contracts.create'))
                                            @if($contract->canRenew)
                                                <a href="{{ route('contracts.renew', [$client->id, $contract->id]) }}"
                                                class="p-1.5 rounded-md bg-green-50 text-green-600 hover:bg-green-100 transition-colors"
                                                title="{{ __('Renew Contract') }}">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="p-1.5 rounded-md bg-gray-50 text-gray-300 cursor-not-allowed" 
                                                      title="{{ __('Cannot renew - Address has active contract') }}">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <div class="flex flex-wrap items-center gap-4 text-xs">
                                        <span class="text-gray-500">{{ __('Duration') }}:</span>
                                        <span class="font-medium">{{ $contract->duration_months }} {{ __('months') }}</span>
                                        
                                        <span class="text-gray-500">{{ __('Start') }}:</span>
                                        <span class="font-medium">{{ \App\Helpers\DateHelper::formatDate($contract->start_date) }}</span>
                                        
                                        <span class="text-gray-500">{{ __('End') }}:</span>
                                        <span class="font-medium">{{ \App\Helpers\DateHelper::formatDate($contract->end_date) }}</span>
                                        
                                        @if($contract->commission_amount)
                                            <span class="text-gray-500">{{ __('Commission') }}:</span>
                                            <span class="font-medium">{{ number_format($contract->commission_amount, 3) }} KWD</span>
                                        @else
                                            <span class="text-gray-500">{{ __('No commission') }}</span>
                                        @endif
                                        
                                        @if($contract->attachment_url)
                                            <a href="{{ $contract->attachment_url }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ __('Document') }}
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if($contract->details)
                                        <div class="mt-1 text-gray-700 text-xs">
                                            {{ Str::limit($contract->details, 100) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-ui.form-layout>

</x-layouts.app>