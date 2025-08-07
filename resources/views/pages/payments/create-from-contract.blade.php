<x-layouts.app :title="__('New Payment Transaction')">
    <div class="container mx-auto px-4 py-2 max-w">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Contract Info --}}
        <div class="bg-blue-50 rounded-lg shadow-md p-4 border border-blue-200 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-blue-900 mb-2">{{ __('Payment for Contract') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Contract Number:') }}</span>
                            <span class="text-blue-900">{{ $contract->contract_num }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Client:') }}</span>
                            <span class="text-blue-900">{{ $client->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Total Amount:') }}</span>
                            <span class="text-blue-900">{{ number_format($contract->total_amount) }} KWD</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Paid Amount:') }}</span>
                            <span class="text-blue-900">{{ number_format($contract->paid_amount) }} KWD</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Remaining Amount:') }}</span>
                            <span class="text-red-600 font-semibold">{{ number_format($contract->remaining_amount) }} KWD</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Status:') }}</span>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                        @if($contract->status === 'active') bg-green-100 text-green-800
                        @elseif($contract->status === 'cancelled') bg-red-100 text-red-800
                        @elseif($contract->status === 'expired') bg-orange-100 text-orange-800
                        @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($contract->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('contracts.show', [$client->id, $contract->id]) }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back to Contract') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-lg shadow-md p-6 border">
            <form action="{{ route('payments.store.from.contract', [$client->id, $contract->id]) }}" method="POST">
                @csrf

                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Amount (KWD)') }}</label>
                        <input type="number" name="amount" step="0.001" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter payment amount"
                               max="{{ $contract->remaining_amount }}"
                               value="{{ old('amount') }}">
                        <p class="mt-1 text-sm text-gray-500">{{ __('Maximum:') }} {{ number_format($contract->remaining_amount) }} KWD</p>
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Payment Date') }}</label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Due Date') }}</label>
                        <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(10)->toDateString()) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Payment Method') }}</label>
                        <select name="method" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="Cash" {{ old('method') == 'Cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                            <option value="KNET" {{ old('method') == 'KNET' ? 'selected' : '' }}>{{ __('KNET') }}</option>
                            <option value="Cheque" {{ old('method') == 'Cheque' ? 'selected' : '' }}>{{ __('Cheque') }}</option>
                            <option value="Wamd" {{ old('method') == 'Wamd' ? 'selected' : '' }}>{{ __('Wamd') }}</option>
                            <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <option value="Unpaid" {{ old('status') == 'Unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                            <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Notes') }}</label>
                        <textarea name="notes" rows="3" 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter any additional notes about this payment">{{ old('notes') }}</textarea>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('contracts.show', [$client->id, $contract->id]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ __('Cancel') }}
                    </a>

                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('Create Payment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app> 