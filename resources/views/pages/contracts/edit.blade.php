<x-layouts.app :title="'Edit Contract for ' . $client->name">

    <x-ui.form-layout
        :title="__('Edit Contract') . ' # ' . $contract->contract_num"
        :description="__('Client') . ': ' . $client->name"
        :back-url="route('contracts.show', [$client, $contract])"
        :back-label="__('Back to Contract')"
    >
        <form method="POST" action="{{ route('contracts.update', [$client->id, $contract->id]) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="address_id" value="{{ $contract->address_id }}">

            <!-- Basic Contract Information Section -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-gray-900">{{ __('Contract Information') }}</h2>
                
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
                        <select name="type" required class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach (['L', 'LS', 'C', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('type', $contract->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Duration')}} ({{__('months')}})</label>
                        <input type="number" step="0.01" name="duration_months" value="{{ old('duration_months', $contract->duration_months) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
            </div>



            <!-- Financial Information Section -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-gray-900">{{ __('Financial Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Total Amount') }}</label>
                        <input type="number" step="0.001" name="total_amount" value="{{ old('total_amount', $contract->total_amount) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach (['active', 'expired', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $contract->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Commission Section -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-gray-900">{{ __('Commission') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Amount') }}</label>
                        <input type="number" step="0.001" name="commission_amount" value="{{ old('commission_amount', $contract->commission_amount) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Type') }}</label>
                        <select name="commission_type" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                        <input type="text" name="commission_recipient" value="{{ old('commission_recipient', $contract->commission_recipient) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Commission Date') }}</label>
                        <input type="date" name="commission_date" value="{{ old('commission_date', $contract->commission_date) }}" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
            </div>

            <!-- Additional Details Section -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-gray-900">{{ __('Additional Details') }}</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Details') }}</label>
                    <textarea name="details" rows="4" class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('details', $contract->details) }}</textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Attachment') }} ({{ __('Upload to replace') }})</label>
                    <input type="file" name="attachment_url" class="w-full border rounded px-4 py-2 bg-white shadow-sm" />
                    
                    <!-- Always include the hidden field -->
                    <input type="hidden" name="delete_attachment" id="delete_attachment" value="0">
                    
                    @if($contract->attachment_url)
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border" id="attachment-display">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $contract->attachment_file_name ?? 'Document' }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ $contract->attachment_url }}" class="text-blue-500 hover:text-blue-700 text-sm underline" target="_blank">
                                        {{ __('View') }}
                                    </a>
                                    <button type="button" 
                                            onclick="deleteAttachment()" 
                                            class="text-red-500 hover:text-red-700 text-sm underline">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('contracts.show', [$client, $contract]) }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

    <script>
        function deleteAttachment() {
            if (confirm('{{ __("Are you sure you want to delete this attachment?") }}')) {
                document.getElementById('delete_attachment').value = '1';
                // Hide the attachment display
                const attachmentDisplay = document.getElementById('attachment-display');
                if (attachmentDisplay) {
                    attachmentDisplay.style.display = 'none';
                }
            }
        }
    </script>

</x-layouts.app>
