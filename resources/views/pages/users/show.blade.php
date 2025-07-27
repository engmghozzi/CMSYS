<x-layouts.app :title="__('User Details')">

    <div class="min-h-screen">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
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
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Users') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('User Information') }}</h2>
                        
                        <div class="space-y-4">
                        <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Name') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                        </div>

                        <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Email') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Role') }}</label>
                            @if($user->role)
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium 
                                    @if($user->role->name === 'super_admin') bg-red-100 text-red-800
                                    @elseif($user->role->name === 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role->name === 'employee') bg-blue-100 text-blue-800
                                    @elseif($user->role->name === 'accountant') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ __($user->role->display_name) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ __('No Role Assigned') }}
                                </span>
                            @endif
                        </div>

                        <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Status') }}</label>
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

                        <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Created') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('Last Updated') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Overview -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Permissions Overview') }}</h2>
                        
                        @php
                            $userGrantedFeatures = $user->features()->wherePivot('is_granted', true)->get();
                            $userRevokedFeatures = $user->features()->wherePivot('is_granted', false)->get();
                            $roleGrantedFeatures = $user->role ? $user->role->features()->wherePivot('is_granted', true)->get() : collect();
                        @endphp

                        <!-- Granted Permissions -->
                        @if($userGrantedFeatures->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-md font-medium text-green-800 mb-3">{{ __('Granted Permissions') }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ __('Permissions explicitly granted to this user:') }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($userGrantedFeatures as $feature)
                                        <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                            <svg class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                            <span class="text-sm text-gray-900">{{ __($feature->display_name) }}</span>
                                        </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                        <!-- Revoked Permissions -->
                        @if($userRevokedFeatures->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-md font-medium text-red-800 mb-3">{{ __('Revoked Permissions') }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ __('Permissions explicitly revoked from this user:') }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($userRevokedFeatures as $feature)
                                        <div class="flex items-center p-3 bg-red-50 rounded-lg">
                                            <svg class="h-4 w-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ __($feature->display_name) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Inherited Permissions -->
                        @if($roleGrantedFeatures->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-md font-medium text-indigo-800 mb-3">{{ __('Inherited Permissions') }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ __('Permissions inherited from the role (not overridden):') }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($roleGrantedFeatures as $feature)
                                        @php
                                            $isUserGranted = $userGrantedFeatures->contains('id', $feature->id);
                                            $isUserRevoked = $userRevokedFeatures->contains('id', $feature->id);
                    @endphp
                                        @if(!$isUserGranted && !$isUserRevoked)
                                            <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                                                <svg class="h-4 w-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                                <span class="text-sm text-gray-900">{{ __($feature->display_name) }}</span>
                                            </div>
                                        @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                        <!-- No Permissions Message -->
                        @if($userGrantedFeatures->count() == 0 && $userRevokedFeatures->count() == 0 && $roleGrantedFeatures->count() == 0)
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No Permissions') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('This user has no permissions assigned.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Permission Test Section -->
            <div class="mt-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Permission Test Results') }}</h2>
                    <p class="text-sm text-gray-600 mb-4">{{ __('Testing common permissions for this user:') }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $testPermissions = [
                                'clients.read' => 'View Clients',
                                'clients.create' => 'Create Clients',
                                'clients.update' => 'Edit Clients',
                                'clients.delete' => 'Delete Clients',
                                'contracts.read' => 'View Contracts',
                                'contracts.create' => 'Create Contracts',
                                'contracts.update' => 'Edit Contracts',
                                'contracts.delete' => 'Delete Contracts',
                                'payments.read' => 'View Payments',
                                'payments.create' => 'Create Payments',
                                'payments.update' => 'Edit Payments',
                                'payments.delete' => 'Delete Payments',
                                'machines.read' => 'View Machines',
                                'machines.create' => 'Create Machines',
                                'machines.update' => 'Edit Machines',
                                'machines.delete' => 'Delete Machines',
                                'users.read' => 'View Users',
                                'users.create' => 'Create Users',
                                'users.update' => 'Edit Users',
                                'users.delete' => 'Delete Users',
                            ];
                        @endphp
                        
                        @foreach($testPermissions as $permission => $displayName)
                            @php
                                $hasPermission = $user->hasPermission($permission);
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-lg {{ $hasPermission ? 'bg-green-50' : 'bg-red-50' }}">
                                <span class="text-sm font-medium {{ $hasPermission ? 'text-green-800' : 'text-red-800' }}">
                                    {{ __($displayName) }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $hasPermission ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $hasPermission ? __('Allowed') : __('Denied') }}
                                </span>
                            </div>
                        @endforeach
                        </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
