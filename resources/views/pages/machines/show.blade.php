<x-layouts.app :title="__('Machine Details')">
    <div class="min-h-screen">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Machine Details') }}</h1>
                <p class="text-gray-600 mt-1">{{ $client->name }} - {{ $machine->serial_number }} ({{ __('Contract') }} #{{ $machine->contract->contract_num }})</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('contracts.show', [$client->id, $machine->contract_id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back to Contract') }}
                </a>
                @if(auth()->user()->hasPermission('machines.edit'))
                    <a href="{{ route('machines.edit', [$client->id, $machine->id]) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                        </svg>
                        {{ __('Edit Machine') }}
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

        <!-- Machine Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-300">
                        {{ $machine->brand }}
                    </span>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $machine->serial_number }}</h2>
                </div>
            </div>

            <!-- Details -->
            <div class="p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Type') }}:</span>
                        <p class="font-medium text-gray-900">{{ $machine->type }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Capacity') }}:</span>
                        <p class="font-medium text-gray-900">{{ $machine->capacity }} {{ $machine->UOM }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Efficiency') }}:</span>
                        <p class="font-medium text-gray-900">{{ $machine->current_efficiency }}%</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Cost') }}:</span>
                        <p class="font-medium text-gray-900">{{ number_format($machine->cost, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Contract') }}:</span>
                        <p class="font-medium text-gray-900">{{ $machine->contract->contract_num }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Address') }}:</span>
                        <p class="font-medium text-gray-900">
                            {{ __('Area') }} {{ $machine->address->area }} - {{ __('Block') }} {{ $machine->address->block }} - {{ __('Street') }} {{ $machine->address->street }}
                        </p>
                    </div>
                </div>

                @if($machine->assessment)
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Assessment Notes') }}</h3>
                        <p class="text-gray-700">{{ $machine->assessment }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
