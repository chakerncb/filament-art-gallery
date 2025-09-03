<x-filament::page>    
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                My Favorites
            </h1>
            <div class="w-24 h-1 bg-gradient-to-r from-red-500 to-pink-600 mx-auto mb-4"></div>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
                Your personal collection of favorite artworks
            </p>
        </div>

        <div class="w-full">
            <div class="mt-8">
                    <nav role="navigation" aria-label="SearchBox Navigation" class="flex items-center justify-between bg-white p-4 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center space-x-4 w-full">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                wire:model="query"
                                wire:keydown.debounce.300ms="search"
                                placeholder="Search your favorites..." 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                        </div>
                        <select 
                            wire:model="perPage"
                            wire:change="$refresh"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option }} per page</option>
                            @endforeach
                        </select>
                    </div>
                    </nav>
            </div>
        </div>


        @if($favorites && count($favorites) > 0)
            <div class="mb-12">
            <div class="flex items-center mb-8">
                    <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                    <div class="px-6">
                        <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white text-sm font-semibold rounded-full shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                             {{ Auth::user()->favorites()->count() }} Favorite{{ Auth::user()->favorites()->count() > 1 ? 's' : '' }}
                        </span>
                    </div>
                    <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                </div>

            <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                @foreach($favorites as $favorite)
                <div
                    wire:click="openFavoriteModal({{ $favorite->id }}, {{ $favorite->api_image ? 'true' : 'false' }})" 
                    class="break-inside-avoid group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 mb-6">
                    <div class="relative cursor-pointer">
                        @if($favorite->api_image)
                          @if (isset($favorite->image_url) )
                             <img src="{{ $favorite->image_url }}" 
                                 alt="{{ $favorite->title }}" 
                                 class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center" style="display: none;">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                          @else
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                         @endif
                        @else
                          @if (isset($favorite->file_path))
                            <img src="{{ $favorite->file_path ? Storage::url($favorite->file_path) : $favorite->image_url }}" 
                                 alt="{{ $favorite->title }}" 
                                 class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105"
                                 loading="lazy">
                          @else
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                         @endif
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="absolute top-4 right-4 flex gap-2 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <button 
                                    wire:click.stop="toggleFavorite({{ $favorite->img_id }}, {{ $favorite->api_image ? 'true' : 'false' }})"
                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 text-danger-400">
                                    <svg class="w-5 h-5" 
                                         fill="currentColor" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                
                                <button 
                                    wire:click.stop='downloadFavorite({{ $favorite->id }})' 
                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <h3 class="font-semibold text-lg mb-1 line-clamp-2">{{ $favorite->title }}</h3>
                                @if($favorite->description)
                                    <p class="text-sm text-gray-200 line-clamp-2 mb-2">{{ Str::limit($favorite->description, 100) }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between text-xs text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        {{ $favorite->user->name ?? 'Unknown' }}
                                    </span>
                                    <span>{{ $favorite->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($this->getFavoriteCount($favorite->img_id, $favorite->image_url) > 0)
                        <div class="absolute top-4 left-4 bg-black/50 backdrop-blur-sm rounded-full px-2 py-1 text-white text-xs flex items-center gap-1">
                            <svg class="w-3 h-3 text-danger-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $this->getFavoriteCount($favorite->img_id, $favorite->image_url) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="w-full">
                <div class="mt-8">
                    {{ $favorites->links('livewire.custom-pagination') }}
                </div>
            </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 mb-8 text-gray-400 dark:text-gray-600">
                    <svg fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No favorites yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Start exploring the gallery and add favorites to your favorites by clicking the heart icon.
                </p>
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Browse Gallery
                </a>
            </div>
        @endif
    </div>

     @if($showModal && !empty($selectedImage))
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-75 flex items-center justify-center p-4"
         wire:click="closeModal">
        
        <div class="relative w-full max-w-7xl bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden"
             wire:click.stop>
            <button wire:click="closeModal" 
                    class="absolute top-4 right-4 z-10 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="flex flex-col lg:flex-row max-h-[90vh]">
                <div class="flex-1 lg:w-2/3 bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-4 lg:p-8">
                    <div class="relative w-full h-full flex items-center justify-center">
                        <img src="{{ $selectedImage['image_url_large'] ?? $selectedImage['image_url'] }}" 
                             alt="{{ $selectedImage['title'] }}"
                             class="max-w-full max-h-[60vh] lg:max-h-[75vh] object-contain rounded-lg shadow-lg">
                    </div>
                </div>

                <div class="flex-1 lg:w-1/3 flex flex-col bg-white dark:bg-gray-900">
                    <div class="flex-shrink-0 p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="mb-4">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $selectedImage['title'] }}
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-gray-400 mb-1">
                                {{ $selectedImage['artist'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                {{ $selectedImage['date'] }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button wire:click="downloadImage({{$selectedImage['id']}},{{ $isApiImage}})"
                                    class="flex items-center gap-2 px-4 py-2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors duration-200 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download
                            </button>
                            
                            <button wire:click="toggleCurrentImageFavorite"
                                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                <svg class="w-4 h-4 {{ $this->isFavorited($selectedImage['id'], $isApiImage) ? 'text-red-500' : 'text-gray-500' }}" 
                                     fill="{{ $this->isFavorited($selectedImage['id'], $isApiImage) ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm">{{ $selectedImage['favorites_count'] ?? 0 }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        @if(!empty($selectedImage['description']))
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $selectedImage['description'] }}
                            </p>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Details</h3>
                            <dl class="space-y-3">
                                @if(!empty($selectedImage['medium']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Medium</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['medium'] }}</dd>
                                </div>
                                @endif

                                @if(!empty($selectedImage['dimensions']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dimensions</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['dimensions'] }}</dd>
                                </div>
                                @endif

                                @if(!empty($selectedImage['department']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['department'] }}</dd>
                                </div>
                                @endif

                                @if(!empty($selectedImage['place_of_origin']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Place of Origin</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['place_of_origin'] }}</dd>
                                </div>
                                @endif

                                @if(!empty($selectedImage['classification']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Classification</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['classification'] }}</dd>
                                </div>
                                @endif

                                @if(isset($selectedImage['file_size']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">File Size</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['file_size'] }}</dd>
                                </div>
                                @endif

                                @if(isset($selectedImage['upload_date']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Upload Date</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedImage['upload_date'] }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        @if($isApiImage)
                            @if(!empty($selectedImage['credit_line']))
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Credit Line</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $selectedImage['credit_line'] }}
                                </p>
                            </div>
                            @endif

                            @if(!empty($selectedImage['provenance_text']))
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Provenance</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {!! nl2br(e($selectedImage['provenance_text'])) !!}
                                </p>
                            </div>
                            @endif

                            @if(!empty($selectedImage['exhibition_history']))
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Exhibition History</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {!! nl2br(e($selectedImage['exhibition_history'])) !!}
                                </p>
                            </div>
                            @endif

                            @if(!empty($selectedImage['publication_history']))
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Publication History</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {!! nl2br(e($selectedImage['publication_history'])) !!}
                                </p>
                            </div>
                            @endif

                            @if(!empty($selectedImage['inscriptions']))
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Inscriptions</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {!! nl2br(e($selectedImage['inscriptions'])) !!}
                                </p>
                            </div>
                            @endif

                            @if(isset($selectedImage['is_on_view']) && $selectedImage['is_on_view'])
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800 dark:text-green-200">Currently on view</span>
                                </div>
                                @if(!empty($selectedImage['gallery_title']))
                                <p class="text-sm text-green-700 dark:text-green-300 mt-1">{{ $selectedImage['gallery_title'] }}</p>
                                @endif
                            </div>
                            @endif
                        @endif

                        @if(!empty($relatedImages))
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                More by {{ $selectedImage['artist'] }}
                            </h3>
                
                        <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                            @foreach($relatedImages as $relatedImg)
                            <div 
                            wire:click="openRelatedImage({{ $relatedImg['id'] }}, {{ $relatedImg['is_api'] ? 'true' : 'false' }})"
                            class="break-inside-avoid group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 mb-6">
                                <div class="relative cursor-pointer">
                                    @if(isset($relatedImg['image_url']) && $relatedImg['image_url'])
                                        <img src="{{ $relatedImg['image_url_small'] ?? $relatedImg['image_url'] }}" 
                                            alt="{{ $relatedImg['title'] ?? 'relatedImg' }}" 
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
                                                wire:click.stop="toggleFavorite({{ $relatedImg['id'] }}, true)"
                                                class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 
                                                    {{ $this->isFavorited($relatedImg['id'], true) ? 'text-danger-400' : 'text-white' }}">
                                                <svg class="w-5 h-5" 
                                                    fill="{{ $this->isFavorited($relatedImg['id'], true) ? 'currentColor' : 'none' }}" 
                                                    stroke="currentColor" 
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                            
                                            
                                            <button 
                                                wire:click.stop='downloadImage({{$relatedImg["id"]}},{{true}})' 
                                                class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-all duration-200 text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                            <h3 class="font-semibold text-sm mb-1 line-clamp-2">{{ $relatedImg['title'] ?? 'Untitled' }}</h3>
                                        </div>
                                    </div>
                                    
                                    @if($this->getFavoriteCount($relatedImg['id'],$relatedImg['image_url'] ?? '') > 0)
                                    <div class="absolute top-4 left-4 bg-black/50 backdrop-blur-sm rounded-full px-2 py-1 text-white text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3 text-danger-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $this->getFavoriteCount($relatedImg['id'],$relatedImg['image_url'] ?? '') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif ($showModal && empty($selectedImage))
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-75 flex items-center justify-center p-4"
         wire:click="closeModal">
        
        <div class="flex items-center justify-center min-h-screen">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
                </div>
                <p class="text-white text-lg font-medium">Loading...</p>
            </div>
        </div>

    </div>
    @endif
        
</x-filament::page>
