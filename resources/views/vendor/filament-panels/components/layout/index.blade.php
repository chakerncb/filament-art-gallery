@php
    use Filament\Support\Enums\Width;

    $livewire ??= null;

    $hasTopbar = filament()->hasTopbar();
    $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
    $isSidebarFullyCollapsibleOnDesktop = filament()->isSidebarFullyCollapsibleOnDesktop();
    $hasTopNavigation = filament()->hasTopNavigation();
    $hasNavigation = filament()->hasNavigation();
    $renderHookScopes = $livewire?->getRenderHookScopes();
    $maxContentWidth ??= (filament()->getMaxContentWidth() ?? Width::SevenExtraLarge);

    if (is_string($maxContentWidth)) {
        $maxContentWidth = Width::tryFrom($maxContentWidth) ?? $maxContentWidth;
    }
@endphp

<x-filament-panels::layout.base
    :livewire="$livewire"
    @class([
        'fi-body-has-navigation' => $hasNavigation,
        'fi-body-has-sidebar-collapsible-on-desktop' => $isSidebarCollapsibleOnDesktop,
        'fi-body-has-sidebar-fully-collapsible-on-desktop' => $isSidebarFullyCollapsibleOnDesktop,
        'fi-body-has-top-navigation' => $hasTopNavigation,
    ])
