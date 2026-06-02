<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\CustomerCountryController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/dashboard/customers', [CustomerCountryController::class, 'index']);

Route::prefix('dashboard')->group(function () {
    Route::get('/sold-artworks/{category}', [DashboardController::class, 'getSoldArtworks']);
    Route::get('/stats', [DashboardController::class, 'getStats']);           // ← baru
    Route::get('/visitors', [DashboardController::class, 'getVisitorStats']);
    Route::get('/visitors/online', [DashboardController::class, 'getOnlineVisitors']);
    Route::get('/visitors/years', [DashboardController::class, 'getAvailableYears']);
});

// Gunakan format array secara konsisten
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::middleware([StartSession::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('auth', [AuthController::class, 'index']);
Route::post('auth', [AuthController::class, 'store']);
Route::put('auth/{id}', [AuthController::class, 'update']);
Route::delete('auth/{id}', [AuthController::class, 'destroy']);