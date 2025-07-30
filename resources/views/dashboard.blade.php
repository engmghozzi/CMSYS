<x-layouts.app :title="__('Dashboard2')">
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

                <!-- Total Paid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Paid') }}</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($currentMonthPayments, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Collected payments') }}</p>
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
                            <p class="text-2xl font-bold text-red-600">{{ number_format($totalRevenue - $currentMonthPayments, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Outstanding balance') }}</p>
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

        <!-- Revenue Trends Chart -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Revenue Trends (Last 12 Months)') }}</h2>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="w-full">
                    <div class="grid grid-cols-12 gap-2 items-end">
                        @foreach($monthlyRevenueTrends as $trend)
                        @php
                            $maxRevenue = max(array_column($monthlyRevenueTrends, 'revenue'));
                            $height = $maxRevenue > 0 ? max(20, ($trend['revenue'] / $maxRevenue) * 150) : 20;
                        @endphp
                        <div class="flex flex-col items-center group">
                            <div class="relative w-full">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-10">
                                    {{ number_format($trend['revenue'], 3) }} {{__('KWD')}}
                                </div>
                                <div class="w-full bg-gradient-to-t from-blue-100 to-blue-500 rounded-t-md hover:from-blue-200 hover:to-blue-600 transition-colors duration-200" style="height: {{ $height }}px;"></div>
                            </div>
                            <div class="text-sm text-gray-600 mt-3 text-center w-full">
                                <div class="font-medium truncate">{{ __($trend['month']) }}</div>
                                <div class="font-bold text-blue-600 truncate">{{ number_format($trend['revenue'], 0) }}</div>
                            </div>
                        </div>
                        @endforeach
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

        <!-- Financial Health & Alerts -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Financial Health & Alerts') }}</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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
                                <p class="text-sm font-medium text-gray-900">{{ __($alert['title']) }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ __($alert['description']) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if(is_object($alert['date']) && method_exists($alert['date'], 'format'))
                                        @php
                                            $month = __($alert['date']->format('M'));
                                            $day = $alert['date']->format('d');
                                            $year = $alert['date']->format('Y');
                                        @endphp
                                        {{ $month }} {{ $day }}, {{ $year }}
                                    @else
                                        {{ is_string($alert['date']) ? __($alert['date']) : __('N/A') }}
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
                                <p class="text-sm font-medium text-gray-900">{{ __($activity['title']) }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ __($activity['description']) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if(is_object($activity['date']) && method_exists($activity['date'], 'format'))
                                        @php
                                            $month = __($activity['date']->format('M'));
                                            $day = $activity['date']->format('d');
                                            $year = $activity['date']->format('Y');
                                            $time = $activity['date']->format('H:i');
                                        @endphp
                                        {{ $month }} {{ $day }}, {{ $year }} {{ $time }}
                                    @else
                                        {{ is_string($activity['date']) ? __($activity['date']) : __('N/A') }}
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
