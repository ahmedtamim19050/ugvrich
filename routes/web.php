<?php

use App\Http\Controllers\ConsultancyRequestController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/research', [PageController::class, 'research'])->name('research');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{serviceCategory}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/experts', [ExpertController::class, 'index'])->name('experts.index');
Route::get('/experts/{expert}', [ExpertController::class, 'show'])->name('experts.show');

Route::get('/news', [PostController::class, 'index'])->name('news.index');
Route::get('/news/{post}', [PostController::class, 'show'])->name('news.show');

Route::get('/contact/thank-you', [ConsultancyRequestController::class, 'thanks'])->name('consultancy.thanks');

Route::post('/consultancy-request', ConsultancyRequestController::class)
    ->middleware('throttle:8,1')
    ->name('consultancy.store');

Route::post('/subscribe', SubscriberController::class)
    ->middleware('throttle:8,1')
    ->name('subscribe');
