<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtWorkController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// --- PUBLIC ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// Layout Components
Route::get('/navbar', fn() => view('component.layout.navbar'))->name('navbar');
Route::get('/footer', fn() => view('component.layout.footer'))->name('footer');

// Gallery
Route::get('/gallery', [ArtWorkController::class, 'gallery'])->name('gallery');
Route::get('/review_gallery', fn() => view('component.gallery.review_gallery'))->name('review_gallery');

// Artists
Route::get('/artists', [ArtWorkController::class, 'allArtists'])->name('artists');
Route::get('/profile_art/{id?}', [ArtWorkController::class, 'profile'])->name('profile_art');

// Login
$adminPath = env('ADMIN_URL', 'login');
Route::get('/' . $adminPath, fn() => view('admin.form_login'))->name('login');
Route::post('/api/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES ---
Route::middleware(['auth'])->group(function () {

    // Dashboard & Sidebar
    Route::get('/master', fn() => view('admin.dashboard.master'))->name('master');
    Route::get('/sidebar', fn() => view('admin.dashboard.layouts.sidebar'))->name('sidebar');

    // Main Features
    Route::get('/transactions', [DashboardController::class, 'index'])->name('transactions');
    Route::get('/collectors', fn() => view('admin.dashboard.features.collectors'))->name('collectors');
    Route::get('/submit_artworks', [ArtWorkController::class, 'index'])->name('submit_artworks');

    // Tools & Editing
    Route::get('/trending', fn() => view('admin.dashboard.tools.trending'))->name('trending');
    Route::get('/add_product', [ArtWorkController::class, 'create'])->name('add_product');
    Route::get('/edit_product/{id}', [ArtWorkController::class, 'edit'])->name('edit_product');

    // API — Auth
    Route::post('/api/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/api/logout', [AuthController::class, 'logout']);

    // API — Artworks
    Route::post('/api/artworks/store', [ArtWorkController::class, 'store']);
    Route::post('/api/artworks/store-multiple', [ArtWorkController::class, 'storeMultiple']);
    Route::post('/api/artworks/store-collection', [ArtWorkController::class, 'storeCollection']);
    Route::get('/api/artworks/collection/{id}', [ArtWorkController::class, 'getCollection']);
    Route::delete('/api/artworks/collection/{id}', [ArtWorkController::class, 'destroyCollection']);

    // API — Dashboard
    Route::get('/api/sold-artworks/{category}', [DashboardController::class, 'getSoldArtworks']);
});