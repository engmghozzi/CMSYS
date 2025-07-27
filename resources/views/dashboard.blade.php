<x-layouts.app :title="__('Dashboard')">
    <div class="min-h-screen p-3 md:p-4">
        <!-- Financial KPIs Section -->
        @if(auth()->user()?->hasPermission('reports.financial'))
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Financial Overview') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Total Revenue -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Revenue') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Collection Rate') }}: {{ $collectionRate }}%</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('This Month') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyRevenue, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs {{ $revenueGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} mt-1">
                                {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% {{ __('vs last month') }}
                            </p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Contract Value -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Avg Contract Value') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($averageContractValue, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalContracts }} {{ __('contracts') }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Cash Flow -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Cash Flow') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($currentMonthPayments, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('This month collected') }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Trends Chart -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Revenue Trends (Last 12 Months)') }}</h2>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="overflow-x-auto">
                    <div class="flex space-x-4 min-w-max">
                        @foreach($monthlyRevenueTrends as $trend)
                        @php
                            $maxRevenue = max(array_column($monthlyRevenueTrends, 'revenue'));
                            $height = $maxRevenue > 0 ? max(4, ($trend['revenue'] / $maxRevenue) * 100) : 4;
                        @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-8 bg-blue-100 rounded-t-sm" style="height: {{ $height }}px;"></div>
                            <div class="text-xs text-gray-600 mt-2 text-center">
                                <div>{{ $trend['month'] }}</div>
                                <div class="font-semibold">{{ number_format($trend['revenue'], 0) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Performance & Outstanding -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Payment Performance') }}</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Payment Performance -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Payment Status') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">{{ __('On Time') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-green-600">{{ $paymentPerformance['on_time'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">{{ __('Late') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-yellow-600">{{ $paymentPerformance['late'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">{{ __('Overdue') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-red-600">{{ $paymentPerformance['overdue'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">{{ __('Pending') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-600">{{ $paymentPerformance['pending'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Payments by Age -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Outstanding Payments by Age') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Current') }}</span>
                            <span class="text-sm font-semibold text-green-600">{{ number_format($outstandingPaymentsByAge['current'], 3) }} {{__('KWD')}}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('30 Days') }}</span>
                            <span class="text-sm font-semibold text-yellow-600">{{ number_format($outstandingPaymentsByAge['30_days'], 3) }} {{__('KWD')}}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('60 Days') }}</span>
                            <span class="text-sm font-semibold text-orange-600">{{ number_format($outstandingPaymentsByAge['60_days'], 3) }} {{__('KWD')}}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('90 Days') }}</span>
                            <span class="text-sm font-semibold text-red-600">{{ number_format($outstandingPaymentsByAge['90_days'], 3) }} {{__('KWD')}}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Over 90 Days') }}</span>
                            <span class="text-sm font-semibold text-red-800">{{ number_format($outstandingPaymentsByAge['over_90_days'], 3) }} {{__('KWD')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Revenue Clients -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Top Revenue Clients') }}</h2>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Revenue') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Contracts') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Avg Value') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($topRevenueClients as $client)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->client->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->client->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($client->total_revenue, 3) }} {{__('KWD')}}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $client->client->contracts->count() ?? 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($client->total_revenue / (($client->client->contracts->count() ?: 1)), 3) }} {{__('KWD')}}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">{{ __('No clients found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                        <div class="text-sm text-gray-600">{{ $type->type }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ __('KWD') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Financial Health & Alerts -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Financial Health & Alerts') }}</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Financial Health Score -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Financial Health Score') }}</h3>
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-24 h-24">
                            <svg class="w-24 h-24 transform -rotate-90">
                                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200"/>
                                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" 
                                    class="text-{{ $financialHealthStatus === 'excellent' ? 'green' : ($financialHealthStatus === 'good' ? 'blue' : ($financialHealthStatus === 'fair' ? 'yellow' : 'red')) }}-500"
                                    stroke-dasharray="{{ 2 * pi() * 40 }}" 
                                    stroke-dashoffset="{{ 2 * pi() * 40 * (1 - $financialHealthScore / 100) }}"/>
                            </svg>
                            <div class="absolute">
                                <span class="text-2xl font-bold text-gray-900">{{ $financialHealthScore }}</span>
                                <span class="text-sm text-gray-500">/100</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-lg font-semibold text-{{ $financialHealthStatus === 'excellent' ? 'green' : ($financialHealthStatus === 'good' ? 'blue' : ($financialHealthStatus === 'fair' ? 'yellow' : 'red')) }}-600">
                                {{ ucfirst($financialHealthStatus) }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">{{ __('Financial Health') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Financial Alerts -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Financial Alerts') }}</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($financialAlerts as $alert)
                        <div class="flex items-start space-x-3 p-3 rounded-lg 
                            {{ $alert['severity'] === 'critical' ? 'bg-red-50 border border-red-200' : 
                               ($alert['severity'] === 'high' ? 'bg-orange-50 border border-orange-200' : 'bg-yellow-50 border border-yellow-200') }}">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 rounded-full 
                                    {{ $alert['severity'] === 'critical' ? 'bg-red-500' : 
                                       ($alert['severity'] === 'high' ? 'bg-orange-500' : 'bg-yellow-500') }}"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $alert['title'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $alert['description'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if(is_object($alert['date']) && method_exists($alert['date'], 'format'))
                                        {{ $alert['date']->format('M d, Y') }}
                                    @else
                                        {{ is_string($alert['date']) ? $alert['date'] : 'N/A' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <div class="text-sm text-gray-500">{{ __('No financial alerts') }}</div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Financial Activities -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Recent Activities') }}</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($recentFinancialActivities as $activity)
                        <div class="flex items-start space-x-3 p-3 rounded-lg bg-green-50 border border-green-200">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if(is_object($activity['date']) && method_exists($activity['date'], 'format'))
                                        {{ $activity['date']->format('M d, Y H:i') }}
                                    @else
                                        {{ is_string($activity['date']) ? $activity['date'] : 'N/A' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <div class="text-sm text-gray-500">{{ __('No recent activities') }}</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                        <div class="text-center p-2 rounded-md bg-gray-50">
                            <span class="text-xs text-gray-600 block mb-1">{{ __('Draft') }}</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $contractStatusDraft }}</span>
                        </div>
                        <div class="text-center p-2 rounded-md bg-blue-50">
                            <span class="text-xs text-blue-600 block mb-1">{{ __('Signed') }}</span>
                            <span class="text-sm font-semibold text-blue-700">{{ $contractStatusSigned }}</span>
                        </div>
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

        <!-- Payments Statistics -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Payments Statistics') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Paid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Paid') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($paid, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Unpaid Breakdown -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Unpaid') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($unpaid + $pending + $overdue, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('Unpaid') }}</span>
                            <span class="text-sm font-semibold text-red-600">{{ number_format($unpaid, 3) }} {{__('KWD')}}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('Pending') }}</span>
                            <span class="text-sm font-semibold text-yellow-600">{{ number_format($pending, 3) }} {{__('KWD')}}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('Overdue') }}</span>
                            <span class="text-sm font-semibold text-orange-600">{{ number_format($overdue, 3) }} {{__('KWD')}}</span>
                        </div>
                    </div>
                </div>

                <!-- Expected Next Month -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Expected Next Month') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($nextMonthExpectedPayments, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
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

        <!-- Machines Statistics -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Machines Statistics') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Total Machines -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Total Machines') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalMachines }}</p>
                        </div>
                    </div>
                </div>

                <!-- Machines by Brand -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Machines by Brand') }}</h3>
                    <div class="space-y-2">
                        @foreach($machinesByBrand as $brand => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $brand }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Machines by Type -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">{{ __('Machines by Type') }}</h3>
                    <div class="space-y-2">
                        @foreach($machinesByType as $type => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $type }}</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
