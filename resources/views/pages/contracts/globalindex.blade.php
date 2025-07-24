<x-layouts.app :title="__('Contracts')">
    <div class="min-h-screen">
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('contracts.globalindex')}}" class="space-y-4" onsubmit="console.log('Form submitted');">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end justify-start">
                    <div class="md:col-span-1">
                        <div class="flex items-center w-full border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} h-11">
                            <span class="flex items-center px-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="{{ __('Contract Number') }}" 
                                class="flex-1 bg-transparent border-0 focus:ring-0 h-11 {{ app()->getLocale() === 'ar' ? 'text-right placeholder:text-right pr-2' : 'text-left placeholder:text-left pl-2' }}"
                                value="{{ request('search') }}"
                                onchange="console.log('Search input changed:', this.value);"
                            >
                        </div>
                    </div>
                    
                    <div class="md:col-span-1">
                        <div class="flex items-center w-full border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} h-11">
                            <span class="flex items-center px-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </span>
                            <input 
                                type="text" 
                                name="mobile" 
                                placeholder="{{ __('Mobile Number') }}" 
                                class="flex-1 bg-transparent border-0 focus:ring-0 h-11 {{ app()->getLocale() === 'ar' ? 'text-right placeholder:text-right pr-2' : 'text-left placeholder:text-left pl-2' }}"
                                value="{{ request('mobile') }}"
                            >
                        </div>
                    </div>
                    
                    <div class="md:col-span-1">
                        <select name="status" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                            <option value="signed" {{ request('status') === 'signed' ? 'selected' : '' }}>{{ __('Signed') }}</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-1">
                        <select name="type" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>{{ __('All Types') }}</option>
                            <option value="L" {{ request('type') === 'L' ? 'selected' : '' }}>{{ __('L') }}</option>
                            <option value="LS" {{ request('type') === 'LS' ? 'selected' : '' }}>{{ __('LS') }}</option>
                            <option value="C" {{ request('type') === 'C' ? 'selected' : '' }}>{{ __('C') }}</option>
                            <option value="Other" {{ request('type') === 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <select name="expiring_months" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="" {{ request('expiring_months') == '' ? 'selected' : '' }}>{{ __('No Expiry Filter') }}</option>
                            <option value="1" {{ request('expiring_months') === '1' ? 'selected' : '' }}>{{ __('One Month') }}</option>
                            <option value="2" {{ request('expiring_months') === '2' ? 'selected' : '' }}>{{ __('Two Months') }}</option>
                            <option value="3" {{ request('expiring_months') === '3' ? 'selected' : '' }}>{{ __('Three Months') }}</option>
                        </select>
                    </div>
                    <div class="relative md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date  ≥') }}</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                            value="{{ request('start_date') }}"
                        >
                    </div>
                    <div class="relative md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date  ≤') }}</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                            value="{{ request('end_date') }}"
                        >
                    </div>

                    {{-- buttons --}}
                    <div class="flex gap-2 items-center justify-start">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ __('Search') }}
                        </button>
                        <a href="{{ route('contracts.globalindex') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                            </svg>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Contracts Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Contract No.') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Client Name') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Mobile') }}</th>                     
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Start Date') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('End Date') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Amount (KWD)') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Paid (KWD)') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Remaining (KWD)') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Commission (KWD)') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($contracts as $contract)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('contracts.show', [$contract->client->id, $contract->id]) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                    {{ $contract->contract_num }}
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($contract->status === 'active')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ __(ucfirst($contract->status)) }}
                                    </span>
                                @elseif($contract->status === 'signed')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ __(ucfirst($contract->status)) }}
                                    </span>
                                @elseif($contract->status === 'cancelled')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                        {{ __(ucfirst($contract->status)) }}
                                    </span>
                                @elseif($contract->status === 'expired')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ __(ucfirst($contract->status)) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ __(ucfirst($contract->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('clients.show', $contract->client->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $contract->client->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <div class="space-y-1">
                                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                        <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="{{ route('clients.show', $contract->client->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $contract->client->mobile_number }}
                                        </a>
                                    </div>
                                    @if($contract->client->alternate_mobile_number)
                                        <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a href="{{ route('clients.show', $contract->client->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $contract->client->alternate_mobile_number }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>     
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ __($contract->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $contract->start_date }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $contract->end_date }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <span class="text-green-600 font-semibold">{{ number_format($contract->total_amount) }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <span class="text-green-600 font-semibold">{{ number_format($contract->paid_amount) }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                @if($contract->remaining_amount > 0)
                                    <span class="text-red-600 font-semibold">{{ number_format($contract->remaining_amount) }}</span>
                                @else
                                    <span class="text-green-600 font-semibold">0</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <span class="text-yellow-600 font-semibold">{{ number_format($contract->commission_amount) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-8 text-center align-middle">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No contracts found') }}</h3>
                                    <p class="text-gray-500">{{ __('No contracts match your search criteria.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <div class="bg-white rounded-lg shadow border border-gray-200 px-4 py-3">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
