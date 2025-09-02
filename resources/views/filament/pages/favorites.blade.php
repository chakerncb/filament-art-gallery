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

        @if($favorites && count($favorites) > 0)
            <div class="mb-12">
            <div class="flex items-center mb-8">
                    <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                    <div class="px-6">
                        <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white text-sm font-semibold rounded-full shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            {{ count($favorites) }} Favorite{{ count($favorites) > 1 ? 's' : '' }}
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

   <x-image-modal 
        :showModal="$showModal"
        :selectedImage="$selectedImage"
        :isApiImage="$isApiImage"
        :relatedImages="$relatedImages"/>
        
</x-filament::page>
