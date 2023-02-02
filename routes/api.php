<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\UserController;
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

Route::get('/ping', function () {
    return ['pong' => true];
});

Route::name('auth.')->prefix('/auth')->group(function () {
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
    Route::post('/signout', [AuthController::class, 'signout'])->name('signout');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::get('/refresh', [AuthController::class, 'refresh'])->name('refresh');
});

Route::name('user.')->prefix('/user')->group(function () {
    Route::get('/', [UserController::class, 'read'])->name('get');
    Route::get('/appointments', [UserController::class, 'getAppointments'])->name('appointments');
    Route::get('/favorites', [UserController::class, 'getFavorites'])->name('favorites');
    Route::post('/favorite', [UserController::class, 'toggleFavorite'])->name('favorite');
    Route::put('/', [UserController::class, 'update'])->name('update');
});

Route::name('barber.')->prefix('/barber')->group(function () {
    Route::get('/list', [BarberController::class, 'list'])->name('list');
    Route::get('/search', [BarberController::class, 'search'])->name('search');
    Route::get('/{id}', [BarberController::class, 'get'])->name('get');
    Route::post('/{id}/appointment', [BarberController::class, 'insertAppointment'])->name('appointment');
});
