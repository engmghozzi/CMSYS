<x-layouts.app :title="'Edit Address for ' . $client->name">

    <div class="min-h-screen">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit Address') }}</h1>
                <p class="text-gray-600 mt-1">{{ $client->name }}</p>
            </div>
            <a href="{{ route('clients.show', $client->id) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('Back to Client') }}
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ __('There were some errors with your submission') }}</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Address Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Address Information') }}</h2>
            </div>

            <form method="POST" action="{{ route('addresses.update', ['client' => $client->id, 'address' => $address->id]) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Area -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Area') }}</label>
                        <input type="text" name="area" value="{{ old('area', $address->area) }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>

                    <!-- Block -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Block') }}</label>
                        <input type="text" name="block" value="{{ old('block', $address->block) }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>

                    <!-- Street -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Street') }}</label>
                        <input type="text" name="street" value="{{ old('street', $address->street) }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>

                    <!-- House Number -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('House Number') }}</label>
                        <input type="text" name="house_num" value="{{ old('house_num', $address->house_num) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>

                    <!-- Floor Number -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Floor Number') }}</label>
                        <input type="text" name="floor_num" value="{{ old('floor_num', $address->floor_num) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>

                    <!-- Flat Number -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Flat Number') }}</label>
                        <input type="text" name="flat_num" value="{{ old('flat_num', $address->flat_num) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>

                    <!-- PACI Number -->
                    <div class="space-y-2 rtl:space-x-reverse">
                        <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('PACI') }}</label>
                        <input type="number" name="paci_num" value="{{ old('paci_num', $address->paci_num) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right" />
                    </div>
                </div>

                <!-- Address Notes -->
                <div class="mt-6 space-y-2 rtl:space-x-reverse">
                    <label class="block text-sm font-medium text-gray-700 mb-1 rtl:text-right">{{ __('Address Notes') }}</label>
                    <textarea name="address_notes" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 rtl:text-right">{{ old('address_notes', $address->address_notes) }}</textarea>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3 rtl:space-x-reverse">
                    <a href="{{ route('clients.show', $client->id) }}"
                       class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
