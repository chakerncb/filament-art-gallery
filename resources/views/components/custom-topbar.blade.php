<div class="fi-topbar-ctn">
    <nav class="fi-topbar">
        <div class="fi-topbar-start">
            <x-filament-panels::logo />
        </div>

        <div class="fi-topbar-end">
            @if (class_exists('\Filament\Livewire\GlobalSearch'))
                <div class="mr-4">
                    @livewire(\Filament\Livewire\GlobalSearch::class)
                </div>
            @endif

            <div class="mr-4">
                <x-filament::icon-button
                    color="gray"
                    icon="heroicon-m-sun"
                    icon-size="lg"
                    label="Toggle theme"
                    x-data="{}"
                    x-on:click="
                        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            localStorage.theme = 'light';
                            document.documentElement.classList.remove('dark');
                        } else {
                            localStorage.theme = 'dark';
                            document.documentElement.classList.add('dark');
                        }
                    "
                    x-bind:icon="
                        (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) ? 'heroicon-m-moon' : 'heroicon-m-sun'
                    "
                />
            </div>

            <div class="flex items-center space-x-2">
                <x-filament::button
                    color="gray"
                    outlined
                    size="sm"
                    tag="a"
                    href="{{ route('login') }}"
                >
                    Sign In
                </x-filament::button>

                <x-filament::button
                    color="primary"
                    size="sm"
                    tag="a"
                    href="{{ route('register') }}"
                >
                    Sign Up
                </x-filament::button>
            </div>
        </div>
    </nav>
</div>