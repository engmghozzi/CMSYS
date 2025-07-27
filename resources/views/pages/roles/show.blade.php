<x-layouts.app :title="__('Role Details')">
    <div class="min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __($role->display_name) }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $role->name }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @if(auth()->user()->hasPermission('roles.manage'))
                            <a href="{{ route('roles.edit', $role) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Edit Role') }}
                            </a>
                        @endif
                        <a href="{{ route('roles.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Roles') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Role Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow h-full">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ __('Role Information') }}</h2>
                        
                        <div class="space-y-6 flex-1">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Display Name') }}</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ __($role->display_name) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Role Name') }}</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $role->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Description') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $role->description ?: __('No description provided') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Status') }}</label>
                                <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $role->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>

                            <div class="pt-4 border-t border-gray-100 mt-auto">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">{{ __('Created') }}</span>
                                    <span class="text-gray-900">{{ $role->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm mt-3">
                                    <span class="text-gray-500">{{ __('Last Updated') }}</span>
                                    <span class="text-gray-900">{{ $role->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow h-full">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ __('Permissions') }}</h2>
                        
                        @php
                            $grantedFeatures = $role->features->where('pivot.is_granted', true);
                            $categories = $grantedFeatures->groupBy('category');
                        @endphp

                        @if($grantedFeatures->count() > 0)
                            <div class="space-y-8 flex-1">
                                @foreach($categories as $category => $categoryFeatures)
                                    <div>
                                        <h3 class="text-md font-medium text-gray-800 mb-4 capitalize">{{ __($category) }}</h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            @foreach($categoryFeatures as $feature)
                                                <div class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900">{{ __($feature->display_name) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center justify-center h-[calc(100%-4rem)]">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-medium text-gray-900">{{ __('No Permissions') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('This role has no permissions assigned.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Users -->
            <div class="mt-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ __('Assigned Users') }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Users who have this role assigned') }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $role->users->count() }} {{ __('Users') }}
                        </span>
                    </div>
                    
                    @if($role->users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($role->users as $user)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">{{ $user->initials() }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                                </span>
                                            </div>
                                        </div>
                                        @if(auth()->user()->hasPermission('users.update'))
                                            <div>
                                                <a href="{{ route('users.edit', $user) }}" 
                                                   class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h3 class="mt-4 text-sm font-medium text-gray-900">{{ __('No Users Assigned') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('No users have this role assigned.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>