<x-layouts.app :title="'Add Address for ' . $client->name">

    <x-ui.form-layout
        :title="__('Add Address')"
        :subtitle="$client->name"
        :back-url="route('clients.show', $client)"
        :back-label="__('Back')"
    >
        <form method="POST" action="{{ route('addresses.store', $client->id) }}" class="space-y-6">
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

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    {{ __('Back') }}
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

</x-layouts.app>
