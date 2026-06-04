<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtWorkController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GoogleAuthController;

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

// Comments (read = public, write = Google auth checked in controller)
Route::get('/artworks/{artwork}/comments',  [CommentController::class, 'index']);
Route::post('/artworks/{artwork}/comments', [CommentController::class, 'store']);
Route::put('/artworks/{artwork}/comments/{comment}',    [CommentController::class, 'update']);
Route::delete('/artworks/{artwork}/comments/{comment}', [CommentController::class, 'destroy']);
Route::get('/artworks/{artwork}', [CommentController::class, 'show']);

// --- GOOGLE OAUTH ---
Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/google/logout',  [GoogleAuthController::class, 'logout'])->name('auth.google.logout');
Route::get('/auth/google/me',       [GoogleAuthController::class, 'me'])->name('auth.google.me');

// Login (admin)
$adminPath = env('ADMIN_URL', 'login');
Route::get('/' . $adminPath, fn() => view('admin.form_login'))->name('login');
Route::post('/api/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (admin) ---
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

    // API — Dashboard Stats
    Route::get('/api/dashboard/stats',           [DashboardController::class, 'getStats']);
    Route::get('/api/dashboard/visitors',        [DashboardController::class, 'getVisitorStats']);
    Route::get('/api/dashboard/visitors/years',  [DashboardController::class, 'getAvailableYears']);
    Route::get('/api/dashboard/visitors/online', [DashboardController::class, 'getOnlineVisitors']);
    Route::get('/api/dashboard/customers',       [DashboardController::class, 'getCustomerCountries']);

    Route::get('/dashboard/customers', [DashboardController::class, 'getCustomerCountries']);
});