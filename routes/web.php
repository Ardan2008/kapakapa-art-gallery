<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// --- PUBLIC ROUTES (Akses Tanpa Login) ---
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/navbar', function () { return view('component.layout.navbar'); })->name('navbar');
Route::get('/footer', function () { return view('component.layout.footer'); })->name('footer');

// Component Gallery & Artists
Route::get('/gallery', [App\Http\Controllers\ArtWorkController::class, 'gallery'])->name('gallery');
Route::get('/review_gallery', function () { return view('component.gallery.review_gallery'); })->name('review_gallery');
Route::get('/artists', [App\Http\Controllers\ArtWorkController::class, 'allArtists'])->name('artists');
Route::get('/profile_art/{id?}', [App\Http\Controllers\ArtWorkController::class, 'profile'])->name('profile_art');

// Login Page
// Mengambil kode unik dari .env, jika tidak ada defaultnya adalah 'login'
$adminPath = env('ADMIN_URL', 'login');

Route::get('/' . $adminPath, function () { 
    return view('admin.form_login'); 
})->name('login'); // Beri nama 'login' agar redirect otomatis bekerja
Route::post('/api/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Hanya Bisa Diakses Setelah Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Sidebar
    Route::get('/master', function () { return view('admin.dashboard.master'); })->name('master');
    Route::get('/sidebar', function () { return view('admin.dashboard.layouts.sidebar'); })->name('sidebar');

    // Main Features
    Route::get('/transactions', [App\Http\Controllers\DashboardController::class, 'index'])->name('transactions');
    Route::get('/api/sold-artworks/{category}', [App\Http\Controllers\DashboardController::class, 'getSoldArtworks']);
    Route::get('/collectors', function () { return view('admin.dashboard.features.collectors'); })->name('collectors');
    Route::get('/submit_artworks', [App\Http\Controllers\ArtWorkController::class, 'index'])->name('submit_artworks');

    // Tools & Editing
    Route::get('/trending', function () { return view('admin.dashboard.tools.trending'); })->name('trending');
    Route::get('/add_product', [App\Http\Controllers\ArtWorkController::class, 'create'])->name('add_product');
    Route::get('/edit_product/{id}', [App\Http\Controllers\ArtWorkController::class, 'edit'])->name('edit_product');

    // API Actions
    Route::post('/api/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/api/logout', [AuthController::class, 'logout']);
    Route::post('/api/artworks/store', [App\Http\Controllers\ArtWorkController::class, 'store']);
    Route::post('/api/artworks/store-multiple', [App\Http\Controllers\ArtWorkController::class, 'storeMultiple']);
    Route::post('/api/artworks/store-collection', [App\Http\Controllers\ArtWorkController::class, 'storeCollection']);
    Route::get('/api/artworks/collection/{id}', [App\Http\Controllers\ArtWorkController::class, 'getCollection']);
    Route::delete('/api/artworks/collection/{id}', [App\Http\Controllers\ArtWorkController::class, 'destroyCollection']);
});