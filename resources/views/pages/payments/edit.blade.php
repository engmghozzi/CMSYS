<x-layouts.app :title="__('Edit Payment Transaction')">
    <div class="container mx-auto px-4 py-6 max-w-7xl">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg shadow-sm">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <form action="{{ route('payments.update', [$client->id, $payment->id]) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Fixed Contract Info Display --}}
                <div class="mb-8 p-4 bg-gray-50 rounded-lg border-1 border-gray-200 border-radius-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Contract Information') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-sm font-medium text-gray-600">{{ __('Contract No.') }}</span>
                            <span class="text-gray-900">{{ $payment->contract->contract_num }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-600">{{ __('Contract Type') }}</span>
                            <span class="text-gray-900">{{ $payment->contract->type }}</span>
                        </div>
                    </div>
                    <input type="hidden" name="contract_id" value="{{ $payment->contract_id }}">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Amount --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Amount (KWD)') }}</label>
                        <input type="number" 
                               name="amount" 
                               step="0.001" 
                               value="{{ old('amount', $payment->amount) }}" 
                               required
                               class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2">
                    </div>

                    {{-- Due Date --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Due Date') }}</label>
                        <input type="date" 
                               name="due_date" 
                               value="{{ old('due_date', $payment->due_date) }}"
                               class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2">
                    </div>

                    {{-- Payment Date --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Payment Date') }}</label>
                        <input type="date" 
                               name="payment_date" 
                               value="{{ old('payment_date', $payment->payment_date) }}" 
                               required
                               class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2">
                    </div>

                    

                    {{-- Payment Method --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }}</label>
                        <select name="method" 
                                class="form-select w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2">
                            @foreach(['Cash', 'KNET', 'Cheque', 'Wamd', 'other'] as $method)
                                <option value="{{ $method }}" {{ $payment->method === $method ? 'selected' : '' }}>
                                    {{ __($method) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                        <select name="status" 
                                class="form-select w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2" 
                                required>
                            @foreach(['Unpaid', 'Paid'] as $status)
                                <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>
                                    {{ __($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mt-6 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Payment Notes') }}</label>
                    <textarea name="notes" 
                              rows="3"
                              class="form-textarea w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2"
                              placeholder="{{ __('Enter any additional notes about this payment') }}"
                    >{{ old('notes', $payment->notes) }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end items-center gap-4 mt-8 pt-4 border-t">
                    <a href="{{ route('contracts.show', [$client->id, $payment->contract_id]) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Cancel') }}
                    </a>

                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
