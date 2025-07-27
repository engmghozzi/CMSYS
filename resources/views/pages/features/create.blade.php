<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Create Feature') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Add a new feature to the system') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('features.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Back to Features') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Create Form -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Feature Information') }}</h3>
                </div>

                <form method="POST" action="{{ route('features.store') }}" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }}</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ __('e.g., users.create') }}"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Display Name') }}</label>
                            <input type="text" 
                                   id="display_name" 
                                   name="display_name" 
                                   value="{{ old('display_name') }}" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ __('e.g., Create Users') }}"
                                   required>
                            @error('display_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Category') }}</label>
                            <select id="category" 
                                    name="category" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ __(ucfirst($category)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Action') }}</label>
                            <select id="action" 
                                    name="action" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('Select Action') }}</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ old('action') == $action ? 'selected' : '' }}>
                                        {{ __(ucfirst($action)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('action')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resource" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Resource') }}</label>
                            <select id="resource" 
                                    name="resource" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('Select Resource') }}</option>
                                @foreach($resources as $resource)
                                    <option value="{{ $resource }}" {{ old('resource') == $resource ? 'selected' : '' }}>
                                        {{ __(ucfirst($resource)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('resource')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ __('Active') }}</span>
                            </label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="{{ __('Optional description of this feature...') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('features.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Create Feature') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Information -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Feature Naming Convention') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p class="mb-2">{{ __('For best results, follow this naming convention:') }}</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>{{ __('Name') }}:</strong> {{ __('Use lowercase with dots (e.g., users.create, contracts.read)') }}</li>
                                <li><strong>{{ __('Display Name') }}:</strong> {{ __('Use proper case (e.g., Create Users, Read Contracts)') }}</li>
                                <li><strong>{{ __('Category') }}:</strong> {{ __('Group related features (e.g., users, contracts, payments)') }}</li>
                                <li><strong>{{ __('Action') }}:</strong> {{ __('CRUD operations (create, read, update, delete, manage)') }}</li>
                                <li><strong>{{ __('Resource') }}:</strong> {{ __('The resource being managed (users, contracts, payments)') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 