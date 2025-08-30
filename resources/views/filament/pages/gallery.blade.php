<x-filament::page>
    <h1 class="text-2xl font-bold mb-6">Image Gallery</h1>
    <div class="grid grid-cols-3 gap-4">
        @foreach($images as $image)
            <div class="border rounded shadow">
                <img src="{{ Storage::url($image->file_path) }}" 
                     alt="{{ $image->title }}" 
                     class="w-full h-48 object-cover rounded-t">
                <div class="p-2 text-center">
                    <p class="font-semibold">{{ $image->title }}</p>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::page>
