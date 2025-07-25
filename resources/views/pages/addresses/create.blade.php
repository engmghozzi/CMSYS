<x-layouts.app :title="'Add Address for ' . $client->name">

    <div class="max-w-3xl mx-auto bg-white p-4 md:p-6 rounded shadow-md">

        {{-- Client Info Header --}}
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Add Address to') }} </h2>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-600">{{ __('Client Name') }}:</span>
                    <span class="font-medium">{{ $client->name }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ __('ID') }}:</span>
                    <span class="font-medium">{{ $client->id }}</span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('addresses.store', $client->id) }}" class="space-y-4 md:space-y-6">
            @csrf

            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Area') }}</label>
                    <input type="text" name="area" value="{{ old('area') }}" required
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Block') }}</label>
                    <input type="text" name="block" value="{{ old('block') }}" required
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Street') }}</label>
                    <input type="text" name="street" value="{{ old('street') }}" required
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('House Number') }}</label>
                    <input type="text" name="house_num" value="{{ old('house_num') }}"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Floor Number') }}</label>
                    <input type="text" name="floor_num" value="{{ old('floor_num') }}"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Flat Number') }}</label>
                    <input type="text" name="flat_num" value="{{ old('flat_num') }}"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('PACI Number') }}</label>
                    <input type="number" name="paci_num" value="{{ old('paci_num') }}"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('Address Notes') }}</label>
                <textarea name="address_notes" rows="3"
                          class="mt-1 w-full border rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address_notes') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('clients.show', $client->id) }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Back') }}
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
