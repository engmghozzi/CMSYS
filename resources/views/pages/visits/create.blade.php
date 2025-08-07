<x-layouts.app :title="__('New Visit')">
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
                    <h2 class="text-lg font-semibold text-blue-900 mb-2">{{ __('Visit for Contract') }}</h2>
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
            <form method="POST" action="{{ route('pages.visits.store', [$client, $contract]) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="address_id" value="{{ $contract->address_id }}">

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Visit Type -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="visit_type" class="block font-medium text-gray-700 mb-2">{{ __('Visit Type') }}</label>
                            <select name="visit_type" id="visit_type" class="form-select w-full border rounded-lg px-4 py-2 bg-white" required>
                                <option value="">{{ __('Select Visit Type') }}</option>
                                <option value="proactive" {{ old('visit_type') == 'proactive' ? 'selected' : '' }}>{{ __('Proactive') }}</option>
                                <option value="maintenance" {{ old('visit_type') == 'maintenance' ? 'selected' : '' }}>{{ __('Maintenance') }}</option>
                                <option value="repair" {{ old('visit_type') == 'repair' ? 'selected' : '' }}>{{ __('Repair') }}</option>
                                <option value="installation" {{ old('visit_type') == 'installation' ? 'selected' : '' }}>{{ __('Installation') }}</option>
                                <option value="other" {{ old('visit_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                        </div>

                        <!-- Visit Status -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="visit_status" class="block font-medium text-gray-700 mb-2">{{ __('Visit Status') }}</label>
                            <select name="visit_status" id="visit_status" class="form-select w-full border rounded-lg px-4 py-2 bg-white" required>
                                <option value="">{{ __('Select Status') }}</option>
                                <option value="scheduled" {{ old('visit_status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                                <option value="completed" {{ old('visit_status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="cancelled" {{ old('visit_status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        
                        <!-- Scheduled Date -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="visit_scheduled_date" class="block font-medium text-gray-700 mb-2">{{ __('Scheduled Date') }}</label>
                            <input type="date" name="visit_scheduled_date" id="visit_scheduled_date" value="{{ old('visit_scheduled_date') }}"
                                   class="form-input w-full border rounded-lg px-4 py-2 bg-white" required>
                        </div>

                        <!-- Actual Date -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="visit_actual_date" class="block font-medium text-gray-700 mb-2">{{ __('Actual Date') }}</label>
                            <input type="date" name="visit_actual_date" id="visit_actual_date" value="{{ old('visit_actual_date') }}"
                                   class="form-input w-full border rounded-lg px-4 py-2 bg-white">
                        </div>

                        <!-- Technician -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="technician_id" class="block font-medium text-gray-700 mb-2">{{ __('Technician') }}</label>
                            <select name="technician_id" id="technician_id" class="form-select w-full border rounded-lg px-4 py-2 bg-white">
                                <option value="">{{ __('Select Technician') }}</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Visit Notes -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="visit_notes" class="block font-medium text-gray-700 mb-2">{{ __('Visit Notes') }}</label>
                            <textarea name="visit_notes" id="visit_notes" rows="4" 
                                    class="form-textarea w-full border rounded-lg px-4 py-2 bg-white">{{ old('visit_notes') }}</textarea>
                        </div>
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
                        {{ __('Create Visit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>