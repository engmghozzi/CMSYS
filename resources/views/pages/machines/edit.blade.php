<x-layouts.app :title="__('Edit Machine')">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Edit Machine</h1>

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('machines.update', [$client, $machine]) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Contract -->
                    <div>
                        <label for="contract_id" class="block font-medium">Contract</label>
                        <select name="contract_id" id="contract_id" class="form-select w-full border rounded px-4 py-2">
                            @foreach ($client->contracts as $contract)
                                <option value="{{ $contract->id }}" {{ $machine->contract_id == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_num }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address_id" class="block font-medium">Address</label>
                        <select name="address_id" id="address_id" class="form-select w-full border rounded px-4 py-2" required>
                            @foreach ($client->addresses as $address)
                                <option value="{{ $address->id }}" {{ $machine->address_id == $address->id ? 'selected' : '' }}>
                                    {{ $address->area }} - Block {{ $address->block }} - Street {{ $address->street }} - House {{ $address->house }} - {{ $address->floor }} - {{ $address->flat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Serial Number -->
                    <div>
                        <label for="serial_number" class="block font-medium">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-input w-full border rounded px-4 py-2" value="{{ old('serial_number', $machine->serial_number) }}" required>
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block font-medium">Brand</label>
                        <select name="brand" id="brand" class="form-select w-full border rounded px-4 py-2">
                            @foreach (App\Enums\MachineBrand::cases() as $brand)
                                <option value="{{ $brand->value }}" {{ $machine->brand === $brand->value ? 'selected' : '' }}>
                                    {{ $brand->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block font-medium">Type</label>
                        <select name="type" id="type" class="form-select w-full border rounded px-4 py-2">
                            @foreach (App\Enums\MachineType::cases() as $type)
                                <option value="{{ $type->value }}" {{ $machine->type === $type->value ? 'selected' : '' }}>
                                    {{ $type->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- UOM -->
                    <div>
                        <label for="UOM" class="block font-medium">UOM</label>
                        <select name="UOM" class="form-select w-full border rounded px-4 py-2" required>
                            @foreach (['HP', 'PTU', 'Other'] as $uom)
                                <option value="{{ $uom }}" {{ $machine->UOM === $uom ? 'selected' : '' }}>{{ $uom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block font-medium">Capacity</label>
                        <input type="number" step="0.01" name="capacity" id="capacity" class="form-input w-full border rounded px-4 py-2" value="{{ old('capacity', $machine->capacity) }}">
                    </div>

                    <!-- Efficiency -->
                    <div>
                        <label for="current_efficiency" class="block font-medium">Efficiency (%)</label>
                        <select name="current_efficiency" id="current_efficiency" class="form-select w-full border rounded px-4 py-2">
                            <option value="" disabled>Select Efficiency</option>
                            @foreach( App\Enums\MachineEfficiency::cases() as $eff)
                                <option value="{{ $eff->value }}" {{ $machine->current_efficiency == $eff->value ? 'selected' : '' }}>
                                    {{ $eff->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cost -->
                    <div>
                        <label for="cost" class="block font-medium">Estimated Cost (KWD)</label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-input w-full border rounded px-4 py-2" value="{{ old('cost', $machine->cost) }}">
                    </div>

                    <!-- Assessment -->
                    <div class="md:col-span-2">
                        <label for="assessment" class="block font-medium">Assessment Notes</label>
                        <textarea name="assessment" id="assessment" rows="3" class="form-textarea w-full border rounded px-4 py-2">{{ old('assessment', $machine->assessment) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
