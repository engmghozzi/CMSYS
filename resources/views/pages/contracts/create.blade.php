<x-layouts.app :title="'Add Contract for ' . $client->name">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-md">

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contracts.store', $client->id) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contract Type</label>
                    <select name="type" required class="w-full border rounded px-4 py-2">
                        <option value="">Select Type</option>
                        @foreach (['L', 'LS', 'C', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <select name="address_id" required class="w-full border rounded px-4 py-2">
                        <option value="">Select Address</option>
                        @foreach ($client->addresses as $address)
                            <option value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'selected' : '' }}>
                                {{ $address->area }} - {{ $address->block }} - {{ $address->street }} -{{ $address->house_num }} - {{ $address->floor_num }} - {{ $address->flat_num }} - {{ $address->paci_num }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Central Machines</label>
                    <input type="number" name="centeral_machines" value="{{ old('centeral_machines', 0) }}" required
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Machines</label>
                    <input type="number" name="unit_machines" value="{{ old('unit_machines', 0) }}" required
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration (Months)</label>
                    <input type="number" step="0.01" name="duration_months" value="{{ old('duration_months') }}" required
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                    <input type="number" step="0.001" name="total_amount" value="{{ old('total_amount') }}" required
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Amount</label>
                    <input type="number" step="0.001" name="commission_amount" value="{{ old('commission_amount') }}"
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Type</label>
                    <select name="commission_type" class="w-full border rounded px-4 py-2">
                        <option value="">None</option>
                        @foreach (['Incentive Bonus', 'Referral Commission', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('commission_type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Recipient</label>
                    <input type="text" name="commission_recipient" value="{{ old('commission_recipient') }}"
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Date</label>
                    <input type="date" name="commission_date" value="{{ old('commission_date') }}"
                           class="w-full border rounded px-4 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" required class="w-full border rounded px-4 py-2">
                        @foreach (['draft', 'signed', 'active', 'expired', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Single Column Fields -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Details</label>
                <textarea name="details" rows="4"
                          class="w-full border rounded px-4 py-2">{{ old('details') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Attachment (Upload)</label>
                <input type="file" name="attachment_url"
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('clients.show', $client->id) }}"
                   class="inline-flex items-center gap-2 rounded bg-gray-500 px-3 py-2 text-white hover:bg-red-500 hover:text-white">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded bg-gray-500 px-3 py-2 text-white hover:bg-red-500 hover:text-white">
                    Save
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
