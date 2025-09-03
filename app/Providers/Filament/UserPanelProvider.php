<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('user')
            ->path('user')
            // ->login()  // removed for use breeze auth
            ->homeUrl('/')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
             ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('
                    <div class="flex items-center space-x-4">
                        <!-- Navigation Links -->
                        <nav class="hidden md:flex items-center space-x-6">
                            <a href="{{ route(\'home\') }}" 
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-md">
                                  {{__(\'layouts.panel-topbar.links.home\')}}
                            </a>
                            @auth
                                <a href="{{ url(\'/user/images\') }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-md transition-all duration-200">
                                    {{__(\'layouts.panel-topbar.links.my-imgs\')}}
                                </a>
                                <a href="{{ url(\'/user/favorites\') }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-md transition-all duration-200">
                                    {{__(\'layouts.panel-topbar.links.favorites\')}}
                                </a>
                            @endauth
                        </nav> 
                    </div>
                ')
            )
            ->viteTheme('resources/css/filament/user/theme.css')
            ->brandLogo(asset('images/logo.png'))
            ->brandName('ArtGallery')
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
                // Gallery::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->navigation(false) //false to remove the sidebar
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
{
    Filament::serving(function () {
        $panel = Filament::getCurrentPanel();

        $panel->userMenuItems([
            'my-images' => MenuItem::make()
                ->label(__('layouts.panel-topbar.user-dropdown.my-imgs'))
                ->url('/user/images')
                ->icon('heroicon-o-photo'),
            'favorites' => MenuItem::make()
                ->label(__('layouts.panel-topbar.user-dropdown.favorites'))
                ->url('/user/favorites')
                ->icon('heroicon-o-heart')
        ] + $panel->getUserMenuItems());
    });
}
}
