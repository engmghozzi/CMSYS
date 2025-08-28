<x-layouts.app :title="__('New Client')">
    <x-ui.form-layout
        :title="__('Create New Client')"
        :back-url="route('clients.index')"
        :back-label="__('Back to Clients')"
    >
        <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Customer Name -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Customer Name') }}</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                           placeholder="{{ __('Enter customer name') }}">
                </div>

                <!-- Mobile Number -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Mobile Number') }}</label>
                    <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                           placeholder="{{ __('Enter mobile number') }}">
                </div>

                <!-- Alternative Mobile Number -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Alternative Mobile Number') }}</label>
                    <input type="text" name="alternate_mobile_number" value="{{ old('alternate_mobile_number') }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                           placeholder="{{ __('Enter alternative mobile number (optional)') }}">
                </div>

                <!-- Client Type -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Client Type') }}</label>
                    <select name="client_type" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm">
                        <option value="">{{ __('Select client type') }}</option>
                        <option value="Client" {{ old('client_type') == 'Client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                        <option value="Company" {{ old('client_type') == 'Company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                        <option value="Contractor" {{ old('client_type') == 'Contractor' ? 'selected' : '' }}>{{ __('Contractor') }}</option>
                        <option value="Other" {{ old('client_type') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="space-y-2 lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                    <select name="status" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm">
                        <option value="">{{ __('Select status') }}</option>
                        <option value="ordinary" {{ old('status') == 'ordinary' ? 'selected' : '' }}>{{ __('Ordinary') }}</option>
                        <option value="vip" {{ old('status') == 'vip' ? 'selected' : '' }}>{{ __('VIP') }}</option>
                        <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>{{ __('Blocked') }}</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-8 border-t border-gray-200">
                <a href="{{ route('clients.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    {{ __('Create Client') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>
</x-layouts.app>
