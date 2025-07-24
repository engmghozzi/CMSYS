<x-layouts.app :title="__('clients')">


    <div class="min-h-screen">
        <!-- Add Client Button -->
        @if(auth()->user()->hasPermission('clients.create'))
            <div class="flex mb-4 {{ app()->getLocale() === 'ar' ? 'justify-end' : 'justify-start' }}">
                <a 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors
                    {{ app()->getLocale() === 'ar' ? 'flex-row-reverse content-start' : 'flex-row content-start' }}"
                    href="{{ route('clients.create') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('New Client') }}
                </a>
            </div>
        @endif
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('clients.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
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
                                placeholder="{{ __('Search by customer Number') }}" 
                                class="flex-1 bg-transparent border-0 focus:ring-0 h-11 {{ app()->getLocale() === 'ar' ? 'text-right placeholder:text-right pr-2' : 'text-left placeholder:text-left pl-2' }}"
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>
                    
                    <div class="relative md:col-span-1">
                        <select 
                            name="client_type" 
                            class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option value="">{{ __('All Types') }}</option>
                            <option value="Client" {{ request('client_type') == 'Client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                            <option value="Company" {{ request('client_type') == 'Company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                            <option value="Contractor" {{ request('client_type') == 'Contractor' ? 'selected' : '' }}>{{ __('Contractor') }}</option>
                            <option value="Other" {{ request('client_type') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>
                    
                    <div class="relative md:col-span-1">
                        <select 
                            name="created_by" 
                            class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option value="">{{ __('All Creators') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="relative md:col-span-1">
                        <select 
                            name="updated_by" 
                            class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option value="">{{ __('All Updaters') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('updated_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="relative md:col-span-1">
                        <select 
                            name="status" 
                            class="block w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option value="">{{ __('All Status') }}</option>
                            <option value="vip" {{ request('status') == 'vip' ? 'selected' : '' }}>{{ __('VIP') }}</option>
                            <option value="ordinary" {{ request('status') == 'ordinary' ? 'selected' : '' }}>{{ __('Ordinary') }}</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>{{ __('Blocked') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-2">
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
                            href="{{ route('clients.index') }}" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M3 12a9 9 0 0115.9-5.9l.6.6M21 12a9 9 0 01-15.9 5.9l-.6-.6M16.5 7.5H21V3" />
                            </svg>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Clients Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Mobile') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Alt Mobile') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Created By') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Updated By') }}</th>
                        <th class="px-4 py-3 text-left align-middle text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    #{{ $client->id }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $client->getStatusBadgeClasses() }}">
                                    {{ __(ucfirst($client->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $client->mobile_number }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                @if($client->alternate_mobile_number)
                                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                        <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $client->alternate_mobile_number }}
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                    {{ __($client->client_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $client->creator->name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $client->updater->name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'justify-end flex-row-reverse' : 'justify-start flex-row' }} gap-2">
                                    @if(auth()->user()->hasPermission('clients.read'))
                                        <a 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                                            href="{{ route('clients.show', $client) }}"
                                            title="{{ __('View') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endif
                                    <form 
                                        method="POST" 
                                        action="{{ route('clients.destroy', $client) }}"
                                        onsubmit="return confirm('{{ __('Are you sure you want to delete this client?') }}');"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            class="inline-flex items-center justify-center m-1 w-8 h-8 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                                            title="{{ __('Delete') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H8V5a1 1 0 011-1z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                                                                <td colspan="9" class="px-4 py-8 text-center align-middle">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No clients found') }}</h3>
                                    <p class="text-gray-500">{{ __('Get started by creating a new client.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <div class="bg-white rounded-lg shadow border border-gray-200 px-4 py-3">
                {{ $clients->links() }}
            </div>
        </div>
    </div>


</x-layouts.app>
