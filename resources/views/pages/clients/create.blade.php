<x-layouts.app :title="__('New Client')">
    <div class="min-h-screen bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Create New Client') }}</h1>
                <a href="{{ route('clients.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Clients') }}
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">{{ __('There were some errors with your submission') }}</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Customer Name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Enter customer name') }}">
                        </div>

                        <!-- Mobile Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Mobile Number') }}</label>
                            <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Enter mobile number') }}">
                        </div>

                        <!-- Alternative Mobile Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Alternative Mobile Number') }}</label>
                            <input type="text" name="alternate_mobile_number" value="{{ old('alternate_mobile_number') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Enter alternative mobile number (optional)') }}">
                        </div>

                        <!-- Client Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Client Type') }}</label>
                            <select name="client_type" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">{{ __('Select client type') }}</option>
                                <option value="Client" {{ old('client_type') == 'Client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                                <option value="Company" {{ old('client_type') == 'Company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                                <option value="Contractor" {{ old('client_type') == 'Contractor' ? 'selected' : '' }}>{{ __('Contractor') }}</option>
                                <option value="Other" {{ old('client_type') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                            <select name="status" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">{{ __('Select status') }}</option>
                                <option value="ordinary" {{ old('status') == 'ordinary' ? 'selected' : '' }}>{{ __('Ordinary') }}</option>
                                <option value="vip" {{ old('status') == 'vip' ? 'selected' : '' }}>{{ __('VIP') }}</option>
                                <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>{{ __('Blocked') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('clients.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Create Client') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
