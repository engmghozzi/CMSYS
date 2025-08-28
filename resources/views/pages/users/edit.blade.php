<x-layouts.app :title="__('Edit User')">

    <x-ui.form-layout
        :title="__('Edit User')"
        :description="__('Update user information and permissions')"
        :back-url="route('users.show', $user)"
        :back-label="__('Back to User')"
    >
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Enter full name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Enter email address') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Password') }}</label>
                        <input type="password" name="password"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Leave blank to keep current password') }}">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Confirm Password') }}</label>
                        <input type="password" name="password_confirmation"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="{{ __('Confirm new password') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Role') }}</label>
                        <select name="role_id" id="role-select" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">{{ __('Select a role') }}</option>
                            @foreach($roles as $role)
                                @php
                                    $roleGrantedFeatures = $role->features->where('pivot.is_granted', true);
                                    $featureIds = $roleGrantedFeatures->pluck('id')->toArray();
                                @endphp
                                <option value="{{ $role->id }}" 
                                        data-features='@json($featureIds)'
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
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

            <!-- Current Permissions Overview -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Current Permissions') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Permissions are inherited from the user\'s assigned role. To change permissions, assign the user to a different role or modify the role\'s features.') }}</p>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    @php
                        $currentRole = $user->role;
                        $roleGrantedFeatures = $currentRole ? $currentRole->features()->wherePivot('is_granted', true)->get() : collect();
                    @endphp
                    
                    @if($roleGrantedFeatures->count() > 0)
                        <div>
                            <h4 class="font-medium text-indigo-800 mb-2">{{ __('Role Permissions') }}</h4>
                            <p class="text-xs text-indigo-600 mb-2">{{ __('Automatically granted by role:') }} <strong>{{ $currentRole->display_name ?? 'No Role' }}</strong></p>
                            <div class="space-y-1">
                                @foreach($roleGrantedFeatures as $feature)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ __($feature->display_name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-gray-500 text-sm">
                            {{ __('No permissions assigned through role.') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Note about role-based permissions -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-yellow-800">{{ __('Permission System Note') }}</h4>
                    </div>
                    <p class="text-sm text-yellow-700">{{ __('User permissions are now managed through roles only. To change user permissions, assign them to a different role or modify the role\'s features in the role management section.') }}</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('users.show', $user) }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Update User') }}
                </button>
            </div>
        </form>
    </x-ui.form-layout>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role-select');
            const roleFeaturesPreview = document.getElementById('role-features-preview');

            // Store feature names for display
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
                        
                        // Add note about automatic granting
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

            // Update preview when role changes
            roleSelect.addEventListener('change', updateRoleFeaturesPreview);

            // Initial update
            updateRoleFeaturesPreview();
        });
    </script>

</x-layouts.app>
