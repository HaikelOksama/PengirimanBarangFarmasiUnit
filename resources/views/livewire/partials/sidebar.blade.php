<div class="flex flex-col h-screen">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Utama')" class="grid">
            <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            <flux:navlist.item icon="home" :href="route('main.material-request.index')"
                :current="request()->routeIs('material-request/*')" wire:navigate>
                Material Request
                @if($kirimMasuk > 0)
                    <div class="ms-2 badge badge-sm badge-error px-1.5" >{{ $kirimMasuk }}</div>
                @endif
            </flux:navlist.item>
            <flux:navlist.item icon="bolt" :href="route('main.kirim.index')"
                :current="request()->routeIs('permintaan-unit/*')" wire:navigate>
                Kirim Unit (Permintaan)
                @if($kirimRequest > 0)
                    <div class="ms-2 badge badge-sm badge-error rounded-full px-1.5" >{{ $kirimRequest }}</div>
                @endif
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />



    <!-- Desktop User Menu -->
    <flux:dropdown position="bottom" align="start">
        <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
            icon-trailing="chevrons-up-down" />

        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <flux:menu.radio.group>
                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                </flux:menu.item>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>

</div>