<?php

namespace App\Filament\Pages;

use App\Models\Favorite;
use App\Models\Image;
use App\Traits\HasFavorites;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Gallery extends Page
{
    use HasFavorites;
    
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.gallery';    

    public function getHeading(): string
    {
        return ''; // Remmove page heead
    }

    public function getViewData(): array
    {
        $images = Image::all();
        $userFavorites = [];
        
        if (Auth::check()) {
            $userFavorites = Favorite::where('user_id', Auth::id())
                ->pluck('image_url')
                ->toArray();
        }
        
        return [
            'images' => $images,
            'userFavorites' => $userFavorites
        ];
    }
}
