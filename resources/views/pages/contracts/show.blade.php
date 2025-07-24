<x-layouts.app :title="__('Contract Details')">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
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
                <a href="{{ route('contracts.edit', [$client->id, $contract->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                    </svg>
                    Edit Contract
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

        <!-- Contract Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Contract Information</h2>
                <div class="flex gap-2">
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
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Contract Number</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $contract->contract_num }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Contract Type</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $contract->type }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Duration</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $contract->duration_months }} months</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Total Amount</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($contract->total_amount, 3) }} KWD</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Start Date</label>
                        <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($contract->start_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">End Date</label>
                        <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Paid Amount</label>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($contract->paid_amount, 3) }} KWD</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Remaining Amount</label>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($contract->remaining_amount, 3) }} KWD</p>
                    </div>
                </div>
                @if($contract->commission_amount)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Commission Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Commission Amount</label>
                                <p class="mt-1 text-lg text-gray-900">{{ number_format($contract->commission_amount, 3) }} KWD</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Commission Type</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $contract->commission_type }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Recipient</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $contract->commission_recipient }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if($contract->details)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="text-sm font-medium text-gray-500">Contract Details</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $contract->details }}</p>
                    </div>
                @endif
                @if($contract->attachment_url)
                    <div class="mt-6 pt-6 border-t border-gray-200">
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
            </div>
        </div>

        <!-- Tabbed Content -->
        <div x-data="{ tab: 'machines' }" class="bg-white rounded-xl shadow-sm border border-gray-200">
            <nav class="flex space-x-2 border-b border-gray-200 px-6 pt-4">
                <button @click="tab = 'machines'" :class="tab === 'machines' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">Machines</button>
                <button @click="tab = 'payments'" :class="tab === 'payments' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">Payments</button>
                <button @click="tab = 'visits'" :class="tab === 'visits' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-t-lg font-medium focus:outline-none transition-colors">Visits</button>
            </nav>
            <div class="p-6">
                <!-- Machines Tab -->
                <div x-show="tab === 'machines'">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Machines</h2>
                        @if(auth()->user()->hasPermission('machines.create'))
                            <a href="{{ route('machines.create.from.contract', [$client->id, $contract->id]) }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Machine
                            </a>
                        @endif
                    </div>
                    @if ($contract->machines->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No machines</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding machines for this contract.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($contract->machines as $machine)
                                <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $machine->serial_number }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $machine->brand }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600">{{ $machine->type }} - {{ $machine->UOM }}</p>
                                        </div>
                                        @if(auth()->user()->hasAnyOfPermissions(['machines.update', 'machines.delete']))
                                            <div class="flex gap-2">
                                                @if(auth()->user()->hasPermission('machines.update'))
                                                    <a href="{{ route('machines.edit', [$client->id, $machine->id]) }}"
                                                       class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif
                                                @if(auth()->user()->hasPermission('machines.delete'))
                                                    <form method="POST" action="{{ route('machines.destroy', [$client->id, $machine->id]) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this machine?');"
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
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Specifications</label>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm"><span class="font-medium">Capacity:</span> {{ $machine->capacity }} {{ $machine->UOM }}</p>
                                                <p class="text-sm"><span class="font-medium">Efficiency:</span> {{ $machine->current_efficiency }}%</p>
                                                <p class="text-sm"><span class="font-medium">Cost:</span> {{ number_format($machine->cost, 3) }} KWD</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Assessment</label>
                                            <div class="mt-2 space-y-1">
                                                @if($machine->assessment)
                                                    <p class="text-sm text-gray-700">{{ Str::limit($machine->assessment, 100) }}</p>
                                                @else
                                                    <p class="text-sm text-gray-500">No assessment notes</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Status</label>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm"><span class="font-medium">Brand:</span> {{ $machine->brand }}</p>
                                                <p class="text-sm"><span class="font-medium">Type:</span> {{ $machine->type }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <!-- Payments Tab -->
                <div x-show="tab === 'payments'">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Payment Transactions</h2>
                        @if(auth()->user()->hasPermission('payments.create'))
                            <a href="{{ route('payments.create.from.contract', [$client->id, $contract->id]) }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Payment
                            </a>
                        @endif
                    </div>
                    @if ($contract->payments->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No payments</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by recording a payment for this contract.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($contract->payments as $payment)
                                <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ number_format($payment->amount, 3) }} KWD</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($payment->status === 'Paid') bg-green-100 text-green-800
                                                    @elseif($payment->status === 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($payment->status === 'Overdue') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $payment->status }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600">Payment #{{ $payment->id }}</p>
                                        </div>
                                        @if(auth()->user()->hasAnyOfPermissions(['payments.update', 'payments.delete']))
                                            <div class="flex gap-2">
                                                @if(auth()->user()->hasPermission('payments.update'))
                                                    <a href="{{ route('payments.edit', [$client->id, $payment->id]) }}"
                                                       class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif
                                                @if(auth()->user()->hasPermission('payments.delete'))
                                                    <form method="POST" action="{{ route('payments.destroy', [$client->id, $payment->id]) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this payment?');"
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
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Payment Details</label>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm"><span class="font-medium">Method:</span> {{ $payment->method }}</p>
                                                <p class="text-sm"><span class="font-medium">Payment Date:</span> {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                                <p class="text-sm"><span class="font-medium">Due Date:</span> {{ \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Notes</label>
                                            <div class="mt-2 space-y-1">
                                                @if($payment->notes)
                                                    <p class="text-sm text-gray-700">{{ Str::limit($payment->notes, 100) }}</p>
                                                @else
                                                    <p class="text-sm text-gray-500">No notes</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Status</label>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm"><span class="font-medium">Status:</span> {{ $payment->status }}</p>
                                                <p class="text-sm"><span class="font-medium">Amount:</span> {{ number_format($payment->amount, 3) }} KWD</p>
                                            </div>
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
                        <h2 class="text-xl font-semibold text-gray-900">Visits</h2>
                        <button class="inline-flex items-center gap-2 rounded-lg bg-gray-300 px-4 py-2 text-gray-500 cursor-not-allowed" disabled>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Visit (Coming Soon)
                        </button>
                    </div>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No visits yet</h3>
                        <p class="mt-1 text-sm text-gray-500">This section will display all visits related to this contract in the future.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 