<?php

use App\Http\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Api\Country\CountryController;

Route::get('/dev', function (Illuminate\Http\Request $request) {
    $models = get_modules();//get_models();
    //$mothers = get_user_models($request->user());
//    $models = config('modules.modules_data');
    dd($models);
})->name('dev');


Route::get('/user', [\App\Http\Api\User\UserController::class, 'list']);


/**
 * Rutas públicas.
 */
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Registro por default model=User, puede ser Client o Carrier
Route::post('/register/{model?}', [AuthController::class, 'register'])->name('register');

/**
 * Rutas protegias
 */
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    /**
     * User
     */
//    Route::apiResource('/user', \App\Http\Api\User\UserController::class);
//    Route::apiResource('/client', \App\Http\Api\Client\UserController::class);
//    Route::apiResource('/carrier', \App\Http\Api\Carrier\UserController::class);


    /**
     * Country Routes
     */
    Route::get('/country', [CountryController::class, 'index'])->name('country_index');
    Route::get('/country/{id}', [CountryController::class, 'show'])->name('country_show');

    /**
     * State Routes
     */
//    Route::get('/state', [StateController::class, 'index'])->name('state_index');
//    Route::get('/state/{id}', [StateController::class, 'show'])->name('state_show');

    /**
     * City Routes
     */
//    Route::get('/city', [CityController::class, 'index'])->name('city_index');
//    Route::get('/city/{id}', [CityController::class, 'show'])->name('city_show');

});

