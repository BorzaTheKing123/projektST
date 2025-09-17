<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StrankeController;
use App\Http\Controllers\Api\TveganjeController;
use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\HeatmapController;
use App\Http\Controllers\Api\RiskCategoryController;
// use App\Http\Controllers\Api\ScrapeController;


// 🔐 Uporabnik prek Sanctum
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
    Route::middleware('auth:api')->group(function () {
        Route::get('/stranke', [StrankeController::class, 'index']);
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

        Route::post('/ai/predlogi', [AIController::class, 'predlogi']);

        //Route::apiResource('heatmap', HeatmapController::class);
        
        Route::get('/risks/top', [HeatmapController::class, 'top']);

        // Route::post('/scrape-run', [ScrapeController::class, 'runScraper']); Namenjeno za testiranje LLM-ja. V Heatmap.vue samo odkomentiraj gumb

        Route::get('/categories/{id}', [RiskCategoryController::class, 'show']);







    });
});
