<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Role Users') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Users assigned to role') }}: {{ $role->display_name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('roles.show', $role) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Back to Role') }}
                        </a>
                        <a href="{{ route('roles.permissions', $role) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Manage Permissions') }}
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('User Summary') }}</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Total Users') }}:</span>
                                <span class="text-sm text-gray-900">{{ $users->total() }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Active Users') }}:</span>
                                <span class="text-sm text-green-600 font-medium">{{ $users->where('is_active', true)->count() }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">{{ __('Inactive Users') }}:</span>
                                <span class="text-sm text-red-600 font-medium">{{ $users->where('is_active', false)->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Quick Actions') }}</h3>
                        <div class="space-y-2">
                            <a href="{{ route('users.create') }}" 
                               class="block w-full inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm">
                                {{ __('Create New User') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users List -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Assigned Users') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Users who have this role assigned') }}</p>
                </div>

                <div class="p-6">
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Permission Overrides') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">{{ $user->name[0] ?? 'U' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @php
                                                    $overrides = $user->features()->count();
                                                @endphp
                                                @if($overrides > 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $overrides }} {{ __('overrides') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">{{ __('None') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('users.show', $user) }}" 
                                                       class="text-blue-600 hover:text-blue-900">{{ __('View') }}</a>
                                                    <a href="{{ route('users.edit', $user) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                    <a href="{{ route('users.permissions', $user) }}" 
                                                       class="text-green-600 hover:text-green-900">{{ __('Permissions') }}</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">{{ __('No users are assigned to this role') }}</div>
                            <div class="mt-4">
                                <a href="{{ route('users.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Create First User') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Role Management Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Role Management') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ __('Users with this role will inherit all permissions assigned to the role. Individual users can have permission overrides that take precedence over role permissions.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 