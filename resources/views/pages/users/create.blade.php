<x-layouts.app :title="__('New User')">
    <div class="min-h-screen bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Create New User') }}</h1>
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Users') }}
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
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Full Name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Enter full name') }}">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Enter email address') }}">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Password') }}</label>
                            <input type="password" name="password" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Enter password') }}">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="{{ __('Confirm password') }}">
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Role') }}</label>
                            <select name="role_id" id="role-select" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">{{ __('Select a role') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            data-features="{{ $role->features->pluck('id')->toJson() }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ __($role->display_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
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
                    <div id="role-features-preview" class="border-t border-gray-200 pt-6" style="display: none;">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Role Features Preview') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Features that will be inherited from the selected role') }}</p>
                        </div>
                        <div id="role-features-list" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('User Features') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Role features are automatically selected. You can add or remove additional features for this user.') }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $featuresByCategory = $features->groupBy('category');
                            @endphp
                            
                            @foreach($featuresByCategory as $category => $categoryFeatures)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ $category }}</h4>
                                    <div class="space-y-2">
                                        @foreach($categoryFeatures as $feature)
                                            <label class="flex items-center feature-checkbox" data-feature-id="{{ $feature->id }}">
                                                <input type="checkbox" 
                                                       name="features[]" 
                                                       value="{{ $feature->id }}"
                                                       {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded feature-input">
                                                <span class="ml-2 text-sm text-gray-700">{{ $feature->display_name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Create User') }}
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
            const roleFeaturesList = document.getElementById('role-features-list');
            const featureCheckboxes = document.querySelectorAll('.feature-checkbox');
            const featureInputs = document.querySelectorAll('.feature-input');

            // Store feature names for display
            const featureNames = {};
            @foreach($features as $feature)
                featureNames[{{ $feature->id }}] = '{{ $feature->display_name }}';
            @endforeach

            // Track current role features
            let currentRoleFeatures = [];

            function updateRoleFeaturesPreview() {
                const selectedOption = roleSelect.options[roleSelect.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.features) {
                    currentRoleFeatures = JSON.parse(selectedOption.dataset.features);
                    
                    if (currentRoleFeatures.length > 0) {
                        roleFeaturesList.innerHTML = '';
                        currentRoleFeatures.forEach(featureId => {
                            if (featureNames[featureId]) {
                                const featureSpan = document.createElement('span');
                                featureSpan.className = 'inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-indigo-100 text-indigo-800';
                                featureSpan.innerHTML = `
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    ${featureNames[featureId]}
                                `;
                                roleFeaturesList.appendChild(featureSpan);
                            }
                        });
                        roleFeaturesPreview.style.display = 'block';
                    } else {
                        roleFeaturesPreview.style.display = 'none';
                    }
                } else {
                    currentRoleFeatures = [];
                    roleFeaturesPreview.style.display = 'none';
                }
            }

            function autoSelectRoleFeatures() {
                const selectedOption = roleSelect.options[roleSelect.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.features) {
                    const roleFeatures = JSON.parse(selectedOption.dataset.features);
                    
                    // Check the role features
                    roleFeatures.forEach(featureId => {
                        const checkbox = document.querySelector(`input[value="${featureId}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                }
            }

            function handleFeatureSelection(event) {
                // Allow all feature selections and unselections
                // No restrictions - users can freely select/unselect any feature
                return true;
            }

            // Update preview when role changes
            roleSelect.addEventListener('change', function() {
                updateRoleFeaturesPreview();
                autoSelectRoleFeatures();
            });

            // Handle manual feature selection
            featureInputs.forEach(input => {
                input.addEventListener('change', handleFeatureSelection);
            });

            // Initialize preview and auto-select
            updateRoleFeaturesPreview();
            autoSelectRoleFeatures();
        });
    </script>
</x-layouts.app>
