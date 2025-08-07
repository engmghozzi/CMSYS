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
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <form method="GET" action="{{ route('roles.index') }}" class="items-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">{{ __('Search') }}</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               class="w-full h-11 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4"
                               placeholder="{{ __('Search in role name or description...') }}">
                    </div>

                    <div class="sm:w-48">
                        <label for="status" class="sr-only">{{ __('Status') }}</label>
                        <select name="is_active" 
                                id="status"
                                class="w-full h-11 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4">
                            <option value="all" {{ request('is_active', 'all') == 'all' ? 'selected' : '' }}>{{ __('All Roles') }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('Active Roles') }}</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('Inactive Roles') }}</option>
                        </select>
                    </div>

                    <div class="sm:w-48">
                        <label for="sort" class="sr-only">{{ __('Sort By') }}</label>
                        <select name="sort" 
                                id="sort"
                                class="w-full h-11 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4">
                            <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                            <option value="display_name" {{ request('sort') == 'display_name' ? 'selected' : '' }}>{{ __('Display Name') }}</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Created Date') }}</option>
                            <option value="users_count" {{ request('sort') == 'users_count' ? 'selected' : '' }}>{{ __('Users Count') }}</option>
                            <option value="features_count" {{ request('sort') == 'features_count' ? 'selected' : '' }}>{{ __('Permissions Count') }}</option>
                        </select>
                    </div>

                    <div class="sm:w-32">
                        <label for="order" class="sr-only">{{ __('Order') }}</label>
                        <select name="order" 
                                id="order"
                                class="w-full h-11 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4">
                            <option value="asc" {{ request('order', 'asc') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}</option>
                            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ __('Search') }}
                        </button>

                        <a 
                            href="{{ route('roles.index') }}" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                            </svg>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Roles List -->
        <div class="space-y-3">
            @forelse($roles as $role)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <!-- Role Info -->
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ __($role->display_name) }}</h3>
                                    <p class="text-xs text-gray-500">{{ $role->name }}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="flex-1 mx-4">
                                @if($role->description)
                                    <p class="text-sm text-gray-600">{{ Str::limit($role->description, 100) }}</p>
                                @else
                                    <p class="text-sm text-gray-400">{{ __('No description') }}</p>
                                @endif
                            </div>

                            <!-- Status -->
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $role->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">{{ __('Users') }}:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->users_count }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">{{ __('Permissions') }}:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $role->features_count ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons - At the end of the card -->
                        <div class="flex items-center justify-end space-x-2 mt-4 pt-4 border-t border-gray-100">
                            <!-- View Button -->
                            <a href="{{ route('roles.show', $role) }}" 
                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="{{ __('View Role') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>        
                            
                            <!-- Edit Button -->
                            <a href="{{ route('roles.edit', $role) }}"
                                class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                title="{{ __('Edit Role') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                </svg>
                            </a>
                            
                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                onsubmit="return confirm('{{ __('Are you sure you want to delete this role? This action cannot be undone.') }}');"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="{{ __('Delete Role') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m4-12v1H6V5a1 1 0 011-1h10a1 1 0 011 1z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No roles found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Get started by creating a new role.') }}</p>
                    @if(auth()->user()->hasPermission('roles.manage'))
                        <div class="mt-6">
                            <a href="{{ route('roles.create') }}" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('New Role') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($roles->hasPages())
            <div class="mt-8">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>