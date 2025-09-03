<x-layouts.app :title="__('Payment Details')">
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Payment Details') }}</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">{{ $client->name }} - {{ __('Contract') }} #{{ $payment->contract->contract_num }}</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                @if(auth()->user()->hasPermission('payments.update'))
                    <a href="{{ route('payments.edit', [$client->id, $payment->id]) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                        </svg>
                        {{ __('Edit Payment') }}
                    </a>
                @endif
                
                <a href="{{ route('contracts.show', [$client, $payment->contract]) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back to Contract') }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Payment Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                        @if($payment->status === 'Paid') bg-green-100 text-green-700 border border-green-300
                        @elseif($payment->status === 'Unpaid') bg-red-100 text-red-700 border border-red-300
                        @elseif($payment->status === 'Other') bg-yellow-100 text-yellow-700 border border-yellow-300
                        @else bg-gray-100 text-gray-700 border border-gray-300
                        @endif">
                        {{ __($payment->status) }}
                    </span>
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('Payment Status') }} #{{ $payment->payment_num }}</h2>
                </div>
            </div>

            <!-- Details -->
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <span class="text-sm text-gray-500">{{ __('Amount') }}</span>
                        <p class="text-lg font-medium text-gray-900">{{ number_format($payment->amount, 3) }} {{ __('KWD') }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm text-gray-500">{{ __('Payment Date') }}</span>
                        <p class="text-lg font-medium text-gray-900">{{ \App\Helpers\DateHelper::formatDate($payment->payment_date) }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm text-gray-500">{{ __('Payment Method') }}</span>
                        <p class="text-lg font-medium text-gray-900">{{ $payment->method }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm text-gray-500">{{ __('Due Date') }}</span>
                        <p class="text-lg font-medium text-gray-900">{{ \App\Helpers\DateHelper::formatDate($payment->due_date) }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm text-gray-500">{{ __('Contract') }}</span>
                        <p class="text-lg font-medium text-gray-900">{{ $payment->contract->contract_num }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-sm text-gray-500">{{ __('Created By') }}</span>
                        <p class="text-lg font-medium text-gray-900">{{ $payment->creator->name }}</p>
                    </div>
                </div>

                @if($payment->notes)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Payment Notes') }}</h3>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $payment->notes }}</p>
                    </div>
                @endif

                @if($payment->attachment_url)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Payment Receipt') }}</h3>
                        <a href="{{ asset('storage/' . $payment->attachment_url) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-blue-600 hover:bg-gray-50 hover:text-blue-800 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('View Receipt') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
