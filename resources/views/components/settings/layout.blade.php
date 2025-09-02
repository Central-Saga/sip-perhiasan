<div class="flex items-start max-md:flex-col max-w-7xl mx-auto gap-6">
    <div class="me-0 md:me-6 w-full pb-4 md:w-[260px] md:pb-0">
        <div
            class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white/60 dark:bg-neutral-900/60 backdrop-blur p-2 md:p-3 sticky top-24">
            <flux:navlist>
                <flux:navlist.item :href="route('settings.profile')" wire:navigate>{{ __('Profile') }}
                </flux:navlist.item>
                <flux:navlist.item :href="route('settings.password')" wire:navigate>{{ __('Password') }}
                </flux:navlist.item>
                <flux:navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('Appearance') }}
                </flux:navlist.item>
            </flux:navlist>
        </div>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>