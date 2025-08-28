<x-layouts.app :title="'Edit Address for ' . $client->name">

    <x-ui.form-layout
        :title="__('Edit Address')"
        :description="$client->name"
        :back-url="route('clients.show', $client)"
        :back-label="__('Back to Client')"
    >
        <!-- Address Form -->
        <form method="POST" action="{{ route('addresses.update', ['client' => $client->id, 'address' => $address->id]) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Area -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Area') }}</label>
                    <input type="text" name="area" value="{{ old('area', $address->area) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>

                <!-- Block -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Block') }}</label>
                    <input type="text" name="block" value="{{ old('block', $address->block) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>

                <!-- Street -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Street') }}</label>
                    <input type="text" name="street" value="{{ old('street', $address->street) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>

                <!-- House Number -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('House Number') }}</label>
                    <input type="text" name="house_num" value="{{ old('house_num', $address->house_num) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>

                <!-- Floor Number -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Floor Number') }}</label>
                    <input type="text" name="floor_num" value="{{ old('floor_num', $address->floor_num) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>

                <!-- Flat Number -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Flat Number') }}</label>
                    <input type="text" name="flat_num" value="{{ old('flat_num', $address->flat_num) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>

                <!-- PACI Number -->
                <div class="space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('PACI') }}</label>
                    <input type="number" name="paci_num" value="{{ old('paci_num', $address->paci_num) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                </div>
            </div>

            <!-- Address Notes -->
            <div class="mt-6 space-y-2 rtl:space-x-reverse">
                <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Address Notes') }}</label>
                <textarea name="address_notes" rows="3"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right">{{ old('address_notes', $address->address_notes) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3 rtl:space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

</x-layouts.app>
