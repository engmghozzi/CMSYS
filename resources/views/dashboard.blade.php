<x-layouts.app :title="__('Dashboard')">
    <div class="min-h-screen p-3 md:p-4">
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
                <!-- Total Revenue -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Revenue') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 3) }} <span class="text-xs">{{__('KWD')}}</span></p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </div>

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
