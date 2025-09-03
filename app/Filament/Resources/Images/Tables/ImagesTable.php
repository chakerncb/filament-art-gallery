<?php

namespace App\Filament\Resources\Images\Tables;

use App\Models\Image;
use App\Traits\HasFavorites;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ImagesTable
{
    use HasFavorites;

    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn () => Image::where('user_id', Auth::user()->id))
            ->columns([
                Grid::make()
                    ->columns (1)
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->columns(1)
                                ->schema([
                                    ImageColumn::make('file_path')
                                        ->disk('public')
                                        ->height (height: 150)
                                        ->width (width: 120)
                                        ->extraImgAttributes( [
                                        'class' => 'rounded-md',
                                        ])
                                        ->height(100)
                                        ->action(
                                            Action::make('viewImage')
                                                ->modalContent(fn ($record) => new HtmlString('
                                                    <div class="flex justify-center">
                                                        <img src="' . asset('storage/' . $record->file_path) . '" 
                                                            alt="' . htmlspecialchars($record->title) . '" 
                                                            class="max-w-full max-h-[60vh] object-contain" />
                                                    </div>
                                                '))
                                                ->modalHeading(fn ($record) => $record->title)
                                                ->modalCancelAction(false)
                                                ->modalSubmitAction(false)
                                        ),
                                ])
                        ])->grow(false),

                        Stack::make([
                            TextColumn::make('title')
                                ->searchable(),

                            TextColumn::make('description')
                                ->searchable(),

                        ])->extraAttributes(['class' => 'space-y-2'])
                          ->grow(),
                    ])
                ])->contentGrid([
                    'md' => 2,
                    'xl' => 3
                ])  
            ->filters([
                //
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Artist'),
                    
                SelectFilter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Created Date Range'),
            ])
            ->recordUrl('')
            ->recordActions([
                Action::make('Favorite')
                ->icon(fn ($record) => self::isFavoritedStatic($record->id) ? 'heroicon-s-heart' : 'heroicon-o-heart')
                ->name('.')
                ->color('danger')
                ->action(function ($record) {
                    self::toggleFavoriteStatic($record->id);
                }),
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->name(__('pages.my-iamges.buttons.download'))
                    ->tooltip('Download file')
                    ->action(function ($record) {
                        $filePath = storage_path('app/public/' . $record->file_path);
                        $mimeType = mime_content_type($filePath);
                        $extension = pathinfo($record->file_path, PATHINFO_EXTENSION);
                        $filename = $record->title . '.' . $extension;
                        
                        return response()->download(
                            $filePath,
                            $filename,
                            ['Content-Type' => $mimeType]
                        );
                    }),
                ActionGroup::make([
                   EditAction::make(),
                   DeleteAction::make(),
                   // ViewAction::make()
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
