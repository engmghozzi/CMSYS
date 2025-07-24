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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Activity Logs') }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ __('Showing') }} {{ $logs->total() }} {{ __('entries') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Date & Time') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('User') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Action') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Model') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Description') }}
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 hidden md:table-cell">
                                {{ __('IP') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-gray-500">{{ $log->created_at->format('H:i:s') }} (KWT)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($log->action_type === 'create') bg-green-100 text-green-800
                                    @elseif($log->action_type === 'update') bg-blue-100 text-blue-800
                                    @elseif($log->action_type === 'delete') bg-red-100 text-red-800
                                    @elseif($log->action_type === 'login') bg-purple-100 text-purple-800
                                    @elseif($log->action_type === 'logout') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($log->action_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($log->model_type)
                                    <div class="font-medium">{{ class_basename($log->model_type) }}</div>
                                    <div class="text-gray-500">ID: {{ $log->model_id }}</div>
                                    @if($log->new_values && is_array($log->new_values))
                                        <div class="text-xs text-gray-400 mt-1">
                                            @foreach($log->new_values as $key => $value)
                                                @if($key !== 'id' && !is_array($value) && !is_object($value))
                                                    <span class="inline-block mr-2">
                                                        @if($key === 'mobile_number')
                                                            📱 {{ Str::limit($value, 15) }}
                                                        @elseif($key === 'status')
                                                            🏷️ {{ $value }}
                                                        @elseif($key === 'name')
                                                            👤 {{ Str::limit($value, 20) }}
                                                        @elseif($key === 'email')
                                                            📧 {{ Str::limit($value, 20) }}
                                                        @else
                                                            {{ $key }}: {{ Str::limit($value, 20) }}
                                                        @endif
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-48 truncate" title="{{ $log->description }}">
                                    {{ Str::limit($log->description, 40) }}
                                </div>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-xs text-gray-500 hidden md:table-cell">
                                <div class="truncate max-w-20" title="{{ $log->ip_address ?? __('N/A') }}">
                                    {{ $log->ip_address ? Str::limit($log->ip_address, 15) : __('N/A') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No logs found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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