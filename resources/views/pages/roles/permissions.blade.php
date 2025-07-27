<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Role Permissions') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Manage permissions for role') }}: {{ $role->display_name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('roles.show', $role) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Back to Role') }}
                        </a>

                    </div>
                </div>
            </div>

            <!-- Role Info Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Role Information') }}</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Name') }}:</span>
                                <span class="text-sm text-gray-900">{{ $role->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Display Name') }}:</span>
                                <span class="text-sm text-gray-900">{{ $role->display_name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Status') }}:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $role->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                            @if($role->description)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ __('Description') }}:</span>
                                    <span class="text-sm text-gray-900">{{ $role->description }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Permission Summary') }}</h3>
                        <div class="space-y-2">
                            @php
                                $totalPermissions = $effectivePermissions->count();
                                $grantedPermissions = $effectivePermissions->where('has_permission', true)->count();
                                $deniedPermissions = $totalPermissions - $grantedPermissions;
                                $assignedUsers = $role->getUsersCount();
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
                                <span class="text-sm font-medium text-gray-500">{{ __('Assigned Users') }}:</span>
                                <span class="text-sm text-blue-600 font-medium">{{ $assignedUsers }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Quick Actions') }}</h3>
                        <div class="space-y-2">
                            <a href="{{ route('roles.users', $role) }}" 
                               class="block w-full inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm">
                                {{ __('View Assigned Users') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Management -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Permission Management') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Select permissions to grant to this role. Users with this role will inherit these permissions.') }}</p>
                </div>

                <form method="POST" action="{{ route('roles.permissions.update', $role) }}">
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
                                            @endphp
                                            <label class="flex items-center p-2 rounded hover:bg-gray-100">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $feature->id }}"
                                                       id="permission_{{ $feature->id }}"
                                                       {{ $hasPermission ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-gray-700">{{ __($feature->display_name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('roles.show', $role) }}" 
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
                    <p class="mt-1 text-sm text-gray-600">{{ __('Detailed breakdown of all permissions for this role.') }}</p>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Permission') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Action') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Resource') }}</th>
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
                                            {{ __(ucfirst($permission['action'] ?? 'N/A')) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __(ucfirst($permission['resource'] ?? 'N/A')) }}
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


</x-layouts.app> 