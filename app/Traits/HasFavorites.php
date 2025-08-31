<?php

namespace App\Traits;

use App\Models\Favorite;
use App\Models\Image;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait HasFavorites
{
    
    // Instnce methods for livewire use
    public function toggleFavorite($imageId)
    {
        return self::toggleFavoriteStatic($imageId);
    }

    
    public function isFavorited($imageId)
    {
        return self::isFavoritedStatic($imageId);
    }

    
    public function getFavoriteCount($imageId)
    {
        return self::getFavoriteCountStatic($imageId);
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
  
    public static function toggleFavoriteStatic($imageId)
    {
        if (!Auth::check()) {
            Notification::make()
                ->title('You must be logged in to add favorites!')
                ->warning()
                ->send();
            return null;
        }

        $image = Image::find($imageId);
        if (!$image) {
            Notification::make()
                ->title('Error Occurred!')
                ->danger()
                ->send();
            return null;
        }

        $userId = Auth::id();
        $imageUrl = asset('storage/' . $image->file_path);
        
        $existingFavorite = Favorite::where('user_id', $userId)
            ->where('image_url', $imageUrl)
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

    public static function isFavoritedStatic($imageId)
    {
        if (!Auth::check()) {
            return false;
        }

        $image = Image::find($imageId);
        if (!$image) {
            return false;
        }

        $imageUrl = asset('storage/' . $image->file_path);
        
        return Favorite::where('user_id', Auth::id())
            ->where('image_url', $imageUrl)
            ->exists();
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

    public static function getFavoriteCountStatic($imageId)
    {
        $image = Image::find($imageId);
        if (!$image) {
            return 0;
        }

        $imageUrl = asset('storage/' . $image->file_path);
        
        return Favorite::where('image_url', $imageUrl)->count();
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
