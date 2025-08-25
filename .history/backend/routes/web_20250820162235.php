<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserControllers\LoginController;
use App\Http\Controllers\UserControllers\RegisterController;
use App\Http\Controllers\StrankeController;

Route::get('/', [SessionController::class, 'show']);

Route::get('/login', [LoginController::class, 'edit'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/{id}/stranke', [StrankeController::class, 'index'])->name('stranke.index');
    Route::get('/{id}/stranke/dodaj', [StrankeController::class, 'create']);
    Route::post('/{id}/stranke/dodaj', [StrankeController::class, 'store']);
    Route::get('/{id}/stranke/{stranka}', [StrankeController::class, 'edit']);
    Route::put('/{id}/stranke/{stranka}', [StrankeController::class, 'update']);
    Route::delete('/{id}/stranke/{stranka}', [StrankeController::class, 'destroy']);
});