<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StrankeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Broadcast::routes(['middleware' => ['auth:sanctum']]);



//za testiranje
Route::middleware('auth:api')->get('/me', function () {
    return response()->json(auth()->user());
});

Route::options('/{any}', function () {
    return response('', 204);
})->where('any', '.*');



Route::middleware('api')->group(function () {
    Route::get('/login', [LoginController::class, 'edit'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);

    Route::middleware('auth:api')->get('/stranke', [StrankeController::class, 'index'])->name('stranke.index');
    Route::middleware('auth:api')->get('/stranke/dodaj', [StrankeController::class, 'create']);
    Route::middleware('auth:api')->get('/stranke/{id}', [StrankeController::class, 'show']);
    Route::middleware('auth:api')->post('/stranke/dodaj', [StrankeController::class, 'store']);
//    Route::get('/stranke/{stranka}/edit', [StrankeController::class, 'edit']);
    Route::middleware('auth:api')->put('/stranke/{stranka}', [StrankeController::class, 'update']);
    Route::middleware('auth:api')->delete('/stranke/{stranka}', [StrankeController::class, 'destroy']);
});