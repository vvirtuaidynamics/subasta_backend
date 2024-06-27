<?php

use App\Http\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Api\Country\CountryController;
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
if(config('app.debug'))
    Route::get('/dev', [AuthController::class, 'dev'])->name('dev');

Route::post('/login', [AuthController::class, 'login'])->name('login');
// Registro por default model=User, puede ser Client o Carrier
Route::post('/register/{model?}', [AuthController::class, 'register'])->name('register');

/**
 * Probando implementación.
 */


/**
 * Rutas protegias
 */
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    /**
     * User
     */
    Route::apiResource('/user', \App\Http\Api\User\UserController::class);
//    Route::apiResource('/client', \App\Http\Api\Client\UserController::class);
//    Route::apiResource('/carrier', \App\Http\Api\Carrier\UserController::class);



    /**
     * Country Routes
     */
    Route::get('/country', [CountryController::class, 'index'])->name('country_index');
    Route::get('/country/{id}', [CountryController::class, 'show'])->name('country_show');





});

