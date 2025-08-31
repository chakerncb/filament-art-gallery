<?php

namespace App\Filament\Pages;

use App\Models\Favorite;
use App\Traits\HasFavorites;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Favorites extends Page
{
    use HasFavorites;
    protected static ?string $navigationLabel = 'My Favorites';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.favorites';

     public function getHeading(): string
    {
        return ''; // Remmove page heead
    }
    
    public function getViewData(): array
    {
        return [
            'favorites' => $this->getUserFavorites()
        ];
    }
}
