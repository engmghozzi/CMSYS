<x-layouts.app :title="__('Visit Details')">
    <div class="min-h-screen">
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="border-b border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Visit Details') }}</h1>
                        <p class="text-gray-600 mt-1">{{__('Client')}} : {{ $client->name }} - {{ __('Contract') }} #{{ $visit->contract->contract_num }}</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('contracts.show', [$client->id, $visit->contract_id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            {{ __('Back to Contract') }}
                        </a>
                        @if(auth()->user()->hasPermission('visits.update'))
                            <a href="{{ route('pages.visits.edit', [$client, $contract, $visit]) }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                </svg>
                                {{ __('Edit Visit') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-6 rounded-lg bg-green-50 border border-green-200 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Card Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Visit Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Status Card -->
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                                        @if($visit->visit_status === 'completed') bg-green-100 text-green-800 border border-green-300
                                        @elseif($visit->visit_status === 'scheduled') bg-blue-100 text-blue-800 border border-blue-300
                                        @else bg-gray-100 text-gray-800 border border-gray-300 @endif">
                                        {{__('Status')}} : {{ __($visit->visit_status) }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-300">
                                    {{__('Visit Type')}} : {{ __($visit->visit_type) }}
                                </span>
                            </div>
                        </div>

                        <!-- Dates & Times -->
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Visit Dates') }}</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Scheduled Date') }}</label>
                                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($visit->visit_scheduled_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Actual Date') }}</label>
                                    <p class="font-medium text-gray-900">{{ $visit->visit_actual_date ? \Carbon\Carbon::parse($visit->visit_actual_date)->format('M d, Y') : __('Not completed') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Details -->
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Address Details') }}</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm text-gray-500">{{ __('Full Address') }}</label>
                                    <p class="font-medium text-gray-900">
                                        {{__('Area')}} : {{ $visit->contract->address->area }} - 
                                        {{ __('Block') }} : {{ $visit->contract->address->block }} - 
                                        {{ __('Street') }} : {{ $visit->contract->address->street }} - 
                                        {{ __('House') }} : {{ $visit->contract->address->house_num }} - 
                                        {{ __('Floor') }} : {{ $visit->contract->address->floor }} - 
                                        {{ __('Flat') }} : {{ $visit->contract->address->flat }} - 
                                        {{ __('Paci Number') }} : {{ $visit->contract->address->paci_num }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Side Info -->
                    <div class="space-y-6">
                        <!-- Technician Info -->
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Technician') }}</h2>
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $visit->technician->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $visit->technician->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($visit->visit_notes)
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Visit Notes') }}</h2>
                                <p class="text-gray-700 whitespace-pre-line">{{ $visit->visit_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
