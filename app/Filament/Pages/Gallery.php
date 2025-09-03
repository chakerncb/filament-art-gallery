<?php

namespace App\Filament\Pages;

use App\Models\Favorite;
use App\Models\Image;
use App\Services\ArtInstituteService;
use App\Traits\HasFavorites;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Gallery extends Page
{
    use HasFavorites, WithPagination;
    
    protected static bool $shouldRegisterNavigation = false;

    protected static bool $shouldRegisterInNavigation = false;
    protected string $view = 'filament.pages.gallery';
    
    public int $artworksPage = 1;
    public int $perPage = 12;

    public string $query = '';

    protected string $paginationTheme = 'tailwind';

    // Modal properties
    public bool $showModal = false;
    public array $selectedImage = [];
    public bool $isApiImage = false;
    public array $relatedImages = [];

    public function getHeading(): string
    {
        return ''; // Remove page head
    }

    public function getViewData(): array
    {
        $result = Image::with('user')
            ->latest();
        if($this->query != '' ){
          $result = $result->where('title', 'like', '%' . $this->query . '%');
        }

        $images = $result->paginate($this->perPage);
        
        $userFavorites = [];
        
        if (Auth::check()) {
            $userFavorites = Favorite::where('user_id', Auth::id())
                ->pluck('image_url')
                ->toArray();
        }

        $artService = app(ArtInstituteService::class);
         $apiResult = [];
        if($this->query == '' ){
          $apiResult = $artService->getFeaturedArtworks($this->perPage, $this->artworksPage);
        }else{
          $apiResult = $artService->searchArtworks($this->query , $this->perPage,$this->artworksPage);
        }
        return [
            'images' => $images,
            'userFavorites' => $userFavorites,
            'artworks' => $apiResult['artworks'],
            'artworksPagination' => $apiResult['pagination'],
            'artworksConfig' => $apiResult['config']
        ];
    }

    public function nextArtworksPage()
    {
        $this->artworksPage++;
    }

    public function previousArtworksPage()
    {
        if ($this->artworksPage > 1) {
            $this->artworksPage--;
        }
    }

    public function goToArtworksPage($page)
    {
        $this->artworksPage = $page;
    }

    public function updatedQuery()
    {
        $this->artworksPage = 1;
        $this->resetPage();
    }

    public function search(){
        $this->artworksPage = 1;
        $this->resetPage();
        $this->getViewData();
    }

    public function downloadImage($imageId, $apiImg = false) {
        if($apiImg){
            $artService = app(ArtInstituteService::class);
            $artwork = $artService->getArtwork($imageId);

            $imageUrl = $artwork['image_url'];
            $title = $artwork['title'];

            if(!$imageUrl){
                 Notification::make()
                    ->title('Error While Downloading the image!')
                    ->danger()
                    ->send();
                    return;
            }

               Notification::make()
                    ->title('Image Downloaded Successfully!')
                    ->success()
                    ->send();

           
            return response()->streamDownload(function () use ($imageUrl) {
                echo Http::get($imageUrl)->body();
            }, "{$title}.jpg");
        }
        else{
           $image = Image::find($imageId);
           if(!$image){
                Notification::make()
                    ->title('Error While Downloading the image!')
                    ->danger()
                    ->send();
                    return;
           }
            
               Notification::make()
                    ->title('Image Downloaded Successfully!')
                    ->success()
                    ->send();

         return response()->download(storage_path('app/public/' . $image->file_path), $image->title . '.' . pathinfo($image->file_path, PATHINFO_EXTENSION));

        }
        
    }

    public function openImageModal($imageId, $isApi = false)
    {
        $this->isApiImage = $isApi;
        $this->showModal = true;
        
        if ($isApi) {
            $artService = app(ArtInstituteService::class);
            $artwork = $artService->getArtwork($imageId);
            
            $this->selectedImage = [
                'id' => $artwork['id'],
                'title' => $artwork['title'] ?? 'Untitled',
                'description' => $artwork['description'] ?? '',
                'image_url' => $artwork['image_url'],
                'image_url_large' => $artwork['image_url_large'] ?? $artwork['image_url'],
                'artist' => $artwork['artist_title'] ?? 'Unknown Artist',
                'date' => $artwork['date_display'] ?? '',
                'medium' => $artwork['medium_display'] ?? '',
                'dimensions' => $artwork['dimensions'] ?? '',
                'department' => $artwork['department_title'] ?? '',
                'place_of_origin' => $artwork['place_of_origin'] ?? '',
                'classification' => $artwork['classification_title'] ?? '',
                'credit_line' => $artwork['credit_line'] ?? '',
                'publication_history' => $artwork['publication_history'] ?? '',
                'exhibition_history' => $artwork['exhibition_history'] ?? '',
                'provenance_text' => $artwork['provenance_text'] ?? '',
                'inscriptions' => $artwork['inscriptions'] ?? '',
                'api_model' => $artwork['api_model'] ?? '',
                'api_link' => $artwork['api_link'] ?? '',
                'is_on_view' => $artwork['is_on_view'] ?? false,
                'gallery_title' => $artwork['gallery_title'] ?? '',
                'color' => $artwork['color'] ?? [],
                'latitude' => $artwork['latitude'] ?? null,
                'longitude' => $artwork['longitude'] ?? null,
                'created_at' => now()->format('M d, Y'),
                'favorites_count' => $this->getFavoriteCount($imageId, $artwork['image_url'] ?? ''),
            ];

            $this->getRelatedApiImages($artwork['artist_title'] ?? '');
        } else {
            $image = Image::with('user')->find($imageId);
            if (!$image) {
                return;
            }
            
            $this->selectedImage = [
                'id' => $image->id,
                'title' => $image->title,
                'description' => $image->description ?? '',
                'image_url' => asset('storage/' . $image->file_path),
                'image_url_large' => asset('storage/' . $image->file_path),
                'artist' => $image->user->name ?? 'Unknown Artist',
                'date' => $image->created_at->format('M d, Y'),
                'medium' => 'Digital Upload',
                'dimensions' => 'Variable',
                'department' => 'Community Gallery',
                'place_of_origin' => 'User Contribution',
                'classification' => 'Digital Art',
                'credit_line' => 'Uploaded by ' . ($image->user->name ?? 'Unknown User'),
                'created_at' => $image->created_at->format('M d, Y'),
                'favorites_count' => $this->getFavoriteCount($imageId, $image->file_path),
                'file_size' => $this->getFileSize($image->file_path),
                'upload_date' => $image->created_at->format('F j, Y \a\t g:i A'),
            ];

            $this->getRelatedLocalImages($image->user_id, $imageId);
        }
        
    }

    private function getRelatedApiImages($artistTitle)
    {
        if (empty($artistTitle)) {
            $this->relatedImages = [];
            return;
        }

        $artService = app(ArtInstituteService::class);
        $result = $artService->searchArtworks($artistTitle, 6, 1);
        
        $this->relatedImages = collect($result['artworks'] ?? [])
            ->filter(function($artwork) {
                return $artwork['id'] !== $this->selectedImage['id'];
            })
            ->take(5)
            ->map(function($artwork) {
                return [
                    'id' => $artwork['id'],
                    'title' => $artwork['title'] ?? 'Untitled',
                    'image_url' => $artwork['image_url_small'] ?? $artwork['image_url'],
                    'description'=> $artwork['description'] ?? '',
                    'artist' => $artwork['artist_title'] ?? 'Unknown Artist',
                    'is_api' => true,
                ];
            })
            ->toArray();
    }

    private function getRelatedLocalImages($userId, $excludeId)
    {
        $this->relatedImages = Image::with('user')
            ->where('user_id', $userId)
            ->where('id', '!=', $excludeId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($image) {
                return [
                    'id' => $image->id,
                    'title' => $image->title,
                    'image_url' => asset('storage/' . $image->file_path),
                    'description' => $image->description ?? '',
                    'artist' => $image->user->name ?? 'Unknown Artist',
                    'is_api' => false,
                ];
            })
            ->toArray();
    }

    private function getFileSize($filePath)
    {
        $fullPath = storage_path('app/public/' . $filePath);
        if (file_exists($fullPath)) {
            $bytes = filesize($fullPath);
            if ($bytes >= 1024 * 1024) {
                return round($bytes / (1024 * 1024), 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return round($bytes / 1024, 2) . ' KB';
            }
            return $bytes . ' bytes';
        }
        return 'Unknown';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedImage = [];
        $this->relatedImages = [];
        $this->isApiImage = false;
    }

    public function toggleCurrentImageFavorite()
    {
        if (empty($this->selectedImage)) {
            return;
        }
        
        $this->toggleFavorite($this->selectedImage['id'], $this->isApiImage);
        
        $this->selectedImage['favorites_count'] = $this->getFavoriteCount(
            $this->selectedImage['id'], 
            $this->isApiImage ? ($this->selectedImage['image_url'] ?? '') : $this->selectedImage['image_url']
        );
    }

    public function openRelatedImage($imageId, $isApi)
    {
        $this->openImageModal($imageId, $isApi);
    }
}
