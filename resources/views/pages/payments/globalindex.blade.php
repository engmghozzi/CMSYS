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
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4">
            <div class="flex flex-col space-y-3">
                @forelse ($payments as $payment)
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left Side - Main Info -->
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shrink-0">
                                    #{{ $payment->id }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-medium text-gray-900 truncate">
                                        <a href="{{ route('clients.show', $payment->client->id) }}" class="hover:underline">
                                            {{ $payment->client->name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <a href="{{ route('contracts.show', [$payment->client->id, $payment->contract->id]) }}" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                                            {{ $payment->contract->contract_num }}
                                        </a>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium @if ($payment->status === 'Paid') bg-green-100 text-green-800 @elseif ($payment->status === 'Unpaid') bg-red-100 text-red-800 @elseif ($payment->status === 'Pending') bg-yellow-100 text-yellow-800 @elseif ($payment->status === 'Overdue') bg-orange-100 text-orange-800 @else bg-gray-100 text-gray-800 @endif">
                                            {{ __($payment->status) }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ __($payment->method) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Middle - Amount & Notes -->
                            <div class="flex flex-col md:flex-row md:items-center gap-2 flex-grow px-4">
                                <span class="font-semibold text-green-600">{{ number_format($payment->amount) }} {{__('KWD')}}</span>
                                @if($payment->notes)
                                    <div class="max-w-xs truncate text-sm text-gray-600" title="{{ $payment->notes }}">
                                        {{ $payment->notes }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </div>
                            <!-- Right Side - Contact -->
                            <div class="flex flex-col text-xs text-right text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg> &nbsp;
                                    <span class="text-blue-600">
                                        {{ $payment->client->mobile_number }}
                                    </span>
                                </div>
                                @if($payment->client->alternate_mobile_number)
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg> &nbsp; 
                                        <span class="text-blue-600">
                                            {{ $payment->client->alternate_mobile_number }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No payments found') }}</h3>
                        <p class="text-gray-500">{{ __('No payments match your search criteria.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <div class="bg-white rounded-lg shadow border border-gray-200 px-4 py-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
