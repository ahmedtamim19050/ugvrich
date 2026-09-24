<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

// Bangla is the site's own language and answers at the root.
Route::middleware(SetLocale::class)->group(base_path('routes/public.php'));

// English is the same site, one segment deeper.
Route::prefix('en')->name('en.')->middleware(SetLocale::class)->group(base_path('routes/public.php'));
