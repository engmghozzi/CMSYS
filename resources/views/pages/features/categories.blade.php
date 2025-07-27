<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Feature Categories') }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Overview of all feature categories and their usage') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('features.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ __('Back to Features') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories Overview -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Categories Overview') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('All feature categories and their statistics') }}</p>
                </div>

                <div class="p-6">
                    @if($categories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($categories as $category)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium text-gray-900 capitalize">{{ __($category['display_name']) }}</h4>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category['count'] }} {{ __('features') }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">{{ __('Category') }}:</span>
                                            <span class="text-gray-900">{{ $category['name'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">{{ __('Features') }}:</span>
                                            <span class="text-gray-900">{{ $category['count'] }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('features.index', ['category' => $category['name']]) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-900">
                                            {{ __('View Features') }} →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">{{ __('No categories found') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="mt-6 bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Category Statistics') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Detailed statistics for each category') }}</p>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Features') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Active') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Inactive') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $category)
                                    @php
                                        $categoryFeatures = \App\Models\Feature::where('category', $category['name'])->get();
                                        $activeFeatures = $categoryFeatures->where('is_active', true)->count();
                                        $inactiveFeatures = $categoryFeatures->where('is_active', false)->count();
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-blue-600">{{ strtoupper(substr($category['name'], 0, 1)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 capitalize">{{ __($category['display_name']) }}</div>
                                                    <div class="text-sm text-gray-500">{{ $category['name'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $category['count'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $activeFeatures }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $inactiveFeatures }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('features.index', ['category' => $category['name']]) }}" 
                                               class="text-blue-600 hover:text-blue-900">{{ __('View Features') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Help Information -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Feature Categories') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ __('Categories help organize features by their purpose or resource type. Common categories include:') }}</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li><strong>{{ __('users') }}:</strong> {{ __('User management features') }}</li>
                                <li><strong>{{ __('roles') }}:</strong> {{ __('Role management features') }}</li>
                                <li><strong>{{ __('features') }}:</strong> {{ __('Feature management features') }}</li>
                                <li><strong>{{ __('clients') }}:</strong> {{ __('Client management features') }}</li>
                                <li><strong>{{ __('contracts') }}:</strong> {{ __('Contract management features') }}</li>
                                <li><strong>{{ __('payments') }}:</strong> {{ __('Payment management features') }}</li>
                                <li><strong>{{ __('machines') }}:</strong> {{ __('Machine management features') }}</li>
                                <li><strong>{{ __('reports') }}:</strong> {{ __('Reporting features') }}</li>
                                <li><strong>{{ __('settings') }}:</strong> {{ __('System settings features') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 