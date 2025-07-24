<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Language')" :subheading="__('Choose your language')">
        <form wire:submit="saveLanguage" class="my-6 w-full space-y-6">
            <div class="w-full">
                <label for="lang" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Select Language') }}
                </label>
                <div class="relative">
                    <select wire:model="lang" id="lang" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm transition-colors duration-200 focus:border-primary-500 focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-75 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                        <option value="en">English</option>
                        <option value="ar">Arabic</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Save') }}
                    </flux:button>
                </div>
                <x-action-message class="me-3" on="language-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>