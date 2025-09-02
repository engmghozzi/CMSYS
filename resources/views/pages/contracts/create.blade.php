<x-layouts.app :title="isset($contract) ? 'Renew Contract for ' . $client->name : 'Add Contract for ' . $client->name">
    <x-ui.form-layout
        :title="isset($contract) ? __('Renew Contract') : __('Add Contract')"
        :subtitle="isset($contract) ? __('Contract') . ' ' . $contract->contract_num : __('Client') . ': ' . $client->name"
        :back-url="route('clients.show', $client)"
        :back-label="__('Cancel')"
    >
        @if(isset($contract))
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-blue-800 font-medium">{{ __('Renewal Mode: This form is pre-filled with data from the previous contract.') }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('contracts.store', $client->id) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            @if(isset($contract))
                <input type="hidden" name="renewal_contract_id" value="{{ $contract->id }}">
                <input type="hidden" name="address_id" value="{{ $contract->address->id }}">
            @elseif(isset($address))
                <input type="hidden" name="address_id" value="{{ $address->id }}">
            @elseif($client->addresses->count() > 0)
                <input type="hidden" name="address_id" value="{{ $client->addresses->first()->id }}">
            @endif

            <!-- Basic Contract Information Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Contract Information') }}</h2>

                <!-- Address (read-only) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Address') }}</label>
                    <div class="w-full border rounded px-4 py-2 bg-gray-50 text-gray-700 shadow-sm">
                        @if(isset($contract))
                            {{ $contract->address->area }} - {{ $contract->address->block }} - {{ $contract->address->street }} - {{ $contract->address->house_num }} - {{ $contract->address->floor_num }} - {{ $contract->address->flat_num }} - {{ $contract->address->paci_num }}
                        @elseif(isset($address))
                            {{ $address->area }} - {{ $address->block }} - {{ $address->street }} - {{ $address->house_num }} - {{ $address->floor_num }} - {{ $address->flat_num }} - {{ $address->paci_num }}
                        @elseif($client->addresses->count() > 0)
                            {{ $client->addresses->first()->area }} - {{ $client->addresses->first()->block }} - {{ $client->addresses->first()->street }} - {{ $client->addresses->first()->house_num }} - {{ $client->addresses->first()->floor_num }} - {{ $client->addresses->first()->flat_num }} - {{ $client->addresses->first()->paci_num }}
                        @else
                            {{ __('No address available') }}
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Type') }}</label>
                        <select name="type" required class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            @foreach (['L', 'LS', 'C', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('type', isset($contract) ? $contract->type : '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date', isset($contract) ? date('Y-m-d') : '') }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Duration')}} ({{__('months')}})</label>
                        <input type="number" step="0.01" name="duration_months" value="{{ old('duration_months', isset($contract) ? $contract->duration_months : '') }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Total Amount')}} ({{__('KWD')}})</label>
                        <input type="number" step="0.001" name="total_amount" value="{{ old('total_amount', isset($contract) ? $contract->total_amount : '') }}" required class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>



                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" required class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            @if(isset($contract))
                                <option value="active" selected>{{ __('active') }}</option>
                            @else
                                @foreach (['active', 'expired', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>{{ __($status) }}</option>
                                @endforeach
                            @endif
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
                        <input type="number" step="0.001" name="commission_amount" value="{{ old('commission_amount', isset($contract) ? $contract->commission_amount : '') }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Type') }}</label>
                        <select name="commission_type" class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            <option value="">{{ __('No commission') }}</option>
                            @foreach (['Incentive Bonus', 'Referral Commission', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('commission_type', isset($contract) ? $contract->commission_type : '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Recipient') }}</label>
                        <input type="text" name="commission_recipient" value="{{ old('commission_recipient', isset($contract) ? $contract->commission_recipient : '') }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Date') }}</label>
                        <input type="date" name="commission_date" value="{{ old('commission_date', isset($contract) ? $contract->commission_date : '') }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>

            <!-- Additional Details Section -->
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">{{ __('Additional Details') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Details') }}</label>
                        <textarea name="details" rows="4" class="w-full border rounded px-4 py-2 bg-white shadow-sm">{{ old('details', isset($contract) ? $contract->details : '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Attachment') }}</label>
                        <input type="file" name="attachment_url" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 px-6 py-3 text-gray-700 hover:bg-gray-200 transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 transition-colors">
                    {{ isset($contract) ? __('Renew Contract') : __('Save Contract') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>
</x-layouts.app>
