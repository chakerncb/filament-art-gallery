<x-filament::page>
    <div >
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-12">
            <div class="text-center lg:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Art Gallery
            </h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mx-auto lg:mx-0 mb-4"></div>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl leading-relaxed">
            Discover a curated collection of stunning artworks from talented artists around the world
            </p>
            </div>
            
            <div class="mt-8 lg:mt-0 lg:ml-8">
            <div class="relative max-w-md">
                <input type="text" 
                   wire:model="query" 
                   wire:keydown.debounce.300ms="search"
                   wire:keydown.Enter="search"
                   placeholder="Search artworks..." 
                   class="w-full px-4 py-3 pl-12 pr-4 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                </div>
            </div>
            </div>
        </div>

        @if($artworks && count($artworks) > 0)
            <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                <div class="px-6">
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-semibold rounded-full shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m3 0H4a1 1 0 00-1 1v14a1 1 0 001 1h16a1 1 0 001-1V5a1 1 0 00-1-1zM9 9h6M9 13h6m-3 4h.01"></path>
                    </svg>
                    Art Institute of Chicago
                </span>
                </div>
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($artworks as $artwork)
                <div class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-200 dark:border-gray-700">
                    <div class="relative overflow-hidden rounded-t-xl">
                    @if(isset($artwork['image_url']) && $artwork['image_url'])
                        <img src="{{ $artwork['image_url_small'] ?? $artwork['image_url'] }}" 
                         alt="{{ $artwork['title'] ?? 'Artwork' }}" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="text-white transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>

                                <button wire:click='downloadImage({{$artwork['id']}},{{true}})' class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7L12 14M12 14L15 11M12 14L9 11"/>
                                            <path stroke-linecap="round" stroke-width="2" d="M16 17H12H8"/>
                                        </svg>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    </div>
                    
                    <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">{{ $artwork['title'] ?? 'Untitled' }}</h3>
                        <div class="flex row-end-auto">
                            <button 
                                wire:click="toggleFavorite({{ $artwork['id'] }}, true)"
                                class="flex-shrink-0 transition-colors duration-200 p-1 
                                    {{ $this->isFavorited($artwork['id'], true) ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500' }}">
                                <svg class="w-5 h-5" 
                                    fill="{{ $this->isFavorited($artwork['id'], true) ? 'currentColor' : 'none' }}" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $this->getFavoriteCount($artwork['id'],$artwork['image_url'] ?? '') }}</span>
                            </div>
                        </div>
                    </div>
                    @if($artwork['description'])
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ Str::limit($artwork['description'], 80) }}</p>
                    @endif
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <p class="text-gray-600 dark:text-gray-400 mb-1">{{ $artwork['artist_display'] ?? 'Unknown Artist' }}</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm">{{ $artwork['date_display'] ?? 'Unknown Date' }}</p>
                    </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if(isset($artworksPagination) && $artworksPagination)
                <div class="mt-8">
                    <div class="flex justify-between items-center  bg-white p-2 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                        <nav class="flex items-center space-x-1 bg-white dark:bg-gray-800 rounded-lg p-2">
                            @if($artworksPagination['current_page'] > 1)
                                <button wire:click="previousArtworksPage" 
                                    class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 rounded-md transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                            @endif

                            @for($page = max(1, $artworksPagination['current_page'] - 2); $page <= min($artworksPagination['total_pages'], $artworksPagination['current_page'] + 2); $page++)
                                <button wire:click="goToArtworksPage({{ $page }})"
                                    class="px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200
                                        {{ $page == $artworksPagination['current_page'] 
                                            ? 'text-white bg-blue-600 hover:bg-blue-700' 
                                            : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    {{ $page }}
                                </button>
                            @endfor

                            @if($artworksPagination['current_page'] < $artworksPagination['total_pages'])
                                <button wire:click="nextArtworksPage"
                                    class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 rounded-md transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            @endif
                        </nav>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing {{ $artworksPagination['current_page'] }} to {{ $artworksPagination['total_pages'] }}
                            ({{ number_format($artworksPagination['total']) }} total artworks)
                        </div>
                    </div>
                </div>
            @endif
            </div>
        @endif

        @if($images && count($images) > 0)
            <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                <div class="px-6">
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-full shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Community Uploads
                </span>
                </div>
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($images as $image)
                <div class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-200 dark:border-gray-700">
                    <div class="relative overflow-hidden rounded-t-xl">
                    <img src="{{ Storage::url($image->file_path) }}" 
                         alt="{{ $image->title }}" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="text-white transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                            <button wire:click='downloadImage({{$image->id}})' class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7L12 14M12 14L15 11M12 14L9 11"/>
                                        <path stroke-linecap="round" stroke-width="2" d="M16 17H12H8"/>
                                    </svg>
                            </button>
                        </div>
                    </div>
                    </div>
                    
                    <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1 flex-1 mr-2">{{ $image->title }}</h3>
                        <button 
                        wire:click="toggleFavorite({{ $image->id }}, false)"
                        class="flex-shrink-0 transition-colors duration-200 p-1 
                            {{ $this->isFavorited($image->id) ? 'text-red-500 hover:text-red-600' : 'text-gray-400 hover:text-red-500' }}">
                        <svg class="w-5 h-5" 
                             fill="{{ $this->isFavorited($image->id) ? 'currentColor' : 'none' }}" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        </button>
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ $this->getFavoriteCount($image->id,$image->file_path) }}</span>
                        </div>
                    </div>
                    @if($image->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ Str::limit($image->description, 80) }}</p>
                    @endif
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $image->user->name ?? 'Unknown' }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $image->created_at->format('M d, Y') }}</span>
                    </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="w-full">
                <div class="mt-8">
                    {{ $images->links('livewire.custom-pagination') }}
                </div>
            </div>
            </div>
        @endif
    </div>
</x-filament::page>
