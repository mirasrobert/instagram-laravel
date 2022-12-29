<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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
Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/me', MeController::class);
    Route::post('/change_password', ChangePasswordController::class);

    Route::apiResource('profiles', ProfileController::class);

    Route::resource('profiles', ProfileController::class)->except([
        'create', 'store'
    ]);

    Route::resource('posts', PostController::class)->only([
        'index', 'show', 'store', 'destroy'
    ]);

    Route::post('/follow/{user}', [FollowsController::class, 'store']);

    Route::get('/following', [FollowsController::class, 'following']);

});
