<x-layouts.app :title="__('New Machine')">
    <div class="container mx-auto px-4 py-2 max-w">
        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Contract Info --}}
        <div class="bg-blue-50 rounded-lg shadow-md p-4 border border-blue-200 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-blue-900 mb-2">{{ __('Machine for Contract') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Contract Number:') }}</span>
                            <span class="text-blue-900">{{ $contract->contract_num }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Client:') }}</span>
                            <span class="text-blue-900">{{ $client->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">{{ __('Status:') }}</span>
                            <span class="text-blue-900">{{ $contract->status }}</span>
                        </div>
                    </div>
                    @if($contract->address)
                        <div class="mt-3 p-3 bg-blue-100 rounded border border-blue-200">
                            <span class="font-medium text-blue-800">{{ __('Address:') }}</span>
                            <span class="text-blue-900">{{ $contract->address->area }} - Block {{ $contract->address->block }} - Street {{ $contract->address->street }} - House {{ $contract->address->house }} - {{ $contract->address->floor }} - {{ $contract->address->flat }}</span>
                        </div>
                    @endif
                </div>
                <div class="text-right">
                    <a href="{{ route('contracts.show', [$client->id, $contract->id]) }}"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back to Contract') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-lg shadow-md p-6 border">
            <form method="POST" action="{{ route('machines.store.from.contract', [$client->id, $contract->id]) }}">
                @csrf
                <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Serial Number -->
                    <div>
                        <label for="serial_number" class="block font-medium text-gray-700 mb-2">{{ __('Serial Number') }}</label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" 
                               class="form-input w-full border rounded px-4 py-2" required>
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block font-medium text-gray-700 mb-2">{{ __('Brand') }}</label>
                        <select name="brand" id="brand" class="form-select w-full border rounded px-4 py-2" required>
                            <option value="">{{ __('Select Brand') }}</option>
                            @foreach (App\Enums\MachineBrand::cases() as $brand)
                                <option value="{{ $brand->value }}" {{ old('brand') == $brand->value ? 'selected' : '' }}>
                                    {{ $brand->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block font-medium text-gray-700 mb-2">{{ __('Type') }}</label>
                        <select name="type" id="type" class="form-select w-full border rounded px-4 py-2" required>
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach (App\Enums\MachineType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                    {{ $type->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- UOM -->
                    <div>
                        <label for="UOM" class="block font-medium text-gray-700 mb-2">{{ __('UOM') }}</label>
                        <select name="UOM" class="form-select w-full border rounded px-4 py-2" required>
                            <option value="">{{ __('Select UOM') }}</option>
                            <option value="HP" {{ old('UOM') == 'HP' ? 'selected' : '' }}>HP</option>
                            <option value="PTU" {{ old('UOM') == 'PTU' ? 'selected' : '' }}>PTU</option>
                            <option value="Other" {{ old('UOM') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block font-medium text-gray-700 mb-2">{{ __('Capacity') }}</label>
                        <input type="number" step="0.01" name="capacity" id="capacity" value="{{ old('capacity') }}" 
                               class="form-input w-full border rounded px-4 py-2" required>
                    </div>

                    <!-- Efficiency -->
                    <div>
                        <label for="current_efficiency" class="block font-medium text-gray-700 mb-2">{{ __('Efficiency (%)') }}</label>
                        <select name="current_efficiency" id="current_efficiency" class="form-select w-full border rounded px-4 py-2" required>
                            <option value="">{{ __('Select Efficiency') }}</option>
                            @foreach( App\Enums\MachineEfficiency::cases() as $current_efficiency)
                                <option value="{{ $current_efficiency->value }}" {{ old('current_efficiency') == $current_efficiency->value ? 'selected' : '' }}>
                                    {{ $current_efficiency->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cost -->
                    <div>
                        <label for="cost" class="block font-medium text-gray-700 mb-2">{{ __('Estimated Cost (KWD)') }}</label>
                        <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost') }}" 
                               class="form-input w-full border rounded px-4 py-2" required>
                    </div>

                    <!-- Assessment -->
                    <div class="md:col-span-2">
                        <label for="assessment" class="block font-medium text-gray-700 mb-2">{{ __('Assessment Notes') }}</label>
                        <textarea name="assessment" id="assessment" rows="3" 
                                  class="form-textarea w-full border rounded px-4 py-2">{{ old('assessment') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('contracts.show', [$client->id, $contract->id]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('Create Machine') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app> 