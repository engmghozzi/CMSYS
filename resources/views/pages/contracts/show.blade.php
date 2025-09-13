<x-layouts.app :title="__('Contract Details')">

    <x-ui.form-layout
        :title="__('Contract Details')"
        :description="$client->name . ' - ' . $contract->contract_num"
        :back-url="route('addresses.show', [$client->id, $contract->address->id])"
        :back-label="__('Back to Address')"
    >
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-6">
            @if(auth()->user()->hasPermission('contracts.update') && ($contract->status === 'active' && !$contract->is_expired || (auth()->user()->role && auth()->user()->role->name === 'super_admin' && $contract->status === 'expired')))
                <a href="{{ route('contracts.edit', [$client->id, $contract->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm text-white hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                    </svg>
                    {{ __('Edit Contract') }}
                </a>
            @endif

            @if((($contract->canRenew) || ($contract->is_expired && !$contract->address->getActiveContract())) && auth()->user()->hasPermission('contracts.create'))
                <a href="{{ route('contracts.renew', [$client->id, $contract->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 sm:px-4 py-2 text-sm text-white hover:bg-green-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ __('Renew Contract') }}
                </a>
            @elseif((in_array($contract->status, ['expired', 'cancelled']) || $contract->is_expired) && ($contract->address->getActiveContract()))
                <span class="inline-flex items-center gap-2 rounded-lg bg-gray-400 px-3 sm:px-4 py-2 text-sm text-white cursor-not-allowed" 
                      title="{{ __('Cannot renew - Address has active contract') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ __('Renew Contract') }}
                </span>
            @endif
        </div>

        @if($contract->address->getActiveContract() && $contract->status === 'expired')
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-blue-800 font-medium">{{ __('This address has an active contract. Renew operations are disabled for expired contracts.') }}</span>
                </div>
            </div>
        @endif

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

        <!-- Contract Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 w-full">
            <!-- Header with Status -->
            <div class="px-4 sm:px-6 py-3 border-b border-gray-200 bg-gray-50 rounded-t-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div class="flex flex-wrap items-center gap-1">
                    <h2 class="text-base font-semibold text-gray-900">{{ __('Contract') }}# {{ $contract->contract_num }}</h2>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                        @if($contract->dynamic_status === 'active') bg-green-100 text-green-800 border border-green-300
                        @elseif($contract->dynamic_status === 'expired') bg-red-100 text-red-800 border border-red-300
                        @elseif($contract->dynamic_status === 'cancelled') bg-orange-100 text-orange-800 border border-orange-300
                        @endif">
                        {{ __($contract->dynamic_status) }}
                    </span>
                    @if ($contract->is_fully_collected)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300">
                            {{ __('Fully Collected') }}
                        </span>
                    @endif
                </div>
                @if($contract->attachment_url)
                    <a href="{{ $contract->attachment_url }}" 
                       target="_blank"
                       class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1 rounded-full transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('View Document') }}
                    </a>
                @endif
            </div>

            <div class="p-4 sm:p-6 grid gap-4">
                <!-- Key Details -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-2 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Contract Type') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ __($contract->type) }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Central Machines') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $contract->centeral_machines ?? 0 }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Unit Machines') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $contract->unit_machines ?? 0 }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Duration') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $contract->duration_months }} {{ __('months') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Start Date') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ \App\Helpers\DateHelper::formatDate($contract->start_date) }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('End Date') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ \App\Helpers\DateHelper::formatDate($contract->end_date) }}</p>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <label class="text-xs text-blue-600">{{ __('Total Amount') }}</label>
                        <p class="text-sm font-semibold text-blue-900">{{ number_format($contract->total_amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <label class="text-xs text-green-600">{{ __('Paid Amount') }}</label>
                        <p class="text-sm font-semibold text-green-900">{{ number_format($contract->paid_amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div class="p-2 @if($contract->remaining_amount > 0) bg-red-50 @else bg-green-50 @endif rounded-lg">
                        <label class="text-xs @if($contract->remaining_amount > 0) text-red-600 @else text-green-600 @endif">{{ __('Remaining') }}</label>
                        <p class="text-sm font-semibold @if($contract->remaining_amount > 0) text-red-900 @else text-green-900 @endif">
                            {{ number_format($contract->remaining_amount, 3) }} {{ __('KWD') }}
                        </p>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="p-2 bg-gray-50 rounded-lg">
                    <label class="text-xs text-gray-500">{{ __('Contract Address') }}</label>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $contract->address->area }} - {{ $contract->address->block }} - {{ $contract->address->street }} - 
                        {{ $contract->address->house_num }} - {{ $contract->address->floor_num }} - {{ $contract->address->flat_num }} - 
                        {{ $contract->address->paci_num }}
                    </p>
                </div>



                @if($contract->commission_amount)
                <div class="grid grid-cols-4 gap-3 p-2 bg-yellow-50 rounded-lg">
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Commission Amount') }}</label>
                        <p class="text-sm font-medium text-yellow-900">{{ number_format($contract->commission_amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Commission Type') }}</label>
                        <p class="text-sm font-medium text-yellow-900">{{ $contract->commission_type }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Recipient') }}</label>
                        <p class="text-sm font-medium text-yellow-900">{{ $contract->commission_recipient }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Commission Date') }}</label>
                        <p class="text-sm font-medium text-yellow-900">{{ $contract->commission_date ? \App\Helpers\DateHelper::formatDate($contract->commission_date) : '-' }}</p>
                    </div>
                </div>
                @endif

                @if($contract->details)
                <div class="p-2 bg-gray-50 rounded-lg">
                    <label class="text-xs text-gray-500">{{ __('Additional Details') }}</label>
                    <p class="text-sm text-gray-900">{{ $contract->details }}</p>
                </div>
                @endif

                <!-- Record Information -->
                <div class="grid grid-cols-2 gap-3 p-2 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Created By') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $contract->creator->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Updated By') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $contract->updater->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div x-data="{ tab: '{{ session('active_tab', 'payments') }}' }" class="bg-white rounded-xl shadow-sm border border-gray-200">
            <nav class="flex space-x-2 border-b border-gray-200 px-4 sm:px-6 pt-4">
                <button @click="tab = 'payments'" :class="tab === 'payments' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">{{__('Payments')}}</button>
            </nav>
            
            <!-- Payments Tab -->
            <div x-show="tab === 'payments'" class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{__('Payment Transactions')}}</h2>
                        @php
                            $totalPaid = $contract->payments->sum('amount');
                            $remaining = $contract->total_amount - $totalPaid;
                        @endphp
                        @if(auth()->user()->hasPermission('payments.create') && $remaining > 0)
                            <a href="{{ route('payments.create.from.contract', [$client->id, $contract->id]) }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                {{__('Add Payment')}}
                            </a>
                        @endif
                    </div>
                    @if ($contract->payments->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('No payments')}}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{__('Get started by recording a payment for this contract.')}}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($contract->payments->sortByDesc('created_at') as $payment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 min-h-[80px]">
                                        <!-- Payment Info -->
                                        <div class="flex items-center gap-6">
                                            <div>
                                                <p class="text-sm text-gray-600">{{__('Payment No')}} #{{ $payment->id }}</p>
                                                <div class="flex items-center gap-3">
                                                    <h3 class="text-base font-semibold text-gray-900">{{ number_format($payment->amount, 3) }} {{__('KWD')}}</h3>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($payment->status === 'Paid') bg-green-100 text-green-800
                                                        @elseif($payment->status === 'Unpaid') bg-red-100 text-red-800
                                                        @elseif($payment->status === 'Other') bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ $payment->status }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex gap-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">{{__('Payment Method')}}</p>
                                                    <p class="text-sm font-medium">{{ $payment->method }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">{{__('Due Date')}}</p>
                                                    <p class="text-sm font-medium">{{ \App\Helpers\DateHelper::formatDate($payment->due_date) }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-sm text-gray-500">{{__('Payment Date')}}</p>
                                                    <p class="text-sm font-medium">{{ \App\Helpers\DateHelper::formatDate($payment->payment_date) }}</p>
                                                </div>
                                                
                                                @if($payment->notes)
                                                <div class="max-w-xs">
                                                    <p class="text-sm text-gray-500">{{__('Payment Notes')}}</p>
                                                    <p class="text-sm font-medium truncate">{{ $payment->notes }}</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-1 shrink-0">
                                            @if(auth()->user()->hasPermission('payments.read'))
                                                <a href="{{ route('payments.show', [$client->id, $payment->id]) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>        
                                            @endif
                                            @if(auth()->user()->hasPermission('payments.update') && !$contract->address->getActiveContract())
                                                <a href="{{ route('payments.edit', [$client->id, $payment->id]) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('payments.delete') && ($contract->status === 'active' && !$contract->is_expired || (auth()->user()->role && auth()->user()->role->name === 'super_admin' && $contract->status === 'expired')))
                                                <form method="POST" action="{{ route('payments.destroy', [$client->id, $payment->id]) }}"
                                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this payment?') }}');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-full transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m4-12v1H6V5a1 1 0 011-1h10a1 1 0 011 1z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-ui.form-layout>

</x-layouts.app> 
