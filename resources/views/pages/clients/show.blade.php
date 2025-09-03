<x-layouts.app :title="__('Client Details')">

    <x-ui.form-layout
        :title="__('Client Details')"
        :description="__('Client') . ' #' . $client->id . ' - ' . $client->name"
        :back-url="route('clients.index')"
        :back-label="__('Back')"
    >
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-6">
            @if(auth()->user()->hasPermission('clients.update'))
                @if($client->isBlocked())
                    @if(auth()->user()->hasPermission('clients.manage'))
                        <!-- Admin/Superadmin can edit blocked clients -->
                        <a href="{{ route('clients.edit', $client->id) }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-orange-600 px-3 sm:px-4 py-2 text-sm text-white hover:bg-orange-700 transition-colors"
                           title="Edit blocked client (Admin access)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                            </svg>
                            {{__('Edit Blocked Client')}}
                        </a>
                    @else
                        <!-- Regular users cannot edit blocked clients -->
                        <button disabled
                               class="inline-flex items-center gap-2 rounded-lg bg-gray-400 px-3 sm:px-4 py-2 text-sm text-white cursor-not-allowed"
                               title="Cannot edit blocked client">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                            </svg>
                            {{__('Edit Client')}}
                        </button>
                    @endif
                @else
                    <a href="{{ route('clients.edit', $client->id) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm text-white hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                        </svg>
                        {{__('Edit Client')}}
                    </a>
                @endif
            @endif
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($client->isBlocked())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-800">
                        <strong>Warning:</strong> This client is blocked. You cannot edit the client or add addresses, contracts, or payments.
                    </p>
                </div>
            </div>
        @endif

        <!-- Client Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-4 sm:px-6 py-3 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{__('Personal Information')}}</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Full Name')}}</label>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $client->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Mobile Number')}}</label>
                        <p class="text-sm text-gray-900 mt-0.5">{{ $client->mobile_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Alternative Mobile')}}</label>
                        <p class="text-sm text-gray-900 mt-0.5">{{ $client->alternate_mobile_number ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Client Type')}}</label>
                        <p class="mt-0.5"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $client->client_type }}</span></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Status')}}</label>
                        <p class="mt-0.5"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $client->getStatusBadgeClasses() }}">{{ ucfirst($client->status) }}</span></p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{__('Created By')}}</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $client->creator->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{__('Updated By')}}</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $client->updater->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{__('Created Date')}}</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $client->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Addresses')}}</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $client->addresses->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Contracts')}}</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $client->contracts->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Total Payments')}}</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $client->payments->count() }}</p>
                    </div>
                </div>
            </div>


        </div>

        <!-- Addresses Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">{{__('Addresses')}}</h2>
                @if(auth()->user()->hasPermission('clients.create'))
                    @if($client->isBlocked())
                        <button disabled
                               class="inline-flex items-center gap-2 rounded-lg bg-gray-400 px-3 sm:px-4 py-2 text-sm text-white cursor-not-allowed"
                               title="Cannot add addresses to blocked client">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            {{__('Add Address')}}
                        </button>
                    @else
                        <a href="{{ route('addresses.create', $client->id) }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm text-white hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            {{__('Add Address')}}
                        </a>
                    @endif
                @endif
            </div>
            <div class="p-4">
                @if ($client->addresses->isEmpty())
                    <div class="text-center py-6">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('No addresses')}}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{__('Get started by adding an address for this client.')}}</p>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($client->addresses as $address)
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                                <!-- Address Details in Compact Layout -->
                                <div class="space-y-1.5 mb-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900">{{__('Area')}}: {{ $address->area }}</span>
                                        <span class="font-medium text-gray-900">{{__('Block')}}: {{ $address->block }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{__('Street')}}: {{ $address->street }}
                                    </div>
                                    <div class="grid grid-cols-3 text-sm text-gray-600">
                                        <span>{{__('House')}}: {{ $address->house_num }}</span>
                                        <span>{{__('Floor')}}: {{ $address->floor_num }}</span>
                                        <span>{{__('Flat')}}: {{ $address->flat_num }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{__('PACI')}}: {{ $address->paci_num }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 pt-2 border-t border-gray-100">
                                    @if(auth()->user()->hasPermission('clients.read'))
                                        <a href="{{ route('addresses.show', [$client, $address]) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1 rounded-md bg-blue-600 px-2 py-1.5 text-xs text-white hover:bg-blue-700 transition-colors">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{__('View')}}
                                        </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('clients.update'))
                                        @if($client->isBlocked())
                                            <button disabled
                                                   class="flex-1 inline-flex items-center justify-center gap-1 rounded-md bg-gray-300 px-2 py-1.5 text-xs text-gray-500 cursor-not-allowed"
                                                   title="Cannot edit addresses for blocked client">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                </svg>
                                                {{__('Edit')}}
                                            </button>
                                        @else
                                            <a href="{{ route('addresses.edit', [$client->id, $address->id]) }}"
                                               class="flex-1 inline-flex items-center justify-center gap-1 rounded-md bg-gray-100 px-2 py-1.5 text-xs text-gray-700 hover:bg-gray-200 transition-colors">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                </svg>
                                                {{__('Edit')}}
                                            </a>
                                        @endif
                                    @endif
                                    @if(auth()->user()->hasPermission('clients.delete'))
                                        @if($client->isBlocked())
                                            <button disabled
                                                   class="flex-1 inline-flex items-center justify-center gap-1 rounded-md bg-gray-300 px-2 py-1.5 text-xs text-gray-500 cursor-not-allowed"
                                                   title="Cannot delete addresses for blocked client">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                                </svg>
                                                {{__('Delete')}}
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('addresses.destroy', [$client->id, $address->id]) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this address?');"
                                                  class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center gap-1 rounded-md bg-red-100 px-2 py-1.5 text-xs text-red-700 hover:bg-red-200 transition-colors">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                                    </svg>
                                                    {{__('Delete')}}
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-ui.form-layout>

</x-layouts.app>
