<x-layouts.app :title="'Edit Contract for ' . $client->name">
    <div class="min-h-screen bg-gray-50 rounded-xl shadow-lg border border-gray-200 p-6 w-3/4 mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Edit Contract') }} # {{ $contract->contract_num }}</h1>
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

        <form method="POST" action="{{ route('contracts.update', [$client->id, $contract->id]) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="address_id" value="{{ $contract->address_id }}">

            <!-- Basic Contract Information Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Contract Information') }}</h2>
                
                <!-- Address (read-only) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Address') }}</label>
                    <div class="w-full border rounded px-4 py-2 bg-white text-gray-700 shadow-sm">
                        {{ $contract->address->area }} - {{ $contract->address->block }} - {{ $contract->address->street }} - {{ $contract->address->house_num }} - {{ $contract->address->floor_num }} - {{ $contract->address->flat_num }} - {{ $contract->address->paci_num }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Type') }}</label>
                        <select name="type" required class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                        @foreach (['L', 'LS', 'C', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('type', $contract->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Duration')}} ({{__('months')}})</label>
                        <input type="number" step="0.01" name="duration_months" value="{{ old('duration_months', $contract->duration_months) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>
            <!-- Machines Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Machines') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Central Machines') }}</label>
                        <input type="number" name="centeral_machines" value="{{ old('centeral_machines', $contract->centeral_machines) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Unit Machines') }}</label>
                        <input type="number" name="unit_machines" value="{{ old('unit_machines', $contract->unit_machines) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>
            <!-- Financial Information Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Financial Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Total Amount') }}</label>
                        <input type="number" step="0.001" name="total_amount" value="{{ old('total_amount', $contract->total_amount) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            @foreach (['draft', 'signed', 'active', 'expired', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $contract->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!-- Commission Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Commission') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Amount') }}</label>
                        <input type="number" step="0.001" name="commission_amount" value="{{ old('commission_amount', $contract->commission_amount) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Type') }}</label>
                        <select name="commission_type" class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                        <option value="">None</option>
                        @foreach (['Incentive Bonus', 'Referral Commission', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('commission_type', $contract->commission_type) === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Recipient') }}</label>
                        <input type="text" name="commission_recipient" value="{{ old('commission_recipient', $contract->commission_recipient) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                </div>

                <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Date') }}</label>
                        <input type="date" name="commission_date" value="{{ old('commission_date', $contract->commission_date) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>
            <!-- Additional Details Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Additional Details') }}</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Details') }}</label>
                    <textarea name="details" rows="4" class="w-full border rounded px-4 py-2 bg-white shadow-sm">{{ old('details', $contract->details) }}</textarea>
            </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Attachment') }} ({{ __('Upload to replace') }})</label>
                    <input type="file" name="attachment_url" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                @if($contract->attachment_url)
                    <p class="mt-1 text-sm text-gray-500">
                            <a href="{{ asset('storage/' . $contract->attachment_url) }}" class="text-blue-500 underline" target="_blank">{{ __('View Document') }}</a>
                    </p>
                @endif
                </div>
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <a href="{{ route('contracts.show', [$client->id, $contract->id]) }}"
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