>
    @if ($hasTopbar)
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_BEFORE, scopes: $renderHookScopes) }}

            @if (filament()->auth()->check())
                @livewire(filament()->getTopbarLivewireComponent())
            @else
                {{-- <div class="fi-topbar flex h-16 items-center justify-end gap-x-4 border-b border-gray-200 bg-white px-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <x-filament::button 
                        tag="a" 
                        href="{{ route('login') }}" 
                        color="primary"
                        size="sm"
                    >
                        Login
                    </x-filament::button>
                    <x-filament::button 
                        tag="a" 
                        href="{{ route('register') }}" 
                        color="gray"
                        variant="outlined"
                        size="sm"
                    >
                        Sign Up
                    </x-filament::button>
                </div> --}}

                <nav x-data="{ mobileMenuIsOpen: false, searchIsOpen: false }" x-on:click.away="mobileMenuIsOpen = false" class="flex items-center justify-between bg-white/95 backdrop-blur-sm border-b border-gray-200 px-6 py-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/95" aria-label="navigation menu">
                    <!-- Logo -->
                    <a href="#" class="text-2xl font-bold text-gray-900 dark:text-white">
                        <span>Art<span class="text-blue-600 dark:text-blue-400">Gallery</span></span>
                    </a>

                    <!-- Desktop Search Box -->
                    <div class="hidden lg:flex flex-1 max-w-md mx-8">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                                </svg>
                            </div>
                            <input type="search" placeholder="Search artworks, artists..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 dark:focus:border-blue-400" />
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <ul class="hidden lg:flex items-center gap-6">
                        <li><a href="#" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors dark:text-blue-400 dark:hover:text-blue-300" aria-current="page">Gallery</a></li>
                        <li><a href="#" class="font-medium text-gray-700 hover:text-blue-600 transition-colors dark:text-gray-300 dark:hover:text-blue-400">Artists</a></li>
                        <li><a href="#" class="font-medium text-gray-700 hover:text-blue-600 transition-colors dark:text-gray-300 dark:hover:text-blue-400">Collections</a></li>
                        <li><a href="#" class="font-medium text-gray-700 hover:text-blue-600 transition-colors dark:text-gray-300 dark:hover:text-blue-400">About</a></li>
                        <li>
                            <a href="{{route('login')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors dark:bg-blue-500 dark:hover:bg-blue-600">
                                Sign In
                            </a>
                        </li>
                    </ul>

                    <!-- Mobile Controls -->
                    <div class="flex items-center gap-2 lg:hidden">
                        <!-- Mobile Search Toggle -->
                        <button x-on:click="searchIsOpen = !searchIsOpen" type="button" class="p-2 text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" aria-label="search">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                            </svg>
                        </button>

                        <!-- Mobile Menu Toggle -->
                        <button x-on:click="mobileMenuIsOpen = !mobileMenuIsOpen" x-bind:aria-expanded="mobileMenuIsOpen" x-bind:class="mobileMenuIsOpen ? 'fixed top-6 right-6 z-20' : null" type="button" class="p-2 text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" aria-label="mobile menu" aria-controls="mobileMenu">
                            <svg x-cloak x-show="!mobileMenuIsOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-cloak x-show="mobileMenuIsOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Search Box -->
                    <div x-cloak x-show="searchIsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute top-full left-0 right-0 z-10 bg-white border-b border-gray-200 px-6 py-4 dark:bg-gray-900 dark:border-gray-700 lg:hidden">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                                </svg>
                            </div>
                            <input type="search" placeholder="Search artworks, artists..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 dark:focus:border-blue-400" />
                        </div>
                    </div>

                    <!-- Mobile Menu -->
                    <ul x-cloak x-show="mobileMenuIsOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-full opacity-0" id="mobileMenu" class="fixed inset-x-0 top-0 z-10 bg-white border-b border-gray-200 px-6 pb-6 pt-20 shadow-lg dark:bg-gray-900 dark:border-gray-700 lg:hidden">
                        <li class="py-3 border-b border-gray-100 dark:border-gray-800">
                            <a href="#" class="block text-lg font-semibold text-blue-600 dark:text-blue-400" aria-current="page">Gallery</a>
                        </li>
                        <li class="py-3 border-b border-gray-100 dark:border-gray-800">
                            <a href="#" class="block text-lg font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Artists</a>
                        </li>
                        <li class="py-3 border-b border-gray-100 dark:border-gray-800">
                            <a href="#" class="block text-lg font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Collections</a>
                        </li>
                        <li class="py-3 border-b border-gray-100 dark:border-gray-800">
                            <a href="#" class="block text-lg font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">About</a>
                        </li>
                        <li class="pt-4">
                            <a href="{{route('login')}}" class="block w-full text-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors dark:bg-blue-500 dark:hover:bg-blue-600">Sign In</a>
                        </li>
                    </ul>
                </nav>


            @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_AFTER, scopes: $renderHookScopes) }}
    @endif

    {{-- The sidebar is after the page content in the markup to fix issues with page content overlapping dropdown content from the sidebar. --}}
    <div class="fi-layout">
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::LAYOUT_START, scopes: $renderHookScopes) }}

        @if ($hasNavigation)
            <div
                x-cloak
                x-data="{}"
                x-on:click="$store.sidebar.close()"
                x-show="$store.sidebar.isOpen"
                x-transition.opacity.300ms
                class="fi-sidebar-close-overlay"
            ></div>

            @livewire(filament()->getSidebarLivewireComponent())
        @endif

        <div
            @if ($isSidebarCollapsibleOnDesktop)
                x-data="{}"
                x-bind:class="{
                    'fi-main-ctn-sidebar-open': $store.sidebar.isOpen,
                }"
                x-bind:style="'display: flex; opacity:1;'"
                {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
            @elseif ($isSidebarFullyCollapsibleOnDesktop)
                x-data="{}"
                x-bind:class="{
                    'fi-main-ctn-sidebar-open': $store.sidebar.isOpen,
                }"
                x-bind:style="'display: flex; opacity:1;'"
                {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
            @elseif (! ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop || $hasTopNavigation || (! $hasNavigation)))
                x-data="{}"
                x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
            @endif
            class="fi-main-ctn"
        >
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_BEFORE, scopes: $renderHookScopes) }}

            <main
                @class([
                    'fi-main',
                    ($maxContentWidth instanceof Width) ? "fi-width-{$maxContentWidth->value}" : $maxContentWidth,
                ])
            >
                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_START, scopes: $renderHookScopes) }}

                {{ $slot }}

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_END, scopes: $renderHookScopes) }}
            </main>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_AFTER, scopes: $renderHookScopes) }}

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $renderHookScopes) }}
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::LAYOUT_END, scopes: $renderHookScopes) }}
    </div>
</x-filament-panels::layout.base>
