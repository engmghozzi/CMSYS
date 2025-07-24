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

        {{-- Form --}}
        <div class="bg-white rounded-lg shadow-md p-4 border">
            <form action="{{ route('payments.store', ['client' => $client->id]) }}" method="POST">
                @csrf

                <input type="hidden" name="client_id" value="{{ $client->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                    {{-- Contract Dropdown --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contract</label>
                        <select name="contract_id" required
                                class="form-select w-full border rounded px-4 py-2">
                            <option value="">Select Contract</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}">
                                    {{ $contract->contract_num }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" name="amount" step="0.001" required
                               class="form-input w-full border rounded px-4 py-2">
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ now()->toDateString() }}" required
                               class="form-input w-full border rounded px-4 py-2">
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" value="{{ now()->addDays(10)->toDateString() }}"
                               class="form-input w-full border rounded px-4 py-2">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="method" class="form-select w-full border rounded px-4 py-2">
                            <option value="Cash">Cash</option>
                            <option value="KNET">KNET</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Wamd">Wamd</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-select w-full border rounded px-4 py-2" required>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Overdue">Overdue</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="2" class="form-textarea w-full border rounded px-4 py-2"></textarea>
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
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
