<x-layouts.app :title="__('Edit Machine')">

    <x-ui.form-layout
        :title="__('Edit Machine')"
        :back-url="route('contracts.show', [$client, $machine->contract])"
        :back-label="__('Back to Contract')"
    >
        <form method="POST" action="{{ route('machines.update', [$client->id, $machine->id]) }}">
            @csrf
            @method('PUT')

            <!-- Contract & Address Info - Fixed Display -->
            <div class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block font-medium text-gray-700">{{ __('Contract') }}</span>
                        <span class="block mt-1 p-2 bg-white rounded border border-gray-300">
                            {{ $machine->contract->contract_num }}
                        </span>
                        <input type="hidden" name="contract_id" value="{{ $machine->contract_id }}">
                    </div>

                    <div>
                        <span class="block font-medium text-gray-700">{{ __('Address') }}</span>
                        <span class="block mt-1 p-2 bg-white rounded border border-gray-300">
                            {{__('Area')}}{{ $machine->address->area }} - {{__('Block')}} {{ $machine->address->block }} - {{__('Street')}} {{ $machine->address->street }} - {{__('House')}} {{ $machine->address->house }} - {{__('Floor')}} {{ $machine->address->floor }} - {{__('Flat')}}{{ $machine->address->flat }}
                        </span>
                        <input type="hidden" name="address_id" value="{{ $machine->address_id }}">
                    </div>
                </div>
            </div>

            <!-- Machine Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block font-medium text-gray-700 mb-1">{{ __('Serial Number') }}</label>
                    <input type="text" name="serial_number" id="serial_number" 
                        class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('serial_number', $machine->serial_number) }}" required>
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand" class="block font-medium text-gray-700 mb-1">{{ __('Brand') }}</label>
                    <select name="brand" id="brand" class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach (App\Enums\MachineBrand::cases() as $brand)
                            <option value="{{ $brand->value }}" {{ $machine->brand === $brand->value ? 'selected' : '' }}>
                                {{ $brand->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block font-medium text-gray-700 mb-1">{{ __('Type') }}</label>
                    <select name="type" id="type" class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach (App\Enums\MachineType::cases() as $type)
                            <option value="{{ $type->value }}" {{ $machine->type === $type->value ? 'selected' : '' }}>
                                {{ $type->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- UOM -->
                <div>
                    <label for="UOM" class="block font-medium text-gray-700 mb-1">{{ __('UOM') }}</label>
                    <select name="UOM" class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @foreach (['HP', 'PTU', 'Other'] as $uom)
                            <option value="{{ $uom }}" {{ $machine->UOM === $uom ? 'selected' : '' }}>{{ $uom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block font-medium text-gray-700 mb-1">{{ __('Capacity') }}</label>
                    <input type="number" step="0.01" name="capacity" id="capacity" 
                        class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('capacity', $machine->capacity) }}">
                </div>

                <!-- Efficiency -->
                <div>
                    <label for="current_efficiency" class="block font-medium text-gray-700 mb-1">{{ __('Efficiency') }}(%)</label>
                    <select name="current_efficiency" id="current_efficiency" 
                        class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled>{{ __('Select Efficiency') }}</option>
                        @foreach(App\Enums\MachineEfficiency::cases() as $eff)
                            <option value="{{ $eff->value }}" {{ $machine->current_efficiency == $eff->value ? 'selected' : '' }}>
                                {{ $eff->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cost -->
                <div>
                    <label for="cost" class="block font-medium text-gray-700 mb-1">{{ __('Estimated Cost') }} ({{ __('KWD') }})</label>
                    <input type="number" step="0.01" name="cost" id="cost" 
                        class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('cost', $machine->cost) }}">
                </div>
            </div>

            <!-- Assessment -->
            <div class="mt-6">
                <label for="assessment" class="block font-medium text-gray-700 mb-1">{{ __('Assessment Notes') }}</label>
                <textarea name="assessment" id="assessment" rows="4" 
                    class="w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('assessment', $machine->assessment) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('contracts.show', [$client, $machine->contract]) }}"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

</x-layouts.app>
