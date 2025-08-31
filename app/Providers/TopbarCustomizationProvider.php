<?php

namespace App\Providers;

use App\Models\Favorite;
use App\Models\Image;
use App\Models\User;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Facades\Auth;

class TopbarCustomizationProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->topNavigation()
            ->navigationItems([
                NavigationItem::make('Gallery Overview')
                    ->icon('heroicon-o-photo')
                    ->badge(fn () => Image::count())
                    ->badgeColor('primary')
                    ->url('/admin')
                    ->sort(1),
                    
                NavigationItem::make('Favorites')
                    ->icon('heroicon-o-heart')
                    ->badge(fn () => Auth::check() ? Favorite::where('user_id', Auth::id())->count() : 0)
                    ->badgeColor('danger')
                    ->url('/admin/favorites')
                    ->sort(2),
                    
                NavigationItem::make('Public Gallery')
                    ->icon('heroicon-o-eye')
                    ->url('/admin/gallery')
                    ->openUrlInNewTab()
                    ->sort(3),
                    
                NavigationItem::make('Artists')
                    ->icon('heroicon-o-users')
                    ->badge(fn () => User::count())
                    ->badgeColor('success')
                    ->url('/admin/users')
                    ->sort(4),
            ]);
    }
}
