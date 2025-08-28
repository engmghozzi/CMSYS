<x-layouts.app :title="__('Edit Client')">

    <x-ui.form-layout
        :title="__('Edit Client')"
        :back-url="route('clients.show', $client)"
        :back-label="__('Back to Client')"
    >
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($client->isBlocked())
            <div class="mb-4 bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-orange-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-orange-800">{{ __('Editing Blocked Client') }}</h3>
                        <p class="text-sm text-orange-700 mt-1">
                            {{ __('You are editing a blocked client. This action is only available to administrators.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                            placeholder="{{ __('Enter full name') }}">
                </div>

                <!-- Mobile Number -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Mobile Number') }}</label>
                    <input type="text" name="mobile_number" value="{{ old('mobile_number', $client->mobile_number) }}" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                            placeholder="{{ __('Enter mobile number') }}">
                </div>

                <!-- Alternative Mobile -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Alternative Mobile') }}</label>
                    <input type="text" name="alternate_mobile_number" value="{{ old('alternate_mobile_number', $client->alternate_mobile_number) }}"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                            placeholder="{{ __('Enter alternative mobile number (optional)') }}">
                </div>

                <!-- Client Type -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Client Type') }}</label>
                    <select name="client_type" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm">
                        <option value="">{{ __('Select client type') }}</option>
                        @foreach(['Client','Company','Contractor','Other'] as $type)
                            <option value="{{ $type }}" {{ old('client_type', $client->client_type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                    <select name="status" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm">
                        <option value="">{{ __('Select status') }}</option>
                        @foreach(['ordinary','vip','blocked'] as $status)
                            <option value="{{ $status }}" {{ old('status', $client->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-8 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 {{ $client->isBlocked() ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-medium rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $client->isBlocked() ? __('Update Blocked Client') : __('Update Client') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

</x-layouts.app>
