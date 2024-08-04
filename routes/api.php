<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PhotoController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function(){
    Route::post('/users/index', [UserController::class, 'index']);
    Route::apiResource('/users', UserController::class)->except(['index']);

    Route::post('/events/index', [EventController::class, 'index']);
    Route::apiResource('/events', EventController::class)->except(['index']);

    Route::post('/event-photos/index', [PhotoController::class, 'index']);
    Route::get('/event-photos', [PhotoController::class, 'getEventPhotos']); // Use this route to get all event photos by event id and created by user id
    Route::apiResource('/event-photos', PhotoController::class)->except(['index']);

    Route::post('/logout',[UserController::class, 'logout']);
});

    