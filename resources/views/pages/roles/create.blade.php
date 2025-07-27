<x-layouts.app :title="__('Create Role')">

    <div class="min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Create New Role') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Add a new role with specific permissions') }}</p>
                    </div>
                    <a href="{{ route('roles.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back to Roles') }}
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <form method="POST" action="{{ route('roles.store') }}" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Role Name') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="{{ __('e.g., manager, supervisor') }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Display Name') }}</label>
                                <input type="text" name="display_name" value="{{ old('display_name') }}" required
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="{{ __('e.g., Manager, Supervisor') }}">
                                @error('display_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                                <textarea name="description" rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                          placeholder="{{ __('Describe the role and its responsibilities') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label class="ml-2 text-sm text-gray-700">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Permissions') }}</label>
                            <div class="text-sm text-gray-600 mb-4">
                                {{ __('Select the features this role should have access to:') }}
                            </div>
                        </div>
                    </div>

                    <!-- Features Selection -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Feature Permissions') }}</h3>
                        
                        @php
                            $categories = $features->groupBy('category');
                        @endphp

                        @foreach($categories as $category => $categoryFeatures)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-800 mb-3 capitalize">{{ __($category) }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($categoryFeatures as $feature)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="features[]" value="{{ $feature->id }}" 
                                                   id="feature_{{ $feature->id }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="feature_{{ $feature->id }}" class="ml-2 text-sm text-gray-700">
                                                {{ __($feature->display_name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('roles.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Create Role') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.app> 