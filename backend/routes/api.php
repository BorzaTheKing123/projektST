<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StrankeController;
use App\Http\Controllers\Api\TveganjeController;
use App\Http\Controllers\Api\AiController;

// 🔐 Uporabnik prek Sanctum
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 📡 Broadcast (če uporabljaš real-time funkcije)
Broadcast::routes(['middleware' => ['auth:sanctum']]);

// 🧪 Testna JWT ruta
Route::middleware('auth:api')->get('/me', function () {
    return response()->json(auth()->user());
});

// 🌐 CORS preflight podpora
Route::options('/{any}', function () {
    return response('', 204);
})->where('any', '.*');

// 🔐 JWT zaščitena pridobitev uporabnika
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/moje-stranke', [StrankeController::class, 'mojeStranke']);
// 📦 Glavna API skupina
Route::middleware('api')->group(function () {

    // 🔐 Avtentikacija
    Route::get('/login', [LoginController::class, 'edit'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);

    // 📁 STRANKE (zaščitene z JWT)
    Route::middleware('auth:api')->get('/stranke', [StrankeController::class, 'index']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/stranke/dodaj', [StrankeController::class, 'create']);
        Route::get('/stranke/{id}', [StrankeController::class, 'show']);
        Route::post('/stranke/dodaj', [StrankeController::class, 'store']);
        Route::put('/stranke/{stranka}', [StrankeController::class, 'update']);
        Route::delete('/stranke/{stranka}', [StrankeController::class, 'destroy']);

        // 📁 TVEGANJA (REST + dodatne rute)
        
        Route::get('/stranke/{strankaId}/tveganja', [TveganjeController::class, 'zaStranko']);


        Route::apiResource('tveganja', TveganjeController::class);
        Route::get('/tveganja/{tveganja}', [TveganjeController::class, 'show']);
        Route::post('/tveganja', [TveganjeController::class, 'store']);
        Route::put('/tveganja/{tveganja}', [TveganjeController::class, 'update']);

        Route::post('/ai/predlogi', [AiController::class, 'predlogi']);
    });
});
