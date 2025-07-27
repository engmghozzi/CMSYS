<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('User Permissions') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Manage permissions for') }} {{ $user->name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('users.show', $user) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Back to User') }}
                        </a>
                        <button onclick="testPermissions()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Test Permissions') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('User Information') }}</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Name') }}:</span>
                                <span class="text-sm text-gray-900">{{ $user->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Email') }}:</span>
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Role') }}:</span>
                                <span class="text-sm text-gray-900">{{ $user->role ? $user->role->display_name : __('No role assigned') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Status') }}:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Permission Summary') }}</h3>
                        <div class="space-y-2">
                            @php
                                $totalPermissions = $effectivePermissions->count();
                                $grantedPermissions = $effectivePermissions->where('has_permission', true)->count();
                                $deniedPermissions = $totalPermissions - $grantedPermissions;
                                $userOverrides = $user->features()->count();
                            @endphp
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Total Permissions') }}:</span>
                                <span class="text-sm text-gray-900">{{ $totalPermissions }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Granted') }}:</span>
                                <span class="text-sm text-green-600 font-medium">{{ $grantedPermissions }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Denied') }}:</span>
                                <span class="text-sm text-red-600 font-medium">{{ $deniedPermissions }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('User Overrides') }}:</span>
                                <span class="text-sm text-blue-600 font-medium">{{ $userOverrides }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Quick Actions') }}</h3>
                        <div class="space-y-2">
                            <form method="POST" action="{{ route('users.permissions.clear', $user) }}" 
                                  onsubmit="return confirm('{{ __('Are you sure you want to clear all user permission overrides? This will make the user inherit all role permissions.') }}')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 text-sm">
                                    {{ __('Clear Overrides') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Management -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Permission Management') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Override role permissions or add additional permissions for this user.') }}</p>
                </div>

                <form method="POST" action="{{ route('users.permissions.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $featuresByCategory = $features->groupBy('category');
                            @endphp
                            
                            @foreach($featuresByCategory as $category => $categoryFeatures)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ __($category) }}</h4>
                                    <div class="space-y-2">
                                        @foreach($categoryFeatures as $feature)
                                            @php
                                                $effectivePermission = $effectivePermissions->where('name', $feature->name)->first();
                                                $hasPermission = $effectivePermission ? $effectivePermission['has_permission'] : false;
                                                $source = $effectivePermission ? $effectivePermission['source'] : 'none';
                                                $isUserOverride = $user->features()->where('features.id', $feature->id)->exists();
                                            @endphp
                                            <label class="flex items-center justify-between p-2 rounded hover:bg-gray-100">
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $feature->id }}"
                                                           id="permission_{{ $feature->id }}"
                                                           {{ $hasPermission ? 'checked' : '' }}
                                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-gray-700">{{ __($feature->display_name) }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @if($isUserOverride)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ __('Override') }}
                                                        </span>
                                                    @endif
                                                    @if($source === 'role_inherited')
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            {{ __('Role') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('users.show', $user) }}" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Update Permissions') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Permission Details -->
            <div class="mt-6 bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Permission Details') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Detailed breakdown of all permissions and their sources.') }}</p>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Permission') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Source') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($effectivePermissions as $permission)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ __($permission['display_name']) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __(ucfirst($permission['category'])) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($permission['has_permission'])
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ __('Granted') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ __('Denied') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @switch($permission['source'])
                                                @case('user_granted')
                                                    <span class="text-blue-600">{{ __('User Override (Granted)') }}</span>
                                                    @break
                                                @case('user_revoked')
                                                    <span class="text-red-600">{{ __('User Override (Revoked)') }}</span>
                                                    @break
                                                @case('role_inherited')
                                                    <span class="text-green-600">{{ __('Role Inherited') }}</span>
                                                    @break
                                                @default
                                                    <span class="text-gray-500">{{ __('None') }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testPermissions() {
            fetch('{{ route("users.test-permissions", $user) }}')
                .then(response => response.json())
                .then(data => {
                    console.log('Permission Test Results:', data);
                    alert('Permission test completed. Check console for results.');
                })
                .catch(error => {
                    console.error('Error testing permissions:', error);
                    alert('Error testing permissions. Check console for details.');
                });
        }
    </script>
</x-layouts.app> 