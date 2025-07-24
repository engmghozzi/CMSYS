<x-layouts.app :title="__('New Machine')">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Create New Machine</h1>

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('machines.store', $client->id) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Contract -->
                    <div>
                        <label for="contract_id" class="block font-medium">Contract</label>
                        <select name="contract_id" id="contract_id" class="form-select w-full border rounded px-4 py-2">
                            @foreach ($client->contracts as $contract)
                                <option value="{{ $contract->id }}">{{ $contract->contract_num }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address_id" class="block font-medium">Address</label>
                        <select name="address_id" id="address_id" class="form-select w-full border rounded px-4 py-2" required>
                            @foreach ($client->addresses as $address)
                                <option value="{{ $address->id }}">
                                    {{ $address->area }} - Block {{ $address->block }} - Street {{ $address->street }} - House {{ $address->house }}-
                                    {{ $address->floor }} - {{ $address->flat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Serial Number -->
                    <div>
                        <label for="serial_number" class="block font-medium">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-input w-full border rounded px-4 py-2" required>
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block font-medium">Brand</label>
                        <select name="brand" id="brand" class="form-select w-full border rounded px-4 py-2">
                            @foreach (App\Enums\MachineBrand::cases() as $brand)
                                <option value="{{ $brand->value }}">{{ $brand->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block font-medium">Type</label>
                        <select name="type" id="type" class="form-select w-full border rounded px-4 py-2">
                            
                            @foreach (App\Enums\MachineType::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->value }}</option>
                            @endforeach
                           

                        </select>
                    </div>

                    <!-- UOM -->
                    <div>
                        <label for="UOM" class="block font-medium">UOM</label>

                        <select name="UOM" class="form-select w-full border rounded px-4 py-2" required>
                            <option value="HP" >HP</option>
                            <option value="PTU" >PTU</option>
                            <option value="Other" >Other</option>
                        </select>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block font-medium">Capacity</label>
                        <input type="number" step="0.01" name="capacity" id="capacity" class="form-input w-full border rounded px-4 py-2">
                    </div>

                    <!-- Efficiency -->
                    <div>
                        <label for="current_efficiency" class="block font-medium">Efficiency (%)</label>
                        <select name="current_efficiency" id="current_efficiency" class="form-select w-full border rounded px-4 py-2">
                            <option value="" disabled selected>Select Efficiency</option>
                            @foreach( App\Enums\MachineEfficiency::cases() as $current_efficiency)
                                <option value="{{ $current_efficiency->value }}">{{ $current_efficiency->value }}</option>
                            @endforeach
                        </select>
                      
                    </div>

                    <!-- Cost -->
                    <div>
                        <label for="cost" class="block font-medium">Estimated Cost (KWD)</label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-input w-full border rounded px-4 py-2">
                    </div>

                    <!-- Assessment -->
                    <div class="md:col-span-2">
                        <label for="assessment" class="block font-medium">Assessment Notes</label>
                        <textarea name="assessment" id="assessment" rows="3" class="form-textarea w-full border rounded px-4 py-2"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
