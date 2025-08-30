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
        <h1 class="text-3xl font-bold mb-8 text-center">Art Gallery</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($images as $image)
                <div class="group cursor-pointer"
                     @click="openModal({
                         id: {{ $image->id }},
                         title: '{{ addslashes($image->title) }}',
                         description: '{{ addslashes($image->description ?? '') }}',
                         file_path: '{{ $image->file_path }}',
                         user_name: '{{ addslashes($image->user->name ?? 'Unknown') }}',
                         created_at: '{{ $image->created_at->format('M d, Y \a\t H:i') }}',
                         file_size: '{{ file_exists(storage_path('app/public/' . $image->file_path)) ? number_format(filesize(storage_path('app/public/' . $image->file_path)) / 1024, 2) . ' KB' : 'Unknown' }}',
                         file_type: '{{ strtoupper(pathinfo($image->file_path, PATHINFO_EXTENSION)) }}'
                     })">
                    <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ Storage::url($image->file_path) }}" 
                             alt="{{ $image->title }}" 
                             class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity duration-300 flex items-center justify-center">
                            <div class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $image->title }}</h3>
                        @if($image->description)
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($image->description, 50) }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

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
            
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-75"></div>
            
            <!-- Modal content -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                    
                    <!-- Close button -->
                    <button @click="closeModal()" 
                            class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 bg-white dark:bg-gray-700 rounded-full p-2 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- Modal body -->
                    <div class="p-6">
                        <!-- Image -->
                        <div class="flex justify-center mb-6">
                            <img :src="selectedImage ? '{{ Storage::url('') }}' + selectedImage.file_path : ''" 
                                 :alt="selectedImage ? selectedImage.title : ''"
                                 class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg">
                        </div>
                        
                        <h2 x-text="selectedImage ? selectedImage.title : ''" 
                            class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-4">
                        </h2>                        
                        <div class="flex justify-center mb-4 gap-4">
                            <button @click="showDetails = !showDetails"
                                    class="px-6 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors duration-200 flex items-center gap-2">
                                <span x-text="showDetails ? 'Hide Details' : 'More Details'"></span>
                                <svg class="w-4 h-4 transition-transform duration-200"
                                     :class="showDetails ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Download Button -->
                            <button @click="downloadImage()"
                               class="px-6 py-2 bg-green-600 dark:bg-green-500 text-white rounded-lg hover:bg-green-700 dark:hover:bg-green-600 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download
                            </button>
                        </div>
                        
                        <!-- Details section -->
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
