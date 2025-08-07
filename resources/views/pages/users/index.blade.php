<x-layouts.app :title="__('Users')">

    <div class="min-h-screen">
        <!-- Add User Button -->
        @if(auth()->user()->hasPermission('users.create'))
            <div class="flex mb-4 {{ app()->getLocale() === 'ar' ? 'justify-end' : 'justify-start' }}">
                <a 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors
                    {{ app()->getLocale() === 'ar' ? 'flex-row-reverse content-start' : 'flex-row content-start' }}"
                    href="{{ route('users.create') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('New User') }}
                </a>
            </div>
        @endif
        
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <!-- Name Search -->
                    <div class="md:col-span-2">
                        <div class="flex items-center w-full border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} h-11">
                            <span class="flex items-center px-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="search"
                                placeholder="{{ __('Search by Name') }}"
                                class="flex-1 bg-transparent border-0 focus:ring-0 h-11 {{ app()->getLocale() === 'ar' ? 'text-right placeholder:text-right pr-2' : 'text-left placeholder:text-left pl-2' }}"
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>
                    
                    <!-- Email Search -->
                    <div class="md:col-span-1">
                        <div class="flex items-center w-full border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-colors {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} h-11">
                            <span class="flex items-center px-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                placeholder="{{ __('Search by Email') }}"
                                class="flex-1 bg-transparent border-0 focus:ring-0 h-11 {{ app()->getLocale() === 'ar' ? 'text-right placeholder:text-right pr-2' : 'text-left placeholder:text-left pl-2' }}"
                                value="{{ request('email') }}"
                            >
                        </div>
                    </div>
                    
                    <!-- Role Search -->
                    <div class="md:col-span-1">
                        <select name="role" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="all" {{ request('role', 'all') == 'all' ? 'selected' : '' }}>{{ __('All Roles') }}</option>
                            @foreach(\App\Models\Role::active()->get() as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ __($role->display_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Search -->
                    <div class="md:col-span-1">
                        <select name="is_active" class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="all" {{ request('is_active', 'all') == 'all' ? 'selected' : '' }}>{{ __('All Users') }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('Active Users') }}</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('Inactive Users') }}</option>
                        </select>
                    </div>
                    
                    <!-- Search Buttons -->
                    <div class="flex gap-2 items-center justify-center md:col-span-1">
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
                            href="{{ route('users.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                            </svg>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4">
            <div class="flex flex-col space-y-3">
                @forelse ($users as $user)
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-3 flex items-center justify-between">
                            <!-- Left Side - Main Info -->
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shrink-0">
                                    #{{ $user->id }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-medium text-gray-900 truncate">{{ $user->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $user->email }}
                                        </span>
                                        @if($user->role)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium @if($user->role->name === 'super_admin') bg-red-100 text-red-800 @elseif($user->role->name === 'admin') bg-purple-100 text-purple-800 @elseif($user->role->name === 'employee') bg-blue-100 text-blue-800 @elseif($user->role->name === 'accountant') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                                {{ __($user->role->display_name) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ __('No Role') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Middle - Status -->
                            <div class="hidden md:flex flex-col space-y-1 flex-grow px-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    @if ($user->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Right Side - Actions -->
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission('users.read'))
                                        <a href="{{ route('users.show', $user) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-full transition-colors"
                                            title="{{ __('View') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('users.update'))
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-full transition-colors"
                                            title="{{ __('Edit') }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                            </svg>
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('users.delete'))
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                            onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-full transition-colors"
                                                title="{{ __('Delete') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No users found') }}</h3>
                        <p class="text-gray-500">{{ __('Get started by creating a new user.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts.app>
