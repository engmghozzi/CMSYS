<x-layouts.app :title="__('New Machine')">
    <x-ui.form-layout
        :title="__('Create New Machine')"
        :back-url="route('clients.show', $client)"
        :back-label="__('Cancel')"
    >
        <form method="POST" action="{{ route('machines.store', $client->id) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Contract -->
                <div>
                    <label for="contract_id" class="block text-sm font-medium text-gray-700">{{ __('Contract') }}</label>
                    <select name="contract_id" id="contract_id" class="form-select w-full border rounded px-4 py-2">
                        @foreach ($client->contracts as $contract)
                            <option value="{{ $contract->id }}">{{ $contract->contract_num }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Address -->
                <div>
                    <label for="address_id" class="block text-sm font-medium text-gray-700">{{ __('Address') }}</label>
                    <select name="address_id" id="address_id" class="form-select w-full border rounded px-4 py-2" required>
                        @foreach ($client->addresses as $address)
                            <option value="{{ $address->id }}">
                                {{ $address->area }} - {{ __('Block') }} {{ $address->block }} - {{ __('Street') }} {{ $address->street }} - {{ __('House') }} {{ $address->house }}-
                                {{ $address->floor }} - {{ $address->flat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700">{{ __('Serial Number') }}</label>
                    <input type="text" name="serial_number" id="serial_number" class="form-input w-full border rounded px-4 py-2" required>
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700">{{ __('Brand') }}</label>
                    <select name="brand" id="brand" class="form-select w-full border rounded px-4 py-2">
                        @foreach (App\Enums\MachineBrand::cases() as $brand)
                            <option value="{{ $brand->value }}">{{ $brand->value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                    <select name="type" id="type" class="form-select w-full border rounded px-4 py-2">
                        @foreach (App\Enums\MachineType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- UOM -->
                <div>
                    <label for="UOM" class="block text-sm font-medium text-gray-700">{{ __('UOM') }}</label>
                    <select name="UOM" class="form-select w-full border rounded px-4 py-2" required>
                        <option value="HP">HP</option>
                        <option value="PTU">PTU</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">{{ __('Capacity') }}</label>
                    <input type="number" step="0.01" name="capacity" id="capacity" class="form-input w-full border rounded px-4 py-2">
                </div>

                <!-- Efficiency -->
                <div>
                    <label for="current_efficiency" class="block text-sm font-medium text-gray-700">{{ __('Efficiency (%)') }}</label>
                    <select name="current_efficiency" id="current_efficiency" class="form-select w-full border rounded px-4 py-2">
                        <option value="" disabled selected>{{ __('Select Efficiency') }}</option>
                        @foreach( App\Enums\MachineEfficiency::cases() as $current_efficiency)
                            <option value="{{ $current_efficiency->value }}">{{ $current_efficiency->value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cost -->
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700">{{ __('Estimated Cost (KWD)') }}</label>
                    <input type="number" step="0.01" name="cost" id="cost" class="form-input w-full border rounded px-4 py-2">
                </div>

                <!-- Assessment -->
                <div class="md:col-span-2">
                    <label for="assessment" class="block text-sm font-medium text-gray-700">{{ __('Assessment Notes') }}</label>
                    <textarea name="assessment" id="assessment" rows="3" class="form-textarea w-full border rounded px-4 py-2"></textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded bg-gray-100 px-6 py-3 text-gray-700 hover:bg-gray-200 transition-colors">{{ __('Cancel') }}</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 transition-colors">{{ __('Save') }}</button>
            </div>
        </form>
    </x-ui.form-layout>
</x-layouts.app>
