<x-layouts.app :title="__('Edit Machine')">
    <div class="min-h-screen w-3/4 mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">{{ __('Edit Machine') }}</h1>

        @if ($errors->any())
            <div class="mb-6 rounded-md bg-red-50 p-4 text-red-800 border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                        class="form-input w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('serial_number', $machine->serial_number) }}" required>
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand" class="block font-medium text-gray-700 mb-1">{{ __('Brand') }}</label>
                    <select name="brand" id="brand" class="form-select w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <select name="type" id="type" class="form-select w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <select name="UOM" class="form-select w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @foreach (['HP', 'PTU', 'Other'] as $uom)
                            <option value="{{ $uom }}" {{ $machine->UOM === $uom ? 'selected' : '' }}>{{ $uom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block font-medium text-gray-700 mb-1">{{ __('Capacity') }}</label>
                    <input type="number" step="0.01" name="capacity" id="capacity" 
                        class="form-input w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('capacity', $machine->capacity) }}">
                </div>

                <!-- Efficiency -->
                <div>
                    <label for="current_efficiency" class="block font-medium text-gray-700 mb-1">{{ __('Efficiency') }}(%)</label>
                    <select name="current_efficiency" id="current_efficiency" 
                        class="form-select w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
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
                        class="form-input w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('cost', $machine->cost) }}">
                </div>
            </div>

            <!-- Assessment -->
            <div class="mt-6">
                <label for="assessment" class="block font-medium text-gray-700 mb-1">{{ __('Assessment Notes') }}</label>
                <textarea name="assessment" id="assessment" rows="4" 
                    class="form-textarea w-full border rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('assessment', $machine->assessment) }}</textarea>
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <a href="{{ route('contracts.show', [$client->id, $machine->contract_id]) }}"
                    class="inline-flex items-center gap-2 rounded bg-gray-500 px-3 py-2 text-white hover:bg-red-500 hover:text-white">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
