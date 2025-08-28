<x-layouts.app :title="__('User Details')">

    <x-ui.form-layout
        :title="$user->name"
        :description="$user->email"
        :back-url="route('users.index')"
        :back-label="__('Back to Users')"
    >
        <!-- User Avatar and Actions -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasPermission('users.update'))
                    <a href="{{ route('users.edit', $user) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit User') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- User Details & Permissions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- User Info -->
            <div class="border-b border-gray-200">
                <dl class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    <div class="px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Role') }}</dt>
                        <dd class="mt-2">
                            @if($user->role)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                                    @if($user->role->name === 'super_admin') bg-red-100 text-red-800
                                    @elseif($user->role->name === 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role->name === 'employee') bg-blue-100 text-blue-800
                                    @elseif($user->role->name === 'accountant') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ __($user->role->display_name) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ __('No Role Assigned') }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                        <dd class="mt-2">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Member Since') }}</dt>
                        <dd class="mt-2 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            @php
                $roleGrantedFeatures = $user->role ? $user->role->features()->wherePivot('is_granted', true)->get() : collect();
            @endphp

            <!-- Permissions Overview -->
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('Permissions Overview') }}</h3>
                
                <div class="flex flex-col space-y-8">
                    <!-- Role-Based Permissions -->
                    @if($roleGrantedFeatures->isNotEmpty())
                        <div class="bg-indigo-50 rounded-lg p-4">
                            <div class="flex items-center mb-4">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-medium text-indigo-800">{{ __('Role-Based Permissions') }}</h4>
                                <p class="ml-3 text-sm text-indigo-600">{{ __('Inherited from role:') }} <strong>{{ $user->role->display_name ?? 'No Role' }}</strong></p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($roleGrantedFeatures as $feature)
                                    <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                                        <span class="text-sm text-gray-900">{{ __($feature->display_name) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- No Permissions Message -->
                    @if($roleGrantedFeatures->isEmpty())
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('No Permissions') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('This user has no permissions assigned through their role.') }}</p>
                        </div>
                    @endif

                    <!-- Note about role-based permissions -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-blue-800">{{ __('Permission System Note') }}</h4>
                        </div>
                        <p class="text-sm text-blue-700">{{ __('User permissions are now managed through roles only. To change user permissions, assign them to a different role or modify the role\'s features.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.form-layout>

</x-layouts.app>
