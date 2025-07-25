<x-layouts.app :title="'Add Contract for ' . $client->name">

    <div class="max-w-5xl mx-auto bg-white p-4 md:p-6 rounded shadow-md">

        {{-- Client Info Header --}}
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('Add Contract for') }}</h2>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-gray-600">{{ __('Client Name') }}:</span>
                    <span class="font-medium">{{ $client->name }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ __('ID') }}:</span>
                    <span class="font-medium">{{ $client->id }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ __('Address') }}:</span>
                    <span class="font-medium">
                        @if($client->addresses->count() > 0)
                            {{ $client->addresses->first()->area }} - 
                            {{ $client->addresses->first()->block }} - 
                            {{ $client->addresses->first()->street }}
                            @if($client->addresses->first()->house_num) - {{ __('House') }}: {{ $client->addresses->first()->house_num }} @endif
                            @if($client->addresses->first()->floor_num) - {{ __('Floor') }}: {{ $client->addresses->first()->floor_num }} @endif
                            @if($client->addresses->first()->flat_num) - {{ __('Flat') }}: {{ $client->addresses->first()->flat_num }} @endif
                        @else
                            {{ __('No address found') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contracts.store', $client->id) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="client_id" value="{{ $client->id }}">
            @if($client->addresses->count() > 0)
                <input type="hidden" name="address_id" value="{{ $client->addresses->first()->id }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Contract Type') }}</label>
                    <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
                        <option value="">{{ __('Select Type') }}</option>
                        @foreach (['L', 'LS', 'C', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Duration') }} ({{ __('months') }})</label>
                    <input type="number" step="0.01" name="duration_months" value="{{ old('duration_months') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Total Amount') }} ({{ __('KWD') }})</label>
                    <input type="number" step="0.001" name="total_amount" value="{{ old('total_amount') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Central Machines') }}</label>
                    <input type="number" name="centeral_machines" value="{{ old('centeral_machines', 0) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Unit Machines') }}</label>
                    <input type="number" name="unit_machines" value="{{ old('unit_machines', 0) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                    <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
                        @foreach (['draft', 'signed', 'active', 'expired', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>
                                {{ __( $status ) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Commission Amount') }} ({{ __('KWD') }})</label>
                    <input type="number" step="0.001" name="commission_amount" value="{{ old('commission_amount') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Commission Type') }}</label>
                    <select name="commission_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
                        <option value="">{{ __('No commission') }}</option>
                        @foreach (['Incentive Bonus', 'Referral Commission', 'Other'] as $type)
                            <option value="{{ $type }}" {{ old('commission_type') === $type ? 'selected' : '' }}>
                                {{ __( $type ) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Commission Recipient') }}</label>
                    <input type="text" name="commission_recipient" value="{{ old('commission_recipient') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Commission Date') }}</label>
                    <input type="date" name="commission_date" value="{{ old('commission_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2" />
                </div>
            </div>

            <div class="space-y-4 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Details') }}</label>
                    <textarea name="details" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">{{ old('details') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Attachment') }}</label>
                    <input type="file" name="attachment_url"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6">
                <a href="{{ route('addresses.show', [$client->id, $client->addresses->first()->id]) }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Back') }}
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
