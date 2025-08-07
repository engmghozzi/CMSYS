<x-layouts.app :title="'Add Contract for ' . $client->name">
    <div class="min-h-screen bg-gray-50 rounded-xl shadow-lg border border-gray-200 p-6 w-3/4 mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Add Contract') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('Client') }}: <span class="font-semibold">{{ $client->name }}</span></p>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contracts.store', $client->id) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            @if($client->addresses->count() > 0)
                <input type="hidden" name="address_id" value="{{ $client->addresses->first()->id }}">
            @endif

            <!-- Basic Contract Information Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Contract Information') }}</h2>
                
                <!-- Address (read-only) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Address') }}</label>
                    <div class="w-full border rounded px-4 py-2 bg-gray-50 text-gray-700 shadow-sm">
                        {{ $client->addresses->first()->area }} - {{ $client->addresses->first()->block }} - {{ $client->addresses->first()->street }} - {{ $client->addresses->first()->house_num }} - {{ $client->addresses->first()->floor_num }} - {{ $client->addresses->first()->flat_num }} - {{ $client->addresses->first()->paci_num }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Type') }}</label>
                        <select name="type" required class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            @foreach (['L', 'LS', 'C', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Duration')}} ({{__('months')}})</label>
                        <input type="number" step="0.01" name="duration_months" value="{{ old('duration_months') }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Total Amount')}} ({{__('KWD')}})</label>
                        <input type="number" step="0.001" name="total_amount" value="{{ old('total_amount') }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Central Machines') }}</label>
                        <input type="number" name="centeral_machines" value="{{ old('centeral_machines', 0) }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Unit Machines') }}</label>
                        <input type="number" name="unit_machines" value="{{ old('unit_machines', 0) }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" required class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            @foreach (['active', 'expired', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>{{ __($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Commission Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Commission Details') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Amount')}} ({{__('KWD')}})</label>
                        <input type="number" step="0.001" name="commission_amount" value="{{ old('commission_amount') }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Type') }}</label>
                        <select name="commission_type" class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            <option value="">{{ __('No commission') }}</option>
                            @foreach (['Incentive Bonus', 'Referral Commission', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('commission_type') === $type ? 'selected' : '' }}>{{ __($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Recipient') }}</label>
                        <input type="text" name="commission_recipient" value="{{ old('commission_recipient') }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Date') }}</label>
                        <input type="date" name="commission_date" value="{{ old('commission_date') }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>

            <!-- Additional Details Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Additional Details') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Details') }}</label>
                        <textarea name="details" rows="4" class="w-full border rounded px-4 py-2 bg-white shadow-sm">{{ old('details') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Attachment') }}</label>
                        <input type="file" name="attachment_url" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Save Contract') }}
                </button>
                <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-6 py-3 text-white hover:bg-gray-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
