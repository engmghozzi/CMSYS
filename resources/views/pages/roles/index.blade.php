<x-layouts.app :title="__('Roles')">
    <div class="min-h-screen px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Roles') }}</h1>
                <p class="mt-2 text-sm text-gray-600">{{ __('Manage system roles and permissions') }}</p>
            </div>

            @if(auth()->user()->hasPermission('roles.manage'))
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('roles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="h-4 w-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('New Role') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Search & Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <form method="GET" action="{{ route('roles.index') }}" class="space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">{{ __('Search') }}</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2"
                               placeholder="{{ __('Search roles...') }}">
                    </div>

                    <div class="sm:w-48">
                        <label for="status" class="sr-only">{{ __('Status') }}</label>
                        <select name="is_active" 
                                id="status"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                            <option value="all" {{ request('is_active', 'all') == 'all' ? 'selected' : '' }}>{{ __('All Roles') }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('Active Roles') }}</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('Inactive Roles') }}</option>
                        </select>
                    </div>

                    <div class="sm:w-auto">
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ __('Search') }}
                        </button>
                    </div>
                    <div class="sm:w-auto">
                        <a href="{{ route('roles.index') }}"
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Clear') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Roles List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <div class="divide-y divide-gray-200">
                    <!-- Header -->
                    <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="col-span-3">{{ __('Role') }}</div>
                        <div class="col-span-3 hidden md:block">{{ __('Description') }}</div>
                        <div class="col-span-2">{{ __('Users') }}</div>
                        <div class="col-span-2">{{ __('Status') }}</div>
                        <div class="col-span-2 text-right">{{ __('Actions') }}</div>
                    </div>

                    <!-- Role Cards -->
                    @forelse($roles as $role)
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50">
                            <div class="col-span-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ __($role->display_name) }}</div>
                                        <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 hidden md:block">
                                <div class="text-sm text-gray-900">{{ Str::limit($role->description, 50) }}</div>
                            </div>
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $role->users_count }} {{ __('users') }}
                                </span>
                            </div>
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $role->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                            <div class="col-span-2 text-right text-sm font-medium">
                                @if(auth()->user()->hasPermission('roles.manage'))
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('roles.show', $role) }}" 
                                           class="text-blue-600 hover:text-blue-900">{{ __('View') }}</a>
                                        <a href="{{ route('roles.edit', $role) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                        @if($role->users_count == 0 && $role->canBeDeleted())
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('{{ __('Are you sure you want to delete this role? This action cannot be undone.') }}')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            {{ __('No roles found') }}
                        </div>
                    @endforelse
                </div>
            </div>

            @if($roles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>