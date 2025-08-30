<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function download(Image $image)
    {
        $filePath = storage_path('app/public/' . $image->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        $mimeType = mime_content_type($filePath);
        $extension = pathinfo($image->file_path, PATHINFO_EXTENSION);
        $filename = $image->title . '.' . $extension;
        
        return response()->download(
            $filePath,
            $filename,
            ['Content-Type' => $mimeType]
        );
    }
}
