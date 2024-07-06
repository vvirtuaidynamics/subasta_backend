<?php

use App\Http\Api\Auth\AuthController;
use App\Http\Api\User\UserController;
use App\Http\Api\Client\ClientController;
use App\Http\Api\Carrier\CarrierController;
use Illuminate\Support\Facades\Route;
use App\Http\Api\Country\CountryController;
use App\Http\Api\State\StateController;
use App\Http\Api\City\CityController;


Route::get('/dev', function (Illuminate\Http\Request $request) {
    $models = get_modules();//get_models();
    //$mothers = get_user_models($request->user());
//    $models = config('modules.modules_data');
    dd($models);
})->name('dev');

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
    Route::get('/user', [UserController::class, 'list'])->name('user_list');
    Route::get('/user/{id}', [UserController::class, 'view'])->name('user_view');
    Route::post('/user', [UserController::class, 'store'])->name('user_store');
    Route::patch('/user/{id}', [UserController::class, 'update'])->name('user_update');
    Route::delete('/user/{id}', [UserController::class, 'delete'])->name('user_delete');

    /**
     * Client
     */
    Route::get('/client', [ClientController::class, 'list'])->name('client_list');
    Route::get('/client/{id}', [ClientController::class, 'view'])->name('client_view');
    Route::post('/client', [ClientController::class, 'store'])->name('client_store');
    Route::patch('/client/{id}', [ClientController::class, 'update'])->name('client_update');
    Route::delete('/client/{id}', [ClientController::class, 'delete'])->name('client_delete');

    /**
     * Carrier
     */
    Route::get('/carrier', [CarrierController::class, 'list'])->name('carrier_list');
    Route::get('/carrier/{id}', [CarrierController::class, 'view'])->name('carrier_view');
    Route::post('/carrier', [CarrierController::class, 'store'])->name('carrier_store');
    Route::patch('/carrier/{id}', [CarrierController::class, 'update'])->name('carrier_update');
    Route::delete('/carrier/{id}', [CarrierController::class, 'delete'])->name('carrier_delete');

    /**
     * Country Routes
     */
    Route::get('/country', [CountryController::class, 'list'])->name('country_list');
    Route::get('/country/{id}', [CountryController::class, 'view'])->name('country_view');

    /**
     * State Routes
     */
    Route::get('/state', [StateController::class, 'list'])->name('state_list');
    Route::get('/state/{id}', [StateController::class, 'view'])->name('state_view');

    /**
     * City Routes
     */
    Route::get('/city', [CityController::class, 'list'])->name('city_index');
    Route::get('/city/{id}', [CityController::class, 'view'])->name('city_show');

});

