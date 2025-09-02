<x-layouts.app :title="__('Dashboard')">
    <div class="min-h-screen p-3 md:p-4">
        <!-- Financial & Payments Overview -->
        @if(auth()->user()?->hasPermission('reports.financial'))
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Financial & Payments Overview') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Total Revenue -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Revenue') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalContractsAmount, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Sum of all contracts amount') }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Paid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Paid') }}</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($paid, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Sum of all paid amounts') }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Unpaid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Unpaid') }}</p>
                            <p class="text-2xl font-bold text-red-600">{{ number_format($totalContractsAmount - $paid, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Sum of all remaining amounts') }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Expected Next Month -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Expected Next Month') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($expectedNextMonth, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Sum of all awaiting amounts for next month') }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Contract Type -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Revenue by Contract Type') }}</h2>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($revenueByContractType as $type)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($type->total_revenue, 3) }}</div>
                        <div class="text-sm text-gray-600">{{__($type->type) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ __('KWD') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Clients Statistics -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Clients Statistics') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Total Clients -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Clients') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalClients }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Client Types -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Client Types') }}</h3>
                    <div class="space-y-2">
                        @foreach($clientTypes as $type => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __($type) }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Client Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Client Status') }}</h3>
                    <div class="space-y-2">
                        @foreach($clientStatus as $status => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __($status) }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- Contracts Statistics -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Contracts Statistics') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Total Contracts -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Contracts') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalContracts }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Contract Types -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Contract Types') }}</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('L') }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $contractTypeL }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('LS') }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $contractTypeLS }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('C') }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $contractTypeC }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Other') }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $contractTypeOther }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contract Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 sm:col-span-2">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Contract Status') }}</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center p-2 rounded-md bg-green-50">
                            <span class="text-xs text-green-600 block mb-1">{{ __('Active') }}</span>
                            <span class="text-sm font-semibold text-green-700">{{ $contractStatusActive }}</span>
                        </div>
                        <div class="text-center p-2 rounded-md bg-orange-50">
                            <span class="text-xs text-orange-600 block mb-1">{{ __('Expired') }}</span>
                            <span class="text-sm font-semibold text-orange-700">{{ $contractStatusExpired }}</span>
                        </div>
                        <div class="text-center p-2 rounded-md bg-red-50">
                            <span class="text-xs text-red-600 block mb-1">{{ __('Cancelled') }}</span>
                            <span class="text-sm font-semibold text-red-700">{{ $contractStatusCancelled }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Payments Statistics -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Payments Statistics') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Total Payment Transactions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Total Payment Transactions') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalPaymentTransactions }}</p>
                        </div>
                    </div>
                </div>

                <!-- Paid vs Unpaid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Paid Transactions') }}</p>
                            <p class="text-2xl font-bold text-green-600">{{ $paidTransactions }}</p>
                            <p class="text-xs text-gray-500">{{ $totalPaymentTransactions > 0 ? round(($paidTransactions / $totalPaymentTransactions) * 100, 1) : 0 }}% of total</p>
                        </div>
                    </div>
                </div>

                <!-- Unpaid Transactions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Unpaid Transactions') }}</p>
                            <p class="text-2xl font-bold text-red-600">{{ $unpaidTransactions }}</p>
                            <p class="text-xs text-gray-500">{{ $totalPaymentTransactions > 0 ? round(($unpaidTransactions / $totalPaymentTransactions) * 100, 1) : 0 }}% of total</p>
                        </div>
                    </div>
                </div>

                <!-- Largest Payment -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Largest Payment') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($largestPayment, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Smallest Payment -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Smallest Payment') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($smallestPayment, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Average Payment Amount -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Average Payment Amount') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($averagePaymentAmount, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                        </div>
                    </div>
                </div>

                
                <!-- Payments by Method -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 sm:col-span-2 lg:col-span-3">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Payments by Method') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        @foreach($paymentsByMethod as $method)
                        <div class="text-center p-3 rounded-lg {{ strtolower($method->method) == 'cash' ? 'bg-green-50' : (in_array(strtolower($method->method), ['knet', 'wamd']) ? 'bg-blue-50' : 'bg-yellow-50') }}">
                            <div class="flex justify-center mb-2">
                                @if(strtolower($method->method) == 'cash')
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                @elseif(in_array(strtolower($method->method), ['knet', 'wamd']))
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs font-medium {{ strtolower($method->method) == 'cash' ? 'text-green-600' : (in_array(strtolower($method->method), ['knet', 'wamd']) ? 'text-blue-600' : 'text-yellow-600') }} mb-1">{{ $method->method }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $method->count }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($method->total_amount, 3) }} KWD</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </div>
</x-layouts.app>
