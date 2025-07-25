<x-layouts.app :title="__('System Logs')">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('System Logs') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('View all system activities and user actions') }}</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Filters') }}</h3>
            <form method="GET" action="{{ route('logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Action Type Filter -->
                <div>
                    <label for="action_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Action Type') }}</label>
                    <select name="action_type" id="action_type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('All Actions') }}</option>
                        @foreach($actionTypes as $value => $label)
                            <option value="{{ $value }}" {{ request('action_type') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Model Type Filter -->
                <div>
                    <label for="model_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Model Type') }}</label>
                    <select name="model_type" id="model_type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('All Models') }}</option>
                        @foreach($modelTypes as $value => $label)
                            <option value="{{ $value }}" {{ request('model_type') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('User') }}</label>
                    <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('All Users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date From') }}</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date To') }}</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="time_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Time From') }}</label>
                    <input type="time" name="time_from" id="time_from" value="{{ request('time_from') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="time_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Time To') }}</label>
                    <input type="time" name="time_to" id="time_to" value="{{ request('time_to') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Filter Buttons -->
                <div class="md:col-span-2 lg:col-span-4 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        {{ __('Apply Filters') }}
                    </button>
                    <a href="{{ route('logs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        {{ __('Clear Filters') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4">
            <div class="flex flex-col space-y-3">
                @forelse($logs as $log)
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left Side - Date & Action -->
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shrink-0">
                                    {{ $log->created_at->format('M d, Y H:i') }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-medium text-gray-900 truncate">{{ $log->user->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $log->user->email }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium @if($log->action_type === 'create') bg-green-100 text-green-800 @elseif($log->action_type === 'update') bg-blue-100 text-blue-800 @elseif($log->action_type === 'delete') bg-red-100 text-red-800 @elseif($log->action_type === 'login') bg-purple-100 text-purple-800 @elseif($log->action_type === 'logout') bg-gray-100 text-gray-800 @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($log->action_type) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Middle - Model & Description -->
                            <div class="flex flex-col md:flex-row md:items-center gap-2 flex-grow px-4">
                                <div class="flex flex-col text-sm text-gray-600">
                                    @if($log->model_type)
                                        <span class="font-medium">{{ class_basename($log->model_type) }} (ID: {{ $log->model_id }})</span>
                                        @if($log->new_values && is_array($log->new_values))
                                            <div class="text-xs text-gray-400 mt-1">
                                                @foreach($log->new_values as $key => $value)
                                                    @if($key !== 'id' && !is_array($value) && !is_object($value))
                                                        <span class="inline-block mr-2">
                                                            {{ $key }}: {{ Str::limit($value, 20) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                                <div class="max-w-48 truncate text-sm text-gray-900" title="{{ $log->description }}">
                                    {{ Str::limit($log->description, 40) }}
                                </div>
                            </div>
                            <!-- Right Side - IP -->
                            <div class="flex flex-col text-xs text-right text-gray-500">
                                <div class="truncate max-w-20" title="{{ $log->ip_address ?? __('N/A') }}">
                                    {{ $log->ip_address ? Str::limit($log->ip_address, 15) : __('N/A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No logs found') }}</h3>
                    </div>
                @endforelse
            </div>
        </div>

            <!-- Pagination -->
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layouts.app> 