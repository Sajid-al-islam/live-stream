<?php

use App\Http\Controllers\LiveKitWebhookController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/livekit/webhook', [LiveKitWebhookController::class, 'handle'])->name('livekit.webhook');

Route::prefix('streams')->group(function () {
    Route::post('/start', [StreamController::class, 'startStream']);
    Route::post('/join', [StreamController::class, 'joinStream']);
    Route::get('/rooms', [RoomController::class, 'listRooms']);
});
