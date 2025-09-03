<?php

namespace App\Filament\Pages;

use App\Models\Favorite;
use App\Models\Image;
use App\Services\ArtInstituteService;
use App\Traits\HasFavorites;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Favorites extends Page
{
    use HasFavorites;
    use WithPagination;
    
    protected static ?string $navigationLabel = 'My Favorites';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.favorites';

    public bool $showModal = false;
    public array $selectedImage = [];
    public bool $isApiImage = false;
    public $selectedFavorite = null;

    public string $query = '';
    public int $perPage = 12;
    
    public array $perPageOptions = [12, 24, 48, 96];

    public function getHeading(): string
    {
        return ''; // Remove page head
    }
    
    public function getViewData(): array
    {
        if (!Auth::check()) {
            return ['favorites' => collect()];
        }

        $result = Favorite::where('user_id', Auth::id())
            ->with(['user'])
            ->orderBy('created_at', 'desc');

        if($this->query || $this->query != ''){
          $result->where('title' , 'like' , '%'.$this->query.'%');
        }
         $favorites = $result->paginate($this->perPage);

        foreach ($favorites as $favorite) {
            if (!$favorite->api_image) {
                $image = Image::find($favorite->img_id);
                if ($image) {
                    $favorite->file_path = $image->file_path;
                    $favorite->description = $image->description;
                    if (empty($favorite->title) && !empty($image->title)) {
                        $favorite->title = $image->title;
                    }
                }
            }
        }

        return [
            'favorites' => $favorites
        ];
    }

    public function openFavoriteModal($favoriteId, $apiImg = false)
    {
        $favorite = Favorite::where('id', $favoriteId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$favorite) {
            Notification::make()
                ->title('Favorite not found!')
                ->danger()
                ->send();
            return;
        }

        $this->selectedFavorite = $favorite;
        $this->isApiImage = $favorite->api_image;
        $this->showModal = true;

        
        if ($favorite->api_image) {

            $artService = app(ArtInstituteService::class);
            $favArtwork = $artService->getArtwork($favorite->img_id);

            $this->selectedImage = [
                'id' => $favArtwork['id'],
                'title' => $favArtwork['title'],
                'artist' => $favArtwork['artist_display'] ?? 'Unknown Artist',
                'date' => $favArtwork['date_display'] ?? 'Unknown Date',
                'image_url' => $favArtwork['image_url'],
                'image_url_large' => $favArtwork['image_url_large'] ?? $favArtwork['image_url'],
                'description' => $favArtwork['description'] ?? $favArtwork['short_description'],
                'medium' => $favArtwork['medium_display'],
                'dimensions' => $favArtwork['dimensions'],
                'department' => $favArtwork['department_title'],
                'place_of_origin' => $favArtwork['place_of_origin'],
                'classification' => $favArtwork['classification_title'],
                'credit_line' => $favArtwork['credit_line'] ?? '',
                'is_public_domain' => $favArtwork['is_public_domain'] ?? false,
                'artist_title' => $favArtwork['artist_title'] ?? '',
                'artwork_type_title' => $favArtwork['artwork_type_title'] ?? '',
                'thumbnail' => $favArtwork['thumbnail'] ?? '',
                'main_reference_number' => $favArtwork['main_reference_number'] ?? '',
                'is_on_view' => $favArtwork['is_on_view'] ?? false,
                'gallery_title' => $favArtwork['gallery_title'] ?? '',
                'favorites_count' => $this->getFavoriteCount($favorite->img_id, $favorite->image_url ?? '')
            ];
        } else {
            $image = Image::find($favorite->img_id);
            if ($image) {
                $this->selectedImage = [
                    'id' => $image->id,
                    'title' => $image->title,
                    'artist' => $image->user->name ?? 'Unknown Artist',
                    'date' => $image->created_at->format('Y'),
                    'image_url' => Storage::url($image->file_path),
                    'description' => $image->description,
                    'file_size' => $this->formatFileSize($image->file_size ?? 0),
                    'upload_date' => $image->created_at->format('M d, Y'),
                    'favorites_count' => $this->getFavoriteCount($image->id, $image->file_path)
                ];
            }
        }
    }

    public function openImageModal($imageId, $isApi = false)
    {
        $this->openFavoriteModal($imageId, $isApi);
    }

    private function formatFileSize($bytes)
    {
        if ($bytes == 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedImage = [];
        $this->isApiImage = false;
        $this->selectedFavorite = null;
    }

    public function downloadFavorite($favoriteId)
    {
        $favorite = Favorite::where('id', $favoriteId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$favorite) {
            Notification::make()
                ->title('Favorite not found!')
                ->danger()
                ->send();
            return;
        }

        if ($favorite->api_image) {
            if (!$favorite->image_url) {
                Notification::make()
                    ->title('Error While downloading the image!!')
                    ->danger()
                    ->send();
                return;
            }
            
           Notification::make()
                    ->title('Image Downloaded Successfully!')
                    ->success()
                    ->send();

            $imageUrl = $favorite->image_url;
            $title = $favorite->title;
            return response()->streamDownload(function () use ($imageUrl) {
                echo Http::get($imageUrl)->body();
            }, "{$title}.jpg");

        } else {
            $image = Image::find($favorite->img_id);
            if (!$image || !$image->file_path) {
                Notification::make()
                    ->title('Image file not found!')
                    ->danger()
                    ->send();
                return;
            }

            $filePath = storage_path('app/public/' . $image->file_path);
            
            if (!file_exists($filePath)) {
                Notification::make()
                    ->title('Image file not found on server!')
                    ->danger()
                    ->send();
                return;
            }

            $fileName = $image->title ? 
                \Illuminate\Support\Str::slug($image->title) . '.' . pathinfo($image->file_path, PATHINFO_EXTENSION) :
                basename($image->file_path);

            return response()->download($filePath, $fileName);
        }
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

    public function downloadImage($imageId, $isApi = false)
    {
        $favorite = Favorite::where('img_id', $imageId)
            ->where('user_id', Auth::id())
            ->where('api_image', $isApi)
            ->first();

        if ($favorite) {
            return $this->downloadFavorite($favorite->id);
        }

        Notification::make()
            ->title('Favorite not found!')
            ->danger()
            ->send();
    }

    public function search(){
        $this->getViewData();
    }
    
}