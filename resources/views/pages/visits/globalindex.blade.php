<x-layouts.app :title="__('All Visits')">
    <div class="min-h-screen">
        <!-- Search Section -->
        <div class="bg-white flex flex-row items-center justify-between rounded-lg shadow border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('pages.visits.globalindex') }}" class="w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                    <!-- Search Input -->
                    <div class="flex items-center w-full border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} h-11">
                        <span class="flex items-center px-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="{{ __('Search by contract number') }}" 
                            class="flex-1 px-2 bg-transparent border-0 focus:ring-0 h-11 {{ app()->getLocale() === 'ar' ? 'text-right placeholder:text-right pr-2' : 'text-left placeholder:text-left pl-2' }}"
                            value="{{ request('search') }}"
                        >
                    </div>

                    <!-- Visit Type Dropdown -->
                    <div>
                        <select name="visit_type" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">{{ __('All Visit Types') }}</option>
                            <option value="proactive" {{ request('visit_type') == 'proactive' ? 'selected' : '' }}>{{ __('Proactive') }}</option>
                            <option value="maintenance" {{ request('visit_type') == 'maintenance' ? 'selected' : '' }}>{{ __('Maintenance') }}</option>
                            <option value="repair" {{ request('visit_type') == 'repair' ? 'selected' : '' }}>{{ __('Repair') }}</option>
                            <option value="installation" {{ request('visit_type') == 'installation' ? 'selected' : '' }}>{{ __('Installation') }}</option>
                            <option value="other" {{ request('visit_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>

                    <!-- Status Dropdown -->
                    <div>
                        <select name="status" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                    </div>

                    

                    <!-- Action Buttons -->
                    <div class="flex gap-2 items-center justify-end lg:col-span-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ __('Search') }}
                        </button>
                        <a href="{{ route('pages.visits.globalindex', ['search' => '', 'status' => '', 'visit_type' => '']) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                            </svg>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Visits Table -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4">
            <div class="flex flex-col space-y-3">
                @forelse ($visits as $visit)
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-3">
                            <!-- Top Row -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('pages.visits.show', [$visit->client, $visit->contract, $visit]) }}" class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200">
                                        #{{ $visit->id }}
                                    </a>
                                    <h3 class="font-medium text-gray-900">
                                        <a href="{{ route('pages.visits.show', [$visit->client, $visit->contract, $visit]) }}" class="hover:underline">
                                            {{ $visit->client->name }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium @if($visit->visit_status === 'completed') bg-green-100 text-green-800 @elseif($visit->visit_status === 'scheduled') bg-blue-100 text-blue-800 @elseif($visit->visit_status === 'in_progress') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                        {{ __(ucfirst($visit->visit_status)) }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ __(ucfirst($visit->visit_type)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Middle Row -->
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-sm mb-2">
                                <span class="text-gray-600">{{ __('Contract') }}: <a href="{{ route('contracts.show', [$visit->client, $visit->contract]) }}" class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $visit->contract->contract_num }}</a></span>
                                <span class="text-gray-600">{{ __('Address') }}: {{ $visit->address->area }} - {{ $visit->address->block}} - {{ $visit->address->street }} - {{ $visit->address->building_number }} - {{ $visit->address->floor }} - {{ $visit->address->apartment_number }}</span>
                                <span class="text-gray-600">{{ __('Technician') }}: {{ $visit->technician->name ?? __('Not assigned') }}</span>
                                <span class="text-gray-600">{{ __('Visit Scheduled') }}: {{ $visit->visit_scheduled_date ? \Carbon\Carbon::parse($visit->visit_scheduled_date)->format('d/m/Y') : __('Not scheduled') }}</span>
                                @if($visit->visit_actual_date)
                                    <span class="text-gray-600">{{ __('Actual Visit Date') }}: {{ \Carbon\Carbon::parse($visit->visit_actual_date)->format('d/m/Y') }}</span>
                                @endif
                            </div>

                            <!-- Bottom Row -->
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-gray-600">{{ __('Visit Notes') }}: {{$visit->visit_notes ? $visit->visit_notes : $visit->notes ?? __('No notes') }}</span>
                               
                            </div>

                            <!-- Bottom Row -->
                            <div class="flex flex-row items-center justify-between gap-2 mt-2 pt-2 border-t border-gray-100">
                                <!-- Created by and Updated by -->
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600">{{ __('Created by') }}: {{ $visit->createdBy->name }}</span>
                                    <span class="text-gray-600">{{ __('Updated by') }}: {{ $visit->updatedBy->name }}</span>
                                </div>
                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    @if(auth()->user()->hasPermission('visits.read'))
                                        <a href="{{ route('pages.visits.show', [$visit->client, $visit->contract, $visit]) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('visits.update') && $visit->visit_status !== 'completed')
                                        <a href="{{ route('pages.visits.edit', [$visit->client, $visit->contract, $visit]) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('visits.delete') && $visit->visit_status !== 'completed')
                                        <form method="POST" 
                                            action="{{ route('pages.visits.destroy', [$visit->client, $visit->contract, $visit]) }}"
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
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No visits found') }}</h3>
                        <p class="text-gray-500">{{ __('No visits match your search criteria.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <div class="bg-white rounded-lg shadow border border-gray-200 px-4 py-3">
                {{ $visits->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>