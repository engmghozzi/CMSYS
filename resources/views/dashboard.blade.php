<x-layouts.app :title="__('Dashboard')">
    <div class="min-h-screen">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Contracts -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Total Contracts') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalContracts }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <span class="font-medium">{{ $activeContracts }}</span> {{ __('active') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Total Revenue') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRevenue, 3) }} KWD</p>
                        <p class="text-sm text-green-600 mt-1">
                            <span class="font-medium">{{ number_format($collectionRate, 1) }}%</span> {{ __('collected') }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Outstanding Amount -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Outstanding') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($outstandingAmount, 3) }} KWD</p>
                        <p class="text-sm text-orange-600 mt-1">
                            <span class="font-medium">{{ $overduePayments }}</span> {{ __('overdue payments') }}
                        </p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Clients -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Total Clients') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalClients) }}</p>
                        <p class="text-sm text-purple-600 mt-1">
                            <span class="font-medium">{{ $newClientsThisMonth }}</span> {{ __('new this month') }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Machine Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Machines -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Total Machines') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalMachines }}</p>
                        <p class="text-sm text-indigo-600 mt-1">
                            <span class="font-medium">{{ $activeMachines }}</span> {{ __('active') }}
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Machine Types -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Machine Types') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $machineTypesCount }}</p>
                        <p class="text-sm text-teal-600 mt-1">
                            <span class="font-medium">{{ $mostCommonMachineType }}</span> {{ __('most common') }}
                        </p>
                    </div>
                    <div class="p-3 bg-teal-100 rounded-lg">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Machine Brands -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Machine Brands') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $machineBrandsCount }}</p>
                        <p class="text-sm text-cyan-600 mt-1">
                            <span class="font-medium">{{ $topMachineBrand }}</span> {{ __('top brand') }}
                        </p>
                    </div>
                    <div class="p-3 bg-cyan-100 rounded-lg">
                        <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Machine Efficiency -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Avg Efficiency') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $averageEfficiency }}%</p>
                        <p class="text-sm text-emerald-600 mt-1">
                            <span class="font-medium">{{ $highEfficiencyMachines }}</span> {{ __('high efficiency') }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-lg">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Revenue Trend (Last 6 Months)') }}</h3>
                <div class="h-64 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($totalRevenue, 3) }} KWD</div>
                        <div class="text-sm text-gray-600">{{ __('Total Revenue') }}</div>
                    </div>
                </div>
            </div>

            <!-- Contract Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Contract Status Distribution') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ __('Active Contracts') }}</span>
                        <span class="text-sm font-semibold text-green-600">{{ $activeContracts }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ __('Completed Contracts') }}</span>
                        <span class="text-sm font-semibold text-blue-600">{{ $completedContracts }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ __('Pending Contracts') }}</span>
                        <span class="text-sm font-semibold text-yellow-600">{{ $pendingContracts }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Contracts -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Recent Contracts') }}</h3>
                <div class="space-y-3">
                    @forelse($recentContracts as $contract)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $contract->contract_number }}</p>
                            <p class="text-sm text-gray-600">{{ $contract->client->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">{{ number_format($contract->total_amount, 3) }} KWD</p>
                            <p class="text-xs text-gray-500">{{ $contract->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">{{ __('No recent contracts') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Recent Payments') }}</h3>
                <div class="space-y-3">
                    @forelse($recentPayments as $payment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $payment->payment_number }}</p>
                            <p class="text-sm text-gray-600">{{ $payment->contract->client->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">{{ number_format($payment->amount, 3) }} KWD</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                    ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">{{ __('No recent payments') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
