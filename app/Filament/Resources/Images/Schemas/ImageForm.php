<?php

namespace App\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('pages.my-iamges.image-labels.title'))
                    ->required(),
                    
                Textarea::make('description')
                    ->label(__('pages.my-iamges.image-labels.description'))
                    ->columnSpanFull()
                    ->required(),

                FileUpload::make('file_path')
                    ->label(__('pages.my-iamges.image-labels.file-path'))
                    ->image()
                    ->required()
                    ->maxSize(6144) // 6MB
                    ->directory('images')
                    ->disk('public'),

                Hidden::make('user_id')
                    ->default(Auth::user()->id),
            ]);
    }
}
