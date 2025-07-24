<x-layouts.app :title="__('Payments')">
    <div class="min-h-screen">
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('payments.globalindex')}}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
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
                            <option value="" {{ request('status', '') == '' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                            <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                            <option value="Unpaid" {{ request('status') === 'Unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="Overdue" {{ request('status') === 'Overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <select name="method" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">{{ __('All Methods') }}</option>
                            <option value="Cash" {{ request('method') === 'Cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                            <option value="KNET" {{ request('method') === 'KNET' ? 'selected' : '' }}>{{ __('KNET') }}</option>
                            <option value="Cheque" {{ request('method') === 'Cheque' ? 'selected' : '' }}>{{ __('Cheque') }}</option>
                            <option value="Wamd" {{ request('method') === 'Wamd' ? 'selected' : '' }}>{{ __('Wamd') }}</option>
                            <option value="other" {{ request('method') === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>
                    <div class="flex gap-2 items-end md:col-span-1">
                        @if(app()->getLocale() === 'ar')
                            <a href="{{ route('payments.globalindex') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                                </svg>
                                {{ __('Clear') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                {{ __('Search') }}
                            </button>
                        @else
                            <a href="{{ route('payments.globalindex') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                                </svg>
                                {{ __('Clear') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                {{ __('Search') }}
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Payments Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Payment No.') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Contract No.') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Client Name') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Mobile') }}</th>                     
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Amount') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Method') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    #{{ $payment->id }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                    {{ $payment->contract->contract_num }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('clients.show', $payment->client->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $payment->client->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <div class="space-y-1">
                                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                        <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="{{ route('clients.show', $payment->client->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $payment->client->mobile_number }}
                                        </a>
                                    </div>
                                    @if($payment->client->alternate_mobile_number)
                                        <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                            <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a href="{{ route('clients.show', $payment->client->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $payment->client->alternate_mobile_number }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>     
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="font-semibold text-green-600">{{ number_format($payment->amount) }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($payment->status === 'Paid')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ __($payment->status) }}
                                    </span>
                                @elseif ($payment->status === 'Unpaid')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                        {{ __($payment->status) }}
                                    </span>
                                @elseif ($payment->status === 'Pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ __($payment->status) }}
                                    </span>
                                @elseif ($payment->status === 'Overdue')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ __($payment->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ __($payment->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ __($payment->method) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                @if($payment->notes)
                                    <div class="max-w-xs truncate" title="{{ $payment->notes }}">
                                        {{ $payment->notes }}
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center align-middle">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No payments found') }}</h3>
                                    <p class="text-gray-500">{{ __('No payments match your search criteria.') }}</p>
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
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
