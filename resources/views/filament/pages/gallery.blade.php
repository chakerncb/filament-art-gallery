{{-- <style>
    .fi-topbar-ctn { display: none !important; }
    .fi-main-ctn { padding-top: 0 !important; }
</style> --}}
<x-filament::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <div x-data="{ 
        showModal: false, 
        showDetails: false,
        selectedImage: null,
        openModal(image) {
            this.selectedImage = image;
            this.showModal = true;
            this.showDetails = false;
        },
        closeModal() {
            this.showModal = false;
            this.showDetails = false;
            this.selectedImage = null;
        },
        downloadImage() {
            if (this.selectedImage) {
                if (this.selectedImage.type === 'api') {
                    let fullResUrl = this.selectedImage.file_path;
                    
                    if (fullResUrl.includes('/iiif/2/')) {
                        const matches = fullResUrl.match(/\/iiif\/2\/([^\/]+)/);
                        if (matches && matches[1]) {
                            const identifier = matches[1];
                            fullResUrl = `https://www.artic.edu/iiif/2/${identifier}/full/843,/0/default.jpg`;
                        }
                    }
                    
                    window.open(fullResUrl, '_blank');
                    return;
                }
                
                const link = document.createElement('a');
                link.href = '{{ Storage::url('') }}' + this.selectedImage.file_path;
                link.download = this.selectedImage.title + '.' + this.selectedImage.file_type.toLowerCase();
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    }">
        <!-- Header Section -->
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

        <!-- Public Artworks Section -->
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
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity duration-300 flex items-center justify-center">
                        <div class="text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 flex items-center gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200"
                             @click="openModal({
                            id: {{ $artwork['id'] ?? 0 }},
                            title: {{ json_encode($artwork['title'] ?? 'Untitled') }},
                            description: {{ json_encode($artwork['artist_display'] ?? '') }},
                            file_path: {{ json_encode($artwork['image_url'] ?? '') }},
                            type: 'api',
                            user_name: {{ json_encode($artwork['artist_display'] ?? 'Unknown') }},
                            created_at: {{ json_encode($artwork['date_display'] ?? 'Unknown') }},
                            file_size: 'Unknown',
                            file_type: 'Unknown'
                            })">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        
                        {{-- <div class="relative group/download">
                            <button @click="downloadImage()" 
                                class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7L12 14M12 14L15 11M12 14L9 11"/>
                                <path stroke-linecap="round" stroke-width="2" d="M16 17H12H8"/>
                            </svg>
                            </button>
                            
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover/download:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                            Download
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-2 h-2 bg-black rotate-45"></div>
                            </div>
                        </div> --}}
                        </div>
                    </div>
                    </div>
                    
                    <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">{{ $artwork['title'] ?? 'Untitled' }}</h3>
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
                    <p class="text-gray-600 dark:text-gray-400 mb-1">{{ $artwork['artist_display'] ?? 'Unknown Artist' }}</p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm">{{ $artwork['date_display'] ?? 'Unknown Date' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Artworks Pagination -->
            @if(isset($artworksPagination) && $artworksPagination)
                <div class="mt-8 flex justify-center">
                    <nav class="inline-flex items-center space-x-2">
                        @if($artworksPagination['current_page'] > 1)
                            <button wire:click="previousArtworksPage" 
                                class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </button>
                        @endif

                        @for($page = max(1, $artworksPagination['current_page'] - 2); $page <= min($artworksPagination['total_pages'], $artworksPagination['current_page'] + 2); $page++)
                            <button wire:click="goToArtworksPage({{ $page }})"
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                                    {{ $page == $artworksPagination['current_page'] 
                                        ? 'text-white bg-blue-600 hover:bg-blue-700' 
                                        : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                                {{ $page }}
                            </button>
                        @endfor

                        @if($artworksPagination['current_page'] < $artworksPagination['total_pages'])
                            <button wire:click="nextArtworksPage"
                                class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                                Next
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </nav>
                    
                    <div class="ml-4 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                        Page {{ $artworksPagination['current_page'] }} of {{ $artworksPagination['total_pages'] }}
                        ({{ number_format($artworksPagination['total']) }} total artworks)
                    </div>
                </div>
            @endif
            </div>
        @endif

        <!-- User Uploaded Images Section -->
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
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity duration-300 flex items-center justify-center">
                        <div class="text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 flex items-center gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200"
                             @click="openModal({
                            id: {{ $image->id }},
                            title: '{{ addslashes($image->title) }}',
                            description: '{{ addslashes($image->description ?? '') }}',
                            file_path: '{{ $image->file_path }}',
                            type: 'local',
                            user_name: '{{ addslashes($image->user->name ?? 'Unknown') }}',
                            created_at: '{{ $image->created_at->format('M d, Y \a\t H:i') }}',
                            file_size: '{{ file_exists(storage_path('app/public/' . $image->file_path)) ? number_format(filesize(storage_path('app/public/' . $image->file_path)) / 1024, 2) . ' KB' : 'Unknown' }}',
                            file_type: '{{ strtoupper(pathinfo($image->file_path, PATHINFO_EXTENSION)) }}'
                            })">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        
                        {{-- <div class="relative group/download">
                            <button @click="downloadImage()" 
                                class="bg-white/20 backdrop-blur-sm rounded-full p-3 hover:bg-white/30 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7L12 14M12 14L15 11M12 14L9 11"/>
                                <path stroke-linecap="round" stroke-width="2" d="M16 17H12H8"/>
                            </svg>
                            </button>
                            
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover/download:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                            Download
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-2 h-2 bg-black rotate-45"></div>
                            </div>
                        </div> --}}
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
            
            <!-- Images Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $images->links('custom-pagination') }}
            </div>
            </div>
        @endif

        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             @click.away="closeModal()"
             style="display: none;">
            
            <div class="fixed inset-0 bg-black bg-opacity-75"></div>
            
            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                    
                    <button @click="closeModal()" 
                            class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 bg-white dark:bg-gray-700 rounded-full p-2 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <div class="p-6">
                        <div class="flex justify-center mb-6">
                            <img :src="selectedImage ? (selectedImage.type === 'api' ? selectedImage.file_path : '{{ Storage::url('') }}' + selectedImage.file_path) : ''" 
                                 :alt="selectedImage ? selectedImage.title : ''"
                                 class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg">
                        </div>
                        
                        <h2 x-text="selectedImage ? selectedImage.title : ''" 
                            class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-4">
                        </h2>                        
                        <div class="flex justify-center mb-4 gap-4">
                            <button @click="showDetails = !showDetails"
                                class="px-8 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 dark:from-slate-500 dark:to-slate-600 dark:hover:from-slate-600 dark:hover:to-slate-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-3">
                                <span x-text="showDetails ? 'Hide Details' : 'View Details'" class="text-sm font-semibold"></span>
                                <svg class="w-5 h-5 transition-transform duration-300"
                                 :class="showDetails ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <button x-show="selectedImage" 
                                @click="$wire.toggleFavorite(selectedImage.id, selectedImage.type === 'api')"
                                class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 dark:from-red-500 dark:to-red-600 dark:hover:from-red-600 dark:hover:to-red-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-3">
                                <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm font-semibold">Favorite</span>
                            </button>

                            <button @click="downloadImage()"
                                class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 dark:from-emerald-500 dark:to-emerald-600 dark:hover:from-emerald-600 dark:hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span x-text="selectedImage && selectedImage.type === 'api' ? 'View Original' : 'Download'" class="text-sm font-semibold"></span>
                            </button>
                        </div>
                        
                        <div x-show="showDetails"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4"
                             class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            
                            <div class="space-y-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Artist</h4>
                                <p x-text="selectedImage ? selectedImage.user_name : ''" class="text-gray-700 dark:text-gray-300"></p>
                            </div>
                            
                            <div class="space-y-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Upload Date</h4>
                                <p x-text="selectedImage ? selectedImage.created_at : ''" class="text-gray-700 dark:text-gray-300"></p>
                            </div>
                            
                            <div class="space-y-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">File Size</h4>
                                <p x-text="selectedImage ? selectedImage.file_size : ''" class="text-gray-700 dark:text-gray-300"></p>
                            </div>
                            
                            <div class="space-y-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">File Type</h4>
                                <p x-text="selectedImage ? selectedImage.file_type : ''" class="text-gray-700 dark:text-gray-300"></p>
                            </div>
                            
                            <div class="space-y-2 md:col-span-2" x-show="selectedImage && selectedImage.description">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Description</h4>
                                <p x-text="selectedImage ? selectedImage.description : ''" class="text-gray-700 dark:text-gray-300"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
