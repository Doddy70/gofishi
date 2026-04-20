<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\HostController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Guest Routes
Route::get('/boats', [GuestController::class, 'index']);
Route::get('/boats/{id}', [GuestController::class, 'show']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Booking Routes
    Route::get('/bookings/history', [GuestController::class, 'history']);
    Route::post('/bookings', [GuestController::class, 'store']);
    Route::post('/bookings/{id}/cancel', [GuestController::class, 'cancel']);

    // Review Routes
    Route::post('/reviews', [GuestController::class, 'submitReview']);
});

// Host Specific Protected Routes
Route::prefix('host')->middleware('auth:sanctum')->group(function () {
    Route::get('/boats', [HostController::class, 'index']);
    Route::post('/boats', [HostController::class, 'store']);
    Route::get('/bookings', [HostController::class, 'bookings']);
    Route::post('/bookings/{id}/accept', [HostController::class, 'acceptBooking']);
    Route::post('/bookings/{id}/reject', [HostController::class, 'rejectBooking']);
});

// Admin Routes
Route::post('/login/admin', [AuthController::class, 'loginAdmin']);

Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    // Shared Admin (Tier 3+)
    Route::middleware(['admin.tier:3'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
    });

    // Moderator (Tier 2+)
    Route::middleware(['admin.tier:2'])->group(function () {
        Route::get('/boats', [AdminController::class, 'manageBoats']);
        Route::post('/bookings/{id}/moderate', [AdminController::class, 'moderateBooking']);
    });

    // Super Admin (Tier 1)
    Route::middleware(['admin.tier:1'])->group(function () {
        Route::post('/vendors/{id}/status', [AdminController::class, 'manageVendor']);
    });
});

