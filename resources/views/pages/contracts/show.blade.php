<x-layouts.app :title="__('Contract Details')">
    <div class="min-h-screen">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Contract Details') }}</h1>
                <p class="text-gray-600 mt-1">{{ $client->name }} - {{ $contract->contract_num }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('addresses.show', [$client->id, $contract->address->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back to Address') }}
                </a>
                @if(auth()->user()->hasPermission('contracts.update'))
                    <a href="{{ route('contracts.edit', [$client->id, $contract->id]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                        </svg>
                        {{ __('Edit Contract') }}
                    </a>
                @endif
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

        <!-- Contract Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 w-full">
            <!-- Header with Status -->
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div class="flex flex-wrap items-center gap-1">
                    <h2 class="text-base font-semibold text-gray-900">{{ __('Contract') }}# {{ $contract->contract_num }}</h2>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                        @if($contract->status === 'active') bg-green-100 text-green-800 border border-green-300
                        @elseif($contract->status === 'expired') bg-red-100 text-red-800 border border-red-300
                        @elseif($contract->status === 'cancelled') bg-orange-100 text-orange-800 border border-orange-300
                        @endif">
                        {{ __($contract->status) }}
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

            <div class="p-4 grid gap-4">
                <!-- Key Details -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-2 bg-gray-50 rounded-lg text-sm">
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Contract Type') }}</label>
                        <p class="font-medium text-gray-900">{{ __($contract->type) }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Duration') }}</label>
                        <p class="font-medium text-gray-900">{{ $contract->duration_months }} {{ __('months') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Start Date') }}</label>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($contract->start_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('End Date') }}</label>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <label class="text-xs text-blue-600">{{ __('Total Amount') }}</label>
                        <p class="text-base font-semibold text-blue-900">{{ number_format($contract->total_amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <label class="text-xs text-green-600">{{ __('Paid Amount') }}</label>
                        <p class="text-base font-semibold text-green-900">{{ number_format($contract->paid_amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div class="p-2 @if($contract->remaining_amount > 0) bg-red-50 @else bg-green-50 @endif rounded-lg">
                        <label class="text-xs @if($contract->remaining_amount > 0) text-red-600 @else text-green-600 @endif">{{ __('Remaining') }}</label>
                        <p class="text-base font-semibold @if($contract->remaining_amount > 0) text-red-900 @else text-green-900 @endif">
                            {{ number_format($contract->remaining_amount, 3) }} {{ __('KWD') }}
                        </p>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="p-2 bg-gray-50 rounded-lg text-sm">
                    <label class="text-xs text-gray-500">{{ __('Contract Address') }}</label>
                    <p class="font-medium text-gray-900">
                        {{ $contract->address->area }} - {{ $contract->address->block }} - {{ $contract->address->street }} - 
                        {{ $contract->address->house_num }} - {{ $contract->address->floor_num }} - {{ $contract->address->flat_num }} - 
                        {{ $contract->address->paci_num }}
                    </p>
                </div>

                <!-- Machines Info -->
                <div class="grid grid-cols-2 gap-3 p-2 bg-gray-50 rounded-lg text-sm">
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Central Machines') }}</label>
                        <p class="font-medium text-gray-900">{{ $contract->centeral_machines }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Unit Machines') }}</label>
                        <p class="font-medium text-gray-900">{{ $contract->unit_machines }}</p>
                    </div>
                </div>

                @if($contract->commission_amount)
                <div class="grid grid-cols-4 gap-3 p-2 bg-yellow-50 rounded-lg text-sm">
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Commission Amount') }}</label>
                        <p class="font-medium text-yellow-900">{{ number_format($contract->commission_amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Commission Type') }}</label>
                        <p class="font-medium text-yellow-900">{{ $contract->commission_type }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Recipient') }}</label>
                        <p class="font-medium text-yellow-900">{{ $contract->commission_recipient }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-yellow-800">{{ __('Commission Date') }}</label>
                        <p class="font-medium text-yellow-900">{{ $contract->commission_date ? \Carbon\Carbon::parse($contract->commission_date)->format('M d, Y') : '-' }}</p>
                    </div>
                </div>
                @endif

                @if($contract->details)
                <div class="p-2 bg-gray-50 rounded-lg text-sm">
                    <label class="text-xs text-gray-500">{{ __('Additional Details') }}</label>
                    <p class="text-gray-900">{{ $contract->details }}</p>
                </div>
                @endif

                <!-- Record Information -->
                <div class="grid grid-cols-2 gap-3 p-2 bg-gray-50 rounded-lg text-sm">
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Created By') }}</label>
                        <p class="font-medium text-gray-900">{{ $contract->creator->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('Updated By') }}</label>
                        <p class="font-medium text-gray-900">{{ $contract->updater->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div x-data="{ tab: '{{ session('active_tab', 'machines') }}' }" class="bg-white rounded-xl shadow-sm border border-gray-200">
            <nav class="flex space-x-2 border-b border-gray-200 px-6 pt-4">
                <button @click="tab = 'machines'" :class="tab === 'machines' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">{{__('Machines')}}</button>
                <button @click="tab = 'payments'" :class="tab === 'payments' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">{{__('Payments')}}</button>
                <button @click="tab = 'visits'" :class="tab === 'visits' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">{{__('Visits')}}</button>
            </nav>
            <div class="p-6">
                <!-- Machines Tab -->
                <div x-show="tab === 'machines'">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">{{__('Machines')}}</h2>
                        @if(auth()->user()->hasPermission('machines.create'))
                            <a href="{{ route('machines.create.from.contract', [$client->id, $contract->id]) }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                {{__('Add Machine')}}
                            </a>
                        @endif
                    </div>
                    @if ($contract->machines->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('No machines')}}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{__('Get started by adding machines for this contract.')}}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($contract->machines as $machine)
                                <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                    <!-- Header -->
                                    <div class="p-4 border-b border-gray-100">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    {{ $machine->brand }}
                                                </span>
                                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $machine->serial_number }}</h3>
                                            </div>
                                            @if(auth()->user()->hasAnyOfPermissions(['machines.update', 'machines.delete', 'machines.read']))
                                                <div class="flex gap-1">
                                                    @if(auth()->user()->hasPermission('machines.read'))
                                                        <a href="{{ route('machines.show', [$client->id, $machine->id]) }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('machines.update'))
                                                        <a href="{{ route('machines.edit', [$client->id, $machine->id]) }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-full transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('machines.delete'))
                                                        <form method="POST" action="{{ route('machines.destroy', [$client->id, $machine->id]) }}"
                                                              onsubmit="return confirm('Are you sure you want to delete this machine?');"
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
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">{{ $machine->type }}</p>
                                    </div>

                                    <!-- Details -->
                                    <div class="p-4 space-y-3">
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <span class="text-gray-500">{{__('Capacity')}}:</span>
                                                <span class="font-medium text-gray-900">{{ $machine->capacity }} {{ $machine->UOM }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{__('Efficiency')}}:</span>
                                                <span class="font-medium text-gray-900">{{ $machine->current_efficiency }}%</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{__('Cost')}}:</span>
                                                <span class="font-medium text-gray-900">{{ number_format($machine->cost, 3) }} {{__('KWD')}}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{__('Brand')}}:</span>
                                                <span class="font-medium text-gray-900">{{ $machine->brand }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($machine->assessment)
                                            <div class="pt-2 border-t border-gray-100">
                                                <p class="text-xs text-gray-500">{{__('Assessment')}}:</p>
                                                <p class="text-sm text-gray-700 line-clamp-2">{{ $machine->assessment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <!-- Payments Tab -->
                <div x-show="tab === 'payments'">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">{{__('Payment Transactions')}}</h2>
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
                            @foreach ($contract->payments as $payment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 min-h-[80px]">
                                        <!-- Payment Info -->
                                        <div class="flex items-center gap-6">
                                            <div>
                                                <p class="text-sm text-gray-600">{{__('Payment No')}} #{{ $payment->id }}</p>
                                                <div class="flex items-center gap-3">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ number_format($payment->amount, 3) }} {{__('KWD')}}</h3>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($payment->status === 'Paid') bg-green-100 text-green-800
                                                        @elseif($payment->status === 'Unpaid') bg-red-100 text-red-800
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
                                                    <p class="text-sm font-medium">{{ $payment->due_date->format('M d, Y') }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-sm text-gray-500">{{__('Payment Date')}}</p>
                                                    <p class="text-sm font-medium">{{ $payment->payment_date->format('M d, Y') }}</p>
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
                                            @if(auth()->user()->hasPermission('payments.update'))
                                                <a href="{{ route('payments.edit', [$client->id, $payment->id]) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('payments.delete'))
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
                <!-- Visits Tab -->
                <div x-show="tab === 'visits'">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">{{__('Visits')}}</h2>
                        @if(auth()->user()->hasPermission('visits.create'))
                            <a href="{{ route('pages.visits.create', [$client->id, $contract->id]) }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                {{__('Add Visit')}}
                            </a>
                        @endif
                    </div>
                    @if ($contract->visits->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('No visits yet')}}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{__('Get started by scheduling a visit for this contract.')}}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($contract->visits as $visit)
                                <div class="bg-white rounded-lg border border-gray-200 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-medium text-gray-900">{{__($visit->visit_type)}}</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($visit->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($visit->status === 'scheduled') bg-blue-100 text-blue-800
                                                    @elseif($visit->status === 'in progress') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{__($visit->visit_status)}}
                                                </span>
                                            </div>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm text-gray-600">{{__('Scheduled Date')}}: {{ $visit->visit_scheduled_date->format('M d, Y') }}</p>
                                                @if($visit->visit_actual_date)
                                                    <p class="text-sm text-gray-600">{{__('Actual Date')}}: {{ $visit->visit_actual_date->format('M d, Y') }}</p>
                                                @endif
                                                @if($visit->technician)
                                                    <p class="text-sm text-gray-600">{{__('Technician')}}: {{ $visit->technician->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-1">
                                            @if(auth()->user()->hasPermission('visits.read'))
                                                <a href="{{ route('pages.visits.show', [$client, $contract, $visit]) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('visits.update'))
                                                <a href="{{ route('pages.visits.edit', [$client, $contract, $visit]) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('visits.delete'))
                                                <form method="POST" action="{{ route('pages.visits.destroy', [$client, $contract, $visit]) }}"
                                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this visit?') }}');"
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
    </div>
</x-layouts.app> 
