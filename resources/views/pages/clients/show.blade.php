<x-layouts.app :title="__('Client Details')">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="content-start">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Client Details') }}</h1>
                <p class="text-gray-600 mt-1 ">{{__('Client')}} #{{ $client->id }} - {{ $client->name }}</p>
            </div>
            <div class="flex gap-3 content-start">
                <a href="{{ route('clients.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back') }}
                </a>
                @if(auth()->user()->hasPermission('clients.update'))
                    <a href="{{ route('clients.edit', $client->id) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                        </svg>
                        {{__('Edit Client')}}
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Client Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{__('Personal Information')}}</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Full Name')}}</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $client->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Mobile Number')}}</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $client->mobile_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Alternative Mobile')}}</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $client->alternate_mobile_number ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Client Type')}}</label>
                        <p class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $client->client_type }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{__('Status')}}</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $client->getStatusBadgeClasses() }}">
                            {{ ucfirst($client->status) }}
                        </span>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{__('Created By')}}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->creator->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{__('Updated By')}}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->updater->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{__('Created Date')}}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Addresses')}}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $client->addresses->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Contracts')}}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $client->contracts->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Total Payments')}}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $client->payments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{__('Machines')}}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $client->machines->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Addresses Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">{{__('Addresses')}}</h2>
                @if(auth()->user()->hasPermission('clients.create'))
                    <a href="{{ route('addresses.create', $client->id) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        {{__('Add Address')}}
                    </a>
                @endif
            </div>
            <div class="p-6">
                @if ($client->addresses->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('No addresses')}}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{__('Get started by adding an address for this client.')}}</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($client->addresses as $address)
                            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $address->area }} - {{ $address->block }}
                                            </h3>
                                        </div>
                                        <p class="text-gray-600 mb-2">
                                            {{ $address->street }}, {{__('House')}} {{ $address->house_num }}, {{__('Floor')}} {{ $address->floor_num }}, {{__('Flat')}} {{ $address->flat_num }}
                                        </p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span>{{__('PACI')}}: {{ $address->paci_num }}</span>
                                            @if($address->address_notes)
                                                <span>• {{ Str::limit($address->address_notes, 50) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @if(auth()->user()->hasPermission('clients.read'))
                                            <a href="{{ route('addresses.show', [$client->id, $address->id]) }}"
                                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition-colors">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{__('View Details')}}
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('clients.update'))
                                            <a href="{{ route('addresses.edit', [$client->id, $address->id]) }}"
                                               class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.5H4.5v-3L16.862 3.487z" />
                                                </svg>
                                                {{__('Edit')}}
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('clients.delete'))
                                            <form method="POST" action="{{ route('addresses.destroy', [$client->id, $address->id]) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this address?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-md bg-red-100 px-3 py-2 text-sm text-red-700 hover:bg-red-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                                    </svg>
                                                    {{__('Delete')}}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
