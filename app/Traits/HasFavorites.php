<?php

namespace App\Traits;

use App\Models\Favorite;
use App\Models\Image;
use App\Services\ArtInstituteService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait HasFavorites
{
    
    // Instance methods for livewire use
    public function toggleFavorite($imageId, $apiImg = false)
    {
        return self::toggleFavoriteStatic($imageId, $apiImg);
    }

    
    public function isFavorited($imageId, $apiImg = null)
    {
        // If not explicitly specified, try to determine based on context
        if ($apiImg === null) {
            // Default to false (local image) for backward compatibility
            $apiImg = false;
        }
        return self::isFavoritedStatic($imageId, $apiImg);
    }

    
    public function getFavoriteCount($imageId,$imageUrl)
    {
        return self::getFavoriteCountStatic($imageId,$imageUrl);
    }


    public function getUserFavorites()
    {
        return self::getUserFavoritesStatic();
    }

    
    public function removeAllFavoritesForImage($imageId)
    {
        return self::removeAllFavoritesForImageStatic($imageId);
    }

    
    public function removeFavorite($favoriteId)
    {
        return self::removeFavoriteById($favoriteId);
    }

    // the favorite functions
  
    public static function toggleFavoriteStatic($imageId, $apiImg = false)
    {
        if (!Auth::check()) {
            Notification::make()
                ->title('You must be logged in to add favorites!')
                ->warning()
                ->send();
            return null;
        }

        $userId = Auth::id();
        
        if ($apiImg) {
            
            $artService = app(ArtInstituteService::class);
            $artwork = $artService->getArtwork($imageId);

            $imageUrl = $artwork['image_url'];
            $title = $artwork['title'];
            
            $existingFavorite = Favorite::where('user_id', $userId)
                ->where('img_id', $imageId)
                ->where('api_image', true)
                ->first();

            if ($existingFavorite) {
                $existingFavorite->delete();
                Notification::make()
                    ->title('Image removed from favorites!')
                    ->success()
                    ->send();
                return false;
            } else {
                Favorite::create([
                    'user_id' => $userId,
                    'img_id' => $imageId,
                    'image_url' => $imageUrl,
                    'title' => $title,
                    'api_image' => true
                ]);
                Notification::make()
                    ->title('Added to favorites!')
                    ->success()
                    ->send();
                return true;
            }
        } else {

            $image = Image::find($imageId);
            if (!$image) {
                Notification::make()
                    ->title('Error Occurred!')
                    ->danger()
                    ->send();
                return null;
            }

            $imageUrl = asset('storage/' . $image->file_path);
            
            $existingFavorite = Favorite::where('user_id', $userId)
                ->where('img_id', $imageId)
                ->where('api_image', false)
                ->first();

            if ($existingFavorite) {
                $existingFavorite->delete();
                Notification::make()
                    ->title('Image removed from favorites!')
                    ->success()
                    ->send();
                return false;
            } else {
                Favorite::create([
                    'user_id' => $userId,
                    'img_id' => $imageId,
                    'image_url' => $imageUrl,
                    'title' => $image->title,
                    'api_image' => false
                ]);
                Notification::make()
                    ->title('Added to favorites!')
                    ->success()
                    ->send();
                return true;
            }
        }
    }

    public static function isFavoritedStatic($imageId, $apiImg = false)
    {
        if (!Auth::check()) {
            return false;
        }

        if ($apiImg) {
            return Favorite::where('user_id', Auth::id())
                ->where('img_id', $imageId)
                ->where('api_image', true)
                ->exists();
        } else {
            $image = Image::find($imageId);
            if (!$image) {
                return false;
            }

            return Favorite::where('user_id', Auth::id())
                ->where('img_id', $imageId)
                ->where('api_image', false)
                ->exists();
        }
    }

    public static function getUserFavoritesStatic()
    {
        if (!Auth::check()) {
            return collect();
        }

        return Favorite::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getFavoriteCountStatic($imageId,$imageUrl)
    {
        $image = Image::find($imageId);
        
        if ($image) {
            return Favorite::where('img_id', $imageId)
                        ->where('api_image', false)
                        ->count();
        } else {
            return Favorite::where('img_id', $imageId)
                        ->where('api_image', true)
                        ->count();
        }
    }

    public static function removeAllFavoritesForImageStatic($imageId)
    {
        $image = Image::find($imageId);
        if (!$image) {
            return 0;
        }

        $imageUrl = asset('storage/' . $image->file_path);
        
        return Favorite::where('image_url', $imageUrl)->delete();
    }

    public static function removeFavoriteById($favoriteId)
    {
        if (!Auth::check()) {
            Notification::make()
                ->title('You must be logged in!')
                ->warning()
                ->send();
            return false;
        }

        $favorite = Favorite::where('id', $favoriteId)
            ->where('user_id', Auth::id())
            ->first();

        if ($favorite) {
            $favorite->delete();
            Notification::make()
                ->title('Image removed from favorites!')
                ->success()
                ->send();
            return true;
        }

        Notification::make()
            ->title('Favorite not found!')
            ->danger()
            ->send();
        return false;
    }

}
