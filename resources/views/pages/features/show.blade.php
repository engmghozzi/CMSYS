<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Feature Details') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('View detailed information about') }} {{ $feature->display_name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('features.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Back to Features') }}
                        </a>
                        <a href="{{ route('features.edit', $feature) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Edit Feature') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature Info Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Feature Information') }}</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Name') }}:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $feature->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Display Name') }}:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $feature->display_name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Category') }}:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ __(ucfirst($feature->category)) }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Action') }}:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ __(ucfirst($feature->action ?? 'N/A')) }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Resource') }}:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ __(ucfirst($feature->resource ?? 'N/A')) }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Status') }}:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $feature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} ml-2">
                                    {{ $feature->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                            @if($feature->description)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ __('Description') }}:</span>
                                    <p class="text-sm text-gray-900 mt-1">{{ $feature->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Usage Statistics') }}</h3>
                        <div class="space-y-3">
                            @php
                                $usageStats = $feature->getUsageStats();
                            @endphp
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-sm font-medium text-blue-600">{{ __('Total Users') }}</div>
                                    <div class="text-2xl font-bold text-blue-900">{{ $usageStats['total_users'] }}</div>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <div class="text-sm font-medium text-green-600">{{ __('Total Roles') }}</div>
                                    <div class="text-2xl font-bold text-green-900">{{ $usageStats['total_roles'] }}</div>
                                </div>
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <div class="text-sm font-medium text-purple-600">{{ __('Granted Users') }}</div>
                                    <div class="text-2xl font-bold text-purple-900">{{ $usageStats['granted_users'] }}</div>
                                </div>
                                <div class="bg-orange-50 p-3 rounded-lg">
                                    <div class="text-sm font-medium text-orange-600">{{ __('Granted Roles') }}</div>
                                    <div class="text-2xl font-bold text-orange-900">{{ $usageStats['granted_roles'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note: User assignments removed - features are now role-based only -->
            <div class="bg-white rounded-lg shadow border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Feature Assignment Note') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('This feature is now managed through roles only. Users inherit permissions from their assigned role.') }}</p>
                </div>
                <div class="p-6">
                    <div class="text-center py-4">
                        <div class="text-gray-500">{{ __('To manage user permissions, assign users to appropriate roles and manage role features.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Assigned Roles -->
            <div class="bg-white rounded-lg shadow border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Assigned Roles') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Roles that have this feature assigned') }}</p>
                </div>

                <div class="p-6">
                    @if($assignedRoles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assignedRoles as $role)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $role['display_name'] }}</div>
                                                        <div class="text-sm text-gray-500">{{ $role['name'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $role['is_granted'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $role['is_granted'] ? __('Granted') : __('Revoked') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('roles.show', $role['id']) }}" 
                                                   class="text-blue-600 hover:text-blue-900">{{ __('View Role') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">{{ __('No roles have this feature assigned') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Details -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Usage Details') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Detailed breakdown of feature usage') }}</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">{{ __('User Assignments') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('Granted to Users') }}:</span>
                                    <span class="text-sm font-medium text-green-600">{{ $usageStats['granted_users'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('Revoked from Users') }}:</span>
                                    <span class="text-sm font-medium text-red-600">{{ $usageStats['revoked_users'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('Total User Assignments') }}:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $usageStats['total_users'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">{{ __('Role Assignments') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('Granted to Roles') }}:</span>
                                    <span class="text-sm font-medium text-green-600">{{ $usageStats['granted_roles'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('Revoked from Roles') }}:</span>
                                    <span class="text-sm font-medium text-red-600">{{ $usageStats['revoked_roles'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('Total Role Assignments') }}:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $usageStats['total_roles'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($feature->canBeDeleted())
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ __('Danger Zone') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('This feature can be safely deleted as it has no assignments') }}</p>
                                </div>
                                <form method="POST" action="{{ route('features.destroy', $feature) }}" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this feature? This action cannot be undone.') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        {{ __('Delete Feature') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">{{ __('Feature Cannot Be Deleted') }}</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>{{ __('This feature cannot be deleted because it is assigned to users or roles. Please remove all assignments first.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 