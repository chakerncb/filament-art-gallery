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
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Gallery extends Page
{
    use HasFavorites, WithPagination;
    
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.gallery';
    
    public int $artworksPage = 1;
    public int $perPage = 12;

    public string $query = '';

    protected string $paginationTheme = 'tailwind';

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
          $apiResult = $artService->getArtworks($this->perPage, $this->artworksPage);
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
        // Reset pagination when searching
        $this->artworksPage = 1;
        $this->resetPage();
        $this->getViewData();
    }

    public function downloadImage($imageId, $apiImg = false) {
        if($apiImg){
            $artService = app(ArtInstituteService::class);
            $imageUrl = $artService->buildImageUrl($imageId);
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
            }, "{$imageId}.jpg");
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
}
