<x-filament::page>
    <div >
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-12">
            <div class="text-center lg:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Art Gallery
            </h1>
            <div class="w-24 h-1 bg-gradient-to-r from-primary-600 to-indigo-600 mx-auto lg:mx-0 mb-4"></div>
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
                   class="w-full px-4 py-3 pl-12 pr-4 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300">
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
                <div class="flex-grow h-px bg-gradient-twheno-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                <div class="px-6">
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-info-600 to-primary-600 text-white text-sm font-semibold rounded-full shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m3 0H4a1 1 0 00-1 1v14a1 1 0 001 1h16a1 1 0 001-1V5a1 1 0 00-1-1zM9 9h6M9 13h6m-3 4h.01"></path>
                    </svg>
                    Art Institute of Chicago
                </span>
                </div>
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
            </div>

            <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                @foreach($artworks as $artwork)
                <div 
                   wire:click="openImageModal({{ $artwork['id'] }}, true)"
                   class="break-inside-avoid group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 mb-6">
                    <div class="relative cursor-pointer">
                        @if(isset($artwork['image_url']) && $artwork['image_url'])
                            <img src="{{ $artwork['image_url_small'] ?? $artwork['image_url'] }}" 
                                 alt="{{ $artwork['title'] ?? 'Artwork' }}" 
                                 class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105"
                                 loading="lazy">
                        @else
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="absolute top-4 right-4 flex gap-2 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <button 
                                    wire:click.stop="toggleFavorite({{ $artwork['id'] }}, true)"
                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 
                                        {{ $this->isFavorited($artwork['id'], true) ? 'text-danger-400' : 'text-white' }}">
                                    <svg class="w-5 h-5" 
                                         fill="{{ $this->isFavorited($artwork['id'], true) ? 'currentColor' : 'none' }}" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                
                                
                                <button 
                                    wire:click.stop='downloadImage({{$artwork["id"]}},{{true}})' 
                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <h3 class="font-semibold text-lg mb-1 line-clamp-2">{{ $artwork['title'] ?? 'Untitled' }}</h3>
                                @if($artwork['description'])
                                    <p class="text-sm text-gray-200 line-clamp-2 mb-2">{{ Str::limit($artwork['description'], 100) }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between text-xs text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        {{ $artwork['artist_display'] ?? 'Unknown Artist' }}
                                    </span>
                                    <span>{{ $artwork['date_display'] ?? 'Unknown Date' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($this->getFavoriteCount($artwork['id'],$artwork['image_url'] ?? '') > 0)
                        <div class="absolute top-4 left-4 bg-black/50 backdrop-blur-sm rounded-full px-2 py-1 text-white text-xs flex items-center gap-1">
                            <svg class="w-3 h-3 text-danger-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $this->getFavoriteCount($artwork['id'],$artwork['image_url'] ?? '') }}
                        </div>
                        @endif
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
                                            ? 'text-white bg-primary-600 hover:bg-primary-700' 
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
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-success-600 to-emerald-600 text-white text-sm font-semibold rounded-full shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Community Uploads
                </span>
                </div>
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
            </div>

            <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                @foreach($images as $image)
                <div
                    wire:click="openImageModal({{ $image->id }}, false)" 
                    class="break-inside-avoid group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 mb-6">
                    <div class="relative cursor-pointer">
                        <img src="{{ Storage::url($image->file_path) }}" 
                             alt="{{ $image->title }}" 
                             class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105"
                             loading="lazy">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="absolute top-4 right-4 flex gap-2 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <button 
                                    wire:click.stop="toggleFavorite({{ $image->id }}, false)"
                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 
                                        {{ $this->isFavorited($image->id) ? 'text-danger-400' : 'text-white' }}">
                                    <svg class="w-5 h-5" 
                                         fill="{{ $this->isFavorited($image->id) ? 'currentColor' : 'none' }}" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                
                                <button 
                                    wire:click.stop='downloadImage({{$image->id}})' 
                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <h3 class="font-semibold text-lg mb-1 line-clamp-2">{{ $image->title }}</h3>
                                @if($image->description)
                                    <p class="text-sm text-gray-200 line-clamp-2 mb-2">{{ Str::limit($image->description, 100) }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between text-xs text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        {{ $image->user->name ?? 'Unknown' }}
                                    </span>
                                    <span>{{ $image->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($this->getFavoriteCount($image->id,$image->file_path) > 0)
                        <div class="absolute top-4 left-4 bg-black/50 backdrop-blur-sm rounded-full px-2 py-1 text-white text-xs flex items-center gap-1">
                            <svg class="w-3 h-3 text-danger-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $this->getFavoriteCount($image->id,$image->file_path) }}
                        </div>
                        @endif
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

    <x-image-modal 
        :showModal="$showModal"
        :selectedImage="$selectedImage"
        :isApiImage="$isApiImage"
        :relatedImages="$relatedImages"/>

</x-filament::page>
