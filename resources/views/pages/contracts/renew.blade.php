<x-layouts.app>
    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Renew Contract') }}</h1>
                <p class="text-gray-600">{{ __('Renewing contract') }}: {{ $contract->contract_num }}</p>
                <p class="text-sm text-gray-500">{{ __('Address') }}: {{ $contract->address->area }}, {{ $contract->address->block }}, {{ $contract->address->street }}</p>
            </div>

                         <form action="{{ route('contracts.renew.store', [$client, $contract]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contract Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contract Type') }}</label>
                        <select name="type" class="w-full border rounded px-4 py-2 bg-white shadow-sm" required>
                            <option value="L" {{ old('type') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="LS" {{ old('type') == 'LS' ? 'selected' : '' }}>LS</option>
                            <option value="C" {{ old('type') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Central Machines -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Central Machines') }}</label>
                        <input type="number" name="centeral_machines" value="{{ old('centeral_machines', $contract->centeral_machines) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" min="0" required>
                        @error('centeral_machines')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Machines -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Unit Machines') }}</label>
                        <input type="number" name="unit_machines" value="{{ old('unit_machines', $contract->unit_machines) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" min="0" required>
                        @error('unit_machines')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" required>
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration (Months) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Duration (Months)') }}</label>
                        <input type="number" name="duration_months" value="{{ old('duration_months', $contract->duration_months) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" min="1" required>
                        @error('duration_months')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Total Amount') }}</label>
                        <input type="number" name="total_amount" value="{{ old('total_amount', $contract->total_amount) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" min="0" step="0.01" required>
                        @error('total_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commission Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Amount') }}</label>
                        <input type="number" name="commission_amount" value="{{ old('commission_amount', $contract->commission_amount) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" min="0" step="0.01">
                        @error('commission_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commission Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Type') }}</label>
                        <select name="commission_type" class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                            <option value="">{{ __('Select Commission Type') }}</option>
                            <option value="Incentive Bonus" {{ old('commission_type') == 'Incentive Bonus' ? 'selected' : '' }}>Incentive Bonus</option>
                            <option value="Referral Commission" {{ old('commission_type') == 'Referral Commission' ? 'selected' : '' }}>Referral Commission</option>
                            <option value="Other" {{ old('commission_type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('commission_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commission Recipient -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Recipient') }}</label>
                        <input type="text" name="commission_recipient" value="{{ old('commission_recipient', $contract->commission_recipient) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm" maxlength="255">
                        @error('commission_recipient')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commission Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Date') }}</label>
                        <input type="date" name="commission_date" value="{{ old('commission_date', $contract->commission_date) }}" 
                               class="w-full border rounded px-4 py-2 bg-white shadow-sm">
                        @error('commission_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Details -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Details') }}</label>
                    <textarea name="details" rows="4" class="w-full border rounded px-4 py-2 bg-white shadow-sm">{{ old('details', $contract->details) }}</textarea>
                    @error('details')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachment -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Attachment (Optional)') }}</label>
                    <input type="file" name="attachment_url" class="w-full border rounded px-4 py-2 bg-white shadow-sm" accept=".pdf" />
                    <p class="text-sm text-gray-500 mt-1">{{ __('Only PDF files are allowed, max size 2MB') }}</p>
                    @error('attachment_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <a href="{{ route('contracts.show', [$client, $contract]) }}"
                       class="inline-flex items-center gap-2 rounded bg-gray-500 px-3 py-2 text-white hover:bg-gray-600">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">
                        {{ __('Renew Contract') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
