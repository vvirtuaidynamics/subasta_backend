<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 * Rutas públicas.
 */
/**
 * Registro por default model=User, puede ser Client o Carrier
 */
Route::post('/register/{model?}', [AuthController::class, 'register'])->name('register');


Route::post('/login', [AuthController::class, 'login'])->name('login');

/**
 * Rutas protegias
 */
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
});

