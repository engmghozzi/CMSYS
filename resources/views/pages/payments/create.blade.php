<x-layouts.app :title="__('New Payment Transaction')">
    <x-ui.form-layout
        :title="__('Create Payment')"
        :back-url="route('clients.show', $client)"
        :back-label="__('Cancel')"
    >
        <form action="{{ route('payments.store', ['client' => $client->id]) }}" method="POST" class="space-y-6">
            @csrf

            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Contract') }}</label>
                    <select name="contract_id" required class="form-select w-full border rounded px-4 py-2">
                        <option value="">{{ __('Select Contract') }}</option>
                        @foreach($contracts as $contract)
                            <option value="{{ $contract->id }}">{{ $contract->contract_num }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                    <input type="number" name="amount" step="0.001" required class="form-input w-full border rounded px-4 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Payment Date') }}</label>
                    <x-date-picker name="payment_date" value="{{ old('payment_date', \App\Helpers\DateHelper::formatDate(now())) }}" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Due Date') }}</label>
                    <x-date-picker name="due_date" value="{{ old('due_date', \App\Helpers\DateHelper::formatDate(now()->addDays(10))) }}" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }}</label>
                    <select name="method" class="form-select w-full border rounded px-4 py-2">
                        <option value="Cash">Cash</option>
                        <option value="KNET">KNET</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Wamd">Wamd</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                    <select name="status" class="form-select w-full border rounded px-4 py-2" required>
                        <option value="Unpaid">{{ __('Unpaid') }}</option>
                        <option value="Other">{{ __('Other') }}</option>
                        <option value="Paid">{{ __('Paid') }}</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea name="notes" rows="2" class="form-textarea w-full border rounded px-4 py-2"></textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded bg-gray-100 px-6 py-3 text-gray-700 hover:bg-gray-200 transition-colors">{{ __('Cancel') }}</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 transition-colors">{{ __('Save') }}</button>
            </div>
        </form>
    </x-ui.form-layout>
</x-layouts.app>
