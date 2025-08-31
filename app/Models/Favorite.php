<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'image_url',
        'title',
        'api_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
