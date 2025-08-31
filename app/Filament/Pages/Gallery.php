<?php

namespace App\Filament\Pages;

use App\Models\Favorite;
use App\Models\Image;
use App\Services\ArtInstituteService;
use App\Traits\HasFavorites;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Gallery extends Page
{
    use HasFavorites, WithPagination;
    
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.gallery';
    
    public int $artworksPage = 1;
    public int $imagesPage = 1;
    public int $perPage = 12;

    public function getHeading(): string
    {
        return ''; // Remove page head
    }

    public function getViewData(): array
    {
        $images = Image::with('user')
            ->latest()
            ->paginate($this->perPage, ['*'], 'images_page', $this->imagesPage);
        
        $userFavorites = [];
        
        if (Auth::check()) {
            $userFavorites = Favorite::where('user_id', Auth::id())
                ->pluck('image_url')
                ->toArray();
        }

        $artService = app(ArtInstituteService::class);
        $result = $artService->getArtworks($this->perPage, $this->artworksPage);

        return [
            'images' => $images,
            'userFavorites' => $userFavorites,
            'artworks' => $result['artworks'],
            'artworksPagination' => $result['pagination'],
            'artworksConfig' => $result['config']
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

    public function nextImagesPage()
    {
        $this->imagesPage++;
    }

    public function previousImagesPage()
    {
        if ($this->imagesPage > 1) {
            $this->imagesPage--;
        }
    }

    public function goToArtworksPage($page)
    {
        $this->artworksPage = $page;
    }

    public function goToImagesPage($page)
    {
        $this->imagesPage = $page;
    }
}
