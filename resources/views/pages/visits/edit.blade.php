<x-layouts.app :title="__('Edit Visit Details')">
    <div class="min-h-screen py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Edit Visit Details') }}</h1>
                        <p class="text-gray-600 mt-1">{{__('Client')}} : {{ $client->name }} - {{ __('Contract') }} #{{ $visit->contract->contract_num }}</p>
                    </div>
                    
                    <a href="{{ route('pages.visits.show', [$client, $contract, $visit]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('Back to Visit') }}
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Read-only Information -->
                <div class="border-b border-gray-200 p-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">{{ __('Address Details') }}</h3>
                                <div class="mt-2 text-sm text-gray-900">
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                        <div><span class="font-medium">{{__('Area')}}</span>: {{ $visit->contract->address->area }}</div>
                                        <div><span class="font-medium">{{ __('Block') }}</span>: {{ $visit->contract->address->block }}</div>
                                        <div><span class="font-medium">{{ __('Street') }}</span>: {{ $visit->contract->address->street }}</div>
                                        <div><span class="font-medium">{{ __('House') }}</span>: {{ $visit->contract->address->house_num }}</div>
                                        <div><span class="font-medium">{{ __('Floor') }}</span>: {{ $visit->contract->address->floor }}</div>
                                        <div><span class="font-medium">{{ __('Flat') }}</span>: {{ $visit->contract->address->flat }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="font-medium">{{ __('Paci Number') }}</span>: {{ $visit->contract->address->paci_num }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Editable Form -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('pages.visits.update', [$client, $contract, $visit]) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Visit Status & Type -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Visit Status') }}</label>
                                <select name="visit_status" class="mt-1 block w-full p-2.5 bg-white rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="scheduled" {{ old('visit_status', $visit->visit_status) === 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                                    <option value="completed" {{ old('visit_status', $visit->visit_status) === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                    <option value="cancelled" {{ old('visit_status', $visit->visit_status) === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Visit Type') }}</label>
                                <select name="visit_type" class="mt-1 block w-full p-2.5 bg-white rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="proactive" {{ old('visit_type', $visit->visit_type) === 'proactive' ? 'selected' : '' }}>{{ __('Proactive') }}</option>
                                    <option value="maintenance" {{ old('visit_type', $visit->visit_type) === 'maintenance' ? 'selected' : '' }}>{{ __('Maintenance') }}</option>
                                    <option value="repair" {{ old('visit_type', $visit->visit_type) === 'repair' ? 'selected' : '' }}>{{ __('Repair') }}</option>
                                    <option value="installation" {{ old('visit_type', $visit->visit_type) === 'installation' ? 'selected' : '' }}>{{ __('Installation') }}</option>
                                    <option value="other" {{ old('visit_type', $visit->visit_type) === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Visit Dates -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Visit Dates') }}</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Scheduled Date') }}</label>
                                    <input type="date" name="visit_scheduled_date" value="{{ $visit->visit_scheduled_date ? date('Y-m-d', strtotime($visit->visit_scheduled_date)) : old('visit_scheduled_date') }}"
                                        class="mt-1 block w-full p-2.5 bg-white rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Actual Date') }}</label>
                                    <input type="date" name="visit_actual_date" value="{{ $visit->visit_actual_date ? date('Y-m-d', strtotime($visit->visit_actual_date)) : old('visit_actual_date') }}"
                                        class="mt-1 block w-full p-2.5 bg-white rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Technician & Notes -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Assign Technician') }}</h2>
                                <select name="technician_id" class="mt-1 block w-full p-2.5 bg-white rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">{{ __('Select Technician') }}</option>
                                    @foreach($technicians as $technician)
                                        @if($technician->hasRole('technician'))
                                            <option value="{{ $technician->id }}" {{ old('technician_id', $visit->technician_id) === $technician->id ? 'selected' : '' }}>
                                                {{ $technician->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                    
                                </select>
                            </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Visit Notes') }}</h2>
                                <textarea name="visit_notes" rows="4" 
                                    class="mt-1 block w-full p-2.5 bg-white rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('visit_notes', $visit->visit_notes) }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Update Visit') }}
                            </button>
                            <a href="{{ route('pages.visits.show', [$client, $contract, $visit]) }}" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
