<x-layouts.app :title="__('Edit Feature')">

    <x-ui.form-layout
        :title="__('Edit Feature')"
        :description="__('Update feature information')"
        :back-url="route('features.show', $feature)"
        :back-label="__('Back to Feature')"
    >
        <!-- Edit Form -->
        <form method="POST" action="{{ route('features.update', $feature) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }}</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $feature->name) }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                           value="{{ old('display_name', $feature->display_name) }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                            <option value="{{ $category }}" {{ old('category', $feature->category) == $category ? 'selected' : '' }}>
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
                            <option value="{{ $action }}" {{ old('action', $feature->action) == $action ? 'selected' : '' }}>
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
                            <option value="{{ $resource }}" {{ old('resource', $feature->resource) == $resource ? 'selected' : '' }}>
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
                               {{ old('is_active', $feature->is_active) ? 'checked' : '' }}
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
                          placeholder="{{ __('Optional description of this feature...') }}">{{ old('description', $feature->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('features.show', $feature) }}" 
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Update Feature') }}
                </button>
            </div>
        </form>

        <!-- Usage Information -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Current Usage') }}</h3>
            <p class="text-sm text-gray-600 mb-4">{{ __('This feature is currently assigned to the following users and roles') }}</p>
            
            @php
                $usageStats = $feature->getUsageStats();
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">{{ __('User Assignments') }}</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('Total Users') }}:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $usageStats['total_users'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('Granted') }}:</span>
                            <span class="text-sm font-medium text-green-600">{{ $usageStats['granted_users'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('Revoked') }}:</span>
                            <span class="text-sm font-medium text-red-600">{{ $usageStats['revoked_users'] }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-3">{{ __('Role Assignments') }}</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('Total Roles') }}:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $usageStats['total_roles'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('Granted') }}:</span>
                            <span class="text-sm font-medium text-green-600">{{ $usageStats['granted_roles'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('Revoked') }}:</span>
                            <span class="text-sm font-medium text-red-600">{{ $usageStats['revoked_roles'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($usageStats['total_users'] > 0 || $usageStats['total_roles'] > 0)
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">{{ __('Feature in Use') }}</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ __('This feature is currently assigned to users or roles. Changes to this feature may affect their permissions.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-ui.form-layout>

</x-layouts.app> 