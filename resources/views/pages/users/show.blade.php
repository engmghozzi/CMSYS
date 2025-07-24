<x-layouts.app :title="__('User Details')">
    <div class="min-h-screen bg-gray-50">
        <div class="w-full w-full px-4 sm:px-6 lg:px-8 py-6"">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('User Details') }}</h1>
                <div class="flex gap-3">
                    <a href="{{ route('users.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('Back to Users') }}
                    </a>
                    <a href="{{ route('users.edit', $user) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                        </svg>
                        {{ __('Edit User') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Details Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('User ID') }}</label>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                                    #{{ $user->id }}
                                </span>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('Full Name') }}</label>
                            <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('Email Address') }}</label>
                            <p class="text-lg text-gray-900">{{ $user->email }}</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('Role') }}</label>
                            @if($user->role)
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium 
                                    @if($user->role->name === 'super_admin') bg-red-100 text-red-800
                                    @elseif($user->role->name === 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role->name === 'employee') bg-blue-100 text-blue-800
                                    @elseif($user->role->name === 'accountant') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->role->display_name }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ __('No Role Assigned') }}
                                </span>
                            @endif
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('Status') }}</label>
                            @if ($user->is_active)
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    {{ __('Inactive') }}
                                </span>
                            @endif
                        </div>

                        <!-- Created Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('Created Date') }}</label>
                            <p class="text-lg text-gray-900">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>

                        <!-- Last Updated -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('Last Updated') }}</label>
                            <p class="text-lg text-gray-900">{{ $user->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Role Features Section -->
                    @if($user->role && $user->role->features->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Role Features') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('Features inherited from the user\'s role') }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($user->role->features as $feature)
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-indigo-100 text-indigo-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $feature->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Individual User Features Section -->
                    @php
                        $grantedFeatures = $user->features()->wherePivot('is_granted', true)->get();
                        $roleFeatureIds = $user->role ? $user->role->features->pluck('id')->toArray() : [];
                        $additionalFeatures = $grantedFeatures->filter(function($feature) use ($roleFeatureIds) {
                            return !in_array($feature->id, $roleFeatureIds);
                        });
                    @endphp
                    @if($additionalFeatures->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Individual Features') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('Additional features granted specifically to this user beyond their role') }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($additionalFeatures as $feature)
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $feature->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- No Features Message -->
                    @if((!$user->role || $user->role->features->count() == 0) && $grantedFeatures->count() == 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No Features Assigned') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('This user has no features assigned either through their role or individually.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
