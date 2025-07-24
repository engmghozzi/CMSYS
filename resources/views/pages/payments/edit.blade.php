<x-layouts.app :title="__('Edit Payment Transaction')">
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

        <div class="bg-white rounded-lg shadow-md p-4 border">
            <form action="{{ route('payments.update', [$client->id, $payment->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                    {{-- Contract Dropdown --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contract</label>
                        <select name="contract_id" required class="form-select w-full border rounded px-4 py-2">
                            <option value="">Select Contract</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}"
                                    {{ $payment->contract_id == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_num }} - {{ $contract->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" name="amount" step="0.001" value="{{ old('amount', $payment->amount) }}" required
                               class="form-input w-full border rounded px-4 py-2">
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', $payment->payment_date) }}" required
                               class="form-input w-full border rounded px-4 py-2">
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $payment->due_date) }}"
                               class="form-input w-full border rounded px-4 py-2">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="method" class="form-select w-full border rounded px-4 py-2">
                            @foreach(['Cash', 'KNET', 'Cheque', 'Wamd', 'other'] as $method)
                                <option value="{{ $method }}" {{ $payment->method === $method ? 'selected' : '' }}>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-select w-full border rounded px-4 py-2" required>
                            @foreach(['Unpaid', 'Pending', 'Paid', 'Overdue'] as $status)
                                <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="form-textarea w-full border rounded px-4 py-2">{{ old('notes', $payment->notes) }}</textarea>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end mt-4">
                    <a href="{{ route('clients.show', $client->id) }}"
                       class="inline-flex items-center gap-2 rounded bg-gray-500 px-3 py-2 mx-1 text-white hover:bg-red-500 hover:text-white">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded bg-gray-500 px-3 py-2 mx-1 text-white hover:bg-red-500 hover:text-white">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
