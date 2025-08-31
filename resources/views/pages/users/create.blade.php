<x-layouts.app :title="__('Create User')">

    <x-ui.form-layout
        :title="__('Create New User')"
        :description="__('Add a new user with role and custom permissions')"
        :back-url="route('users.index')"
        :back-label="__('Back to Users')"
    >
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Enter full name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Enter email address') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Password') }}</label>
                        <input type="password" name="password" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Enter password') }}">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Confirm Password') }}</label>
                        <input type="password" name="password_confirmation" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Confirm password') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Role') }}</label>
                        <select name="role_id" id="role-select" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">{{ __('Select a role') }}</option>
                            @foreach($roles as $role)
                                @php
                                    $roleGrantedFeatures = $role->features()->wherePivot('is_granted', true)->get();
                                    $featureIds = $roleGrantedFeatures->pluck('id')->toArray();
                                @endphp
                                <option value="{{ $role->id }}"
                                        data-features='@json($featureIds)'
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ __($role->display_name) }} ({{ $roleGrantedFeatures->count() }} features)
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
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

                <!-- Role Features Preview -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Role Features') }}</label>
                    <div class="text-sm text-gray-600 mb-4">
                        {{ __('Features that come with the selected role:') }}
                    </div>
                    <div id="role-features-preview" class="bg-gray-50 rounded-lg p-4 min-h-[200px]">
                        <div class="text-center text-gray-500">
                            {{ __('Select a role to see its features') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Features Section -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Additional Permissions') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Select additional permissions beyond what the role provides. Role permissions are automatically granted.') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $featuresByCategory = $features->groupBy('category');
                    @endphp

                    @foreach($featuresByCategory as $category => $categoryFeatures)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ __($category) }}</h4>
                            <div class="space-y-2">
                                @foreach($categoryFeatures as $feature)
                                    <label class="flex items-center feature-checkbox" data-feature-id="{{ $feature->id }}">
                                        <input type="checkbox"
                                               name="features[]"
                                               value="{{ $feature->id }}"
                                               id="feature_{{ $feature->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded feature-input additional-feature">
                                        <span class="ml-2 text-sm text-gray-700">{{ __($feature->display_name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('users.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Create User') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role-select');
            const roleFeaturesPreview = document.getElementById('role-features-preview');

            const featureNames = {};
            @foreach($features as $feature)
                featureNames[{{ $feature->id }}] = '{{ __($feature->display_name) }}';
            @endforeach

            function updateRoleFeaturesPreview() {
                const selectedOption = roleSelect.options[roleSelect.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.features) {
                    const roleFeatures = JSON.parse(selectedOption.dataset.features);

                    if (roleFeatures.length > 0) {
                        roleFeaturesPreview.innerHTML = '';
                        roleFeatures.forEach(featureId => {
                            if (featureNames[featureId]) {
                                const featureSpan = document.createElement('span');
                                featureSpan.className = 'inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mr-2 mb-2';
                                featureSpan.textContent = featureNames[featureId];
                                roleFeaturesPreview.appendChild(featureSpan);
                            }
                        });

                        const noteDiv = document.createElement('div');
                        noteDiv.className = 'mt-3 text-sm text-indigo-600 font-medium';
                        noteDiv.textContent = '{{ __("These permissions will be automatically granted to the user.") }}';
                        roleFeaturesPreview.appendChild(noteDiv);
                    } else {
                        roleFeaturesPreview.innerHTML = '<div class="text-center text-gray-500">{{ __("No features assigned to this role") }}</div>';
                    }
                } else {
                    roleFeaturesPreview.innerHTML = '<div class="text-center text-gray-500">{{ __("Select a role to see its features") }}</div>';
                }
            }

            roleSelect.addEventListener('change', updateRoleFeaturesPreview);
            updateRoleFeaturesPreview();
        });
    </script>

</x-layouts.app>
