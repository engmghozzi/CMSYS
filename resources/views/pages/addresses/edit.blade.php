<x-layouts.app :title="'Edit Address for ' . $client->name">

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

        <form method="POST" action="{{ route('addresses.update', ['client' => $client->id, 'address' => $address->id]) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Area</label>
                <input type="text" name="area" value="{{ old('area', $address->area) }}" required
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Block</label>
                <input type="text" name="block" value="{{ old('block', $address->block) }}" required
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Street</label>
                <input type="text" name="street" value="{{ old('street', $address->street) }}" required
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">House Number</label>
                <input type="text" name="house_num" value="{{ old('house_num', $address->house_num) }}"
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Floor Number</label>
                <input type="text" name="floor_num" value="{{ old('floor_num', $address->floor_num) }}"
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Flat Number</label>
                <input type="text" name="flat_num" value="{{ old('flat_num', $address->flat_num) }}"
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">PACI Number</label>
                <input type="number" name="paci_num" value="{{ old('paci_num', $address->paci_num) }}"
                       class="w-full border rounded px-4 py-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Address Notes</label>
                <textarea name="address_notes" rows="3"
                          class="w-full border rounded px-4 py-2">{{ old('address_notes', $address->address_notes) }}</textarea>
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
