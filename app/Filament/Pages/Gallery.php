<?php

namespace App\Filament\Pages;

use App\Models\Image;
use Filament\Pages\Page;

class Gallery extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.gallery';    

    public function getViewData(): array
    {
        $image = Image::all();
        return [
            'images' => $image
        ];
    }

}
