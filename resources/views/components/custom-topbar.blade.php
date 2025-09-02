<div class="fi-topbar-ctn border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
    <nav class="fi-topbar flex items-center justify-between px-4 py-3">
        <div class="fi-topbar-start flex items-center space-x-3">
            <x-filament-panels::logo />
        </div>

        <div class="flex-1 max-w-sm mx-4 mr-2">
            <form method="get" role="search" aria-label="Find content sitewide" action="#" class="relative">
                <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-4 py-2 shadow-sm hover:shadow-md transition-shadow duration-200 focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-primary-500">
                    <button title="Search" class="mr-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" type="submit">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    <input 
                        autocomplete="off" 
                        placeholder="Search artworks..." 
                        title="Search content" 
                        class="flex-1 bg-transparent border-none outline-none text-gray-400 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 text-sm" 
                        autocapitalize="none" 
                        spellcheck="false" 
                        type="search" 
                        name="searchKeyword"
                    >
                    
                    <button type="button" aria-label="Visual search" class="ml-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="fi-topbar-end flex items-center space-x-2">
            <div class="flex items-center">
                <x-filament::icon-button
                    color="gray"
                    icon="heroicon-m-sun"
                    icon-size="md"
                    label="Toggle theme"
                    class="hover:bg-gray-100 dark:hover:bg-gray-800"
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

            <div class="h-5 w-px bg-gray-200 dark:bg-gray-700"></div>

            @auth
                <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
                    <button 
                        x-on:click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 group border border-transparent hover:border-gray-200 dark:hover:border-gray-700"
                        type="button"
                        :class="{ 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700': open }"
                    >
                        <div class="w-9 h-9 bg-gray-600 dark:bg-gray-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-gray-900 dark:text-white group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors">
                            {{ Auth::user()->name }}
                        </span>
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-all duration-200 group-hover:text-gray-700 dark:group-hover:text-gray-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="transform opacity-0 scale-95 translate-y-1"
                        class="absolute right-0 mt-3 w-72 bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
                        style="display: none;"
                    >
                        <div class="px-6 py-5 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-gray-600 dark:bg-gray-500 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="py-3">
                            <a href="/user/images" class="flex items-center gap-4 px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span>My Images</span>
                            </a>
                            
                            <a href="/user/favorites" class="flex items-center gap-4 px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <span>My Favorites</span>
                            </a>
                            
                            <a href="#" class="flex items-center gap-4 px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span>Settings</span>
                            </a>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 pb-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-4 w-full px-6 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-red-700 dark:hover:text-red-300 transition-all duration-200 group">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center space-x-2">
                    <x-filament::button
                        color="gray"
                        outlined
                        size="sm"
                        tag="a"
                        href="{{ route('login') }}"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200"
                    >
                        Sign In
                    </x-filament::button>

                    <x-filament::button
                        color="primary"
                        size="sm"
                        tag="a"
                        href="{{ route('register') }}"
                        class="shadow-sm hover:shadow-md transition-shadow duration-200"
                    >
                        Sign Up
                    </x-filament::button>
                </div>
            @endauth
        </div>
    </nav>
</div>