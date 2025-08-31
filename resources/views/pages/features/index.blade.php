<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Features & Permissions') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Manage system features and permissions') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('features.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Create Feature') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4 sm:p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="{{ __('Search features...') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }}</label>
                        <select name="category" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ __(ucfirst($category)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Resource') }}</label>
                        <select name="resource" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('All Resources') }}</option>
                            @foreach($resources as $resource)
                                <option value="{{ $resource }}" {{ request('resource') == $resource ? 'selected' : '' }}>
                                    {{ __(ucfirst($resource)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="is_active" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('is_active') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 md:col-span-4 flex justify-end space-x-3">
                        <a href="{{ route('features.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Clear') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Filter') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Features List -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Features') }} ({{ $features->total() }})</h3>
                </div>

                <div class="p-0 sm:p-2">
                    <div class="flex items-center px-4 py-2 border-b border-gray-100 bg-gray-50 rounded-t-lg text-xs font-semibold text-gray-500 uppercase tracking-wider gap-2 hidden md:flex">
                        
                        <div class="flex-1">{{ __('Feature') }}</div>
                        <div class="w-32">{{ __('Category') }}</div>
                        <div class="w-32">{{ __('Action') }}</div>
                        <div class="w-32">{{ __('Resource') }}</div>
                        <div class="w-32">{{ __('Usage') }}</div>
                        <div class="w-24">{{ __('Status') }}</div>
                        <div class="w-28">{{ __('Actions') }}</div>
                    </div>
                    @forelse($features as $feature)
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-0 border-b last:border-b-0 px-4 py-4 group hover:bg-gray-50 transition">
                           
                            <div class="flex-1 w-full md:w-auto">
                                <div class="text-sm font-medium text-gray-900">{{ __($feature->display_name) }}</div>
                                <div class="text-xs text-gray-500">{{ $feature->name }}</div>
                                @if($feature->description)
                                    <div class="text-xs text-gray-400 mt-1">{{ $feature->description }}</div>
                                @endif
                                <!-- Mobile: show meta below -->
                                <div class="flex flex-wrap gap-2 mt-2 md:hidden">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $feature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $feature->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ __(ucfirst($feature->category)) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ __(ucfirst($feature->action ?? 'N/A')) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ __(ucfirst($feature->resource ?? 'N/A')) }}
                                    </span>
                                    @php
                                        $usageStats = $feature->getUsageStats();
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ __('Users') }}: {{ $usageStats['total_users'] }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ __('Roles') }}: {{ $usageStats['total_roles'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="hidden md:block w-32 text-sm text-gray-500">
                                {{ __(ucfirst($feature->category)) }}
                            </div>
                            <div class="hidden md:block w-32 text-sm text-gray-500">
                                {{ __(ucfirst($feature->action ?? 'N/A')) }}
                            </div>
                            <div class="hidden md:block w-32 text-sm text-gray-500">
                                {{ __(ucfirst($feature->resource ?? 'N/A')) }}
                            </div>
                            <div class="hidden md:block w-32 text-sm text-gray-900">
                                @php
                                    $usageStats = $feature->getUsageStats();
                                @endphp
                                <div>{{ __('Users') }}: {{ $usageStats['total_users'] }}</div>
                                <div>{{ __('Roles') }}: {{ $usageStats['total_roles'] }}</div>
                            </div>
                            <div class="hidden md:block w-24">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $feature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $feature->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 w-full md:w-28 mt-2 md:mt-0 justify-end">
                                <a href="{{ route('features.show', $feature) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="{{ __('View') }}">
                                    <!-- Eye Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('features.edit', $feature) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="{{ __('Edit') }}">
                                    <!-- Pencil Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                    </svg>
                                </a>
                                <a href="{{ route('features.usage', $feature) }}" 
                                   class="text-green-600 hover:text-green-900" title="{{ __('Usage') }}">
                                    <!-- Users Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4.13a4 4 0 10-8 0 4 4 0 008 0z" />
                                    </svg>
                                </a>
                                @if($feature->canBeDeleted())
                                    <form method="POST" action="{{ route('features.destroy', $feature) }}" 
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this feature?') }}')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="{{ __('Delete') }}">
                                            <!-- Trash Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 0 011-1z" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">{{ __('In Use') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-gray-500">
                            {{ __('No features found') }}
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                    {{ $features->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Select all functionality
        document.getElementById('select-all-header').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.feature-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Update select all when individual checkboxes change
        document.querySelectorAll('.feature-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.feature-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.feature-checkbox:checked');
                if(document.getElementById('select-all-header')) {
                    document.getElementById('select-all-header').checked = allCheckboxes.length === checkedCheckboxes.length;
                }
            });
        });
    </script>
</x-layouts.app> 