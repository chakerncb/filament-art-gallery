<?php

use App\Filament\Pages\Gallery;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', Gallery::class)->name('home');

require __DIR__.'/auth.php';
