<x-layouts.app :title="__('Edit User')">

    <div class="min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Edit User') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Update user information and permissions') }}</p>
                    </div>
                <a href="{{ route('users.show', $user) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to User') }}
                </a>
                    </div>
                </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <form method="POST" action="{{ route('users.update', $user->id) }}" class="p-6">
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
                                                data-features="{{ json_encode($featureIds) }}"
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ __($role->display_name) }}
                                            ({{ $roleGrantedFeatures->count() }} features)
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

                    <!-- Current User Features -->
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Current User Permissions') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Permissions currently assigned to this user:') }}</p>
                        </div>
                        
                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                @php
                                    $currentRole = $user->role;
                                    $roleGrantedFeatures = $currentRole ? $currentRole->features()->wherePivot('is_granted', true)->get() : collect();
                                    $additionalGrantedFeatures = $userGrantedFeatures->whereNotIn('id', $roleGrantedFeatures->pluck('id'));
                                @endphp
                                
                                @if($roleGrantedFeatures->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-indigo-800 mb-2">{{ __('Role Permissions') }}</h4>
                                        <p class="text-xs text-indigo-600 mb-2">{{ __('Automatically granted by role') }}</p>
                                        <div class="space-y-1">
                                            @foreach($roleGrantedFeatures as $feature)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ __($feature->display_name) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if($additionalGrantedFeatures->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-green-800 mb-2">{{ __('Additional Permissions') }}</h4>
                                        <p class="text-xs text-green-600 mb-2">{{ __('Manually granted beyond role') }}</p>
                                        <div class="space-y-1">
                                            @foreach($additionalGrantedFeatures as $feature)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{ __($feature->display_name) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if($userRevokedFeatures->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-red-800 mb-2">{{ __('Revoked Permissions') }}</h4>
                                        <p class="text-xs text-red-600 mb-2">{{ __('Explicitly revoked') }}</p>
                                        <div class="space-y-1">
                                            @foreach($userRevokedFeatures as $feature)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    {{ __($feature->display_name) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if($userGrantedFeatures->count() == 0 && $userRevokedFeatures->count() == 0)
                                    <div class="text-gray-500 text-sm">
                                        {{ __('No custom permissions set. User will inherit all role permissions.') }}
                                    </div>
                                @endif
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
                                $userGrantedFeatureIds = $userGrantedFeatures->pluck('id')->toArray();
                                $userRevokedFeatureIds = $userRevokedFeatures->pluck('id')->toArray();
                                $roleGrantedFeatureIds = $roleGrantedFeatures->pluck('id')->toArray();
                            @endphp
                            
                            @foreach($featuresByCategory as $category => $categoryFeatures)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ __($category) }}</h4>
                                    <div class="space-y-2">
                                        @foreach($categoryFeatures as $feature)
                                            @php
                                                $isRoleGranted = in_array($feature->id, $roleGrantedFeatureIds);
                                                $isUserGranted = in_array($feature->id, $userGrantedFeatureIds);
                                                $isRevoked = in_array($feature->id, $userRevokedFeatureIds);
                                                $isChecked = $isUserGranted && !$isRoleGranted; // Only show as checked if user granted it beyond role
                                            @endphp
                                            <label class="flex items-center feature-checkbox" data-feature-id="{{ $feature->id }}">
                                                <input type="checkbox" 
                                                       name="features[]" 
                                                       value="{{ $feature->id }}"
                                                       id="feature_{{ $feature->id }}"
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded feature-input additional-feature">
                                                <span class="ml-2 text-sm text-gray-700">
                                                    {{ __($feature->display_name) }}
                                                    @if($isRoleGranted)
                                                        <span class="text-xs text-indigo-600">({{ __('Role granted') }})</span>
                                                    @endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
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
            </div>
        </div>
    </div>

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
