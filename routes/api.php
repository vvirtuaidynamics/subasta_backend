<?php

use App\Http\Api\Locale\LocaleController;
use App\Http\Api\Auth\AuthController;
use App\Http\Api\DocumentCarrier\DocumentCarrierController;
use App\Http\Api\City\CityController;
use App\Http\Api\Carrier\CarrierController;

use App\Http\Api\Client\ClientController;
use App\Http\Api\Country\CountryController;
use App\Http\Api\State\StateController;
use App\Http\Api\User\UserController;
use App\Http\Api\ValidationTask\ValidationTaskController;
use Illuminate\Support\Facades\Route;


Route::get('/dev', function (Illuminate\Http\Request $request) {
    $models = get_modules();
    $userService = new \App\Http\Api\User\UserService();
    $user = $userService->findByColumn(1);
    $locale = new \App\Http\Api\Locale\LocaleService();
    return get_user_modules($user);

})->name('dev');

/**
 * Rutas públicas.
 */

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register/{model?}', [AuthController::class, 'register'])->name('register');

/**
 *  Locales (Gestión del idioma desde el backend)
 */
Route::get('/lang', [LocaleController::class, 'list'])->name('lang_list');
Route::get('/locales', [LocaleController::class, 'locales'])->name('lang_locales');
Route::get('/lang/{locale}', [LocaleController::class, 'lang'])->name('lang_locale');

/**
 * Rutas protegias
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/token', [AuthController::class, 'validateToken'])->name('token');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    /**
     * User
     */
    Route::get('/user', [UserController::class, 'list'])->name('user_list');
    Route::post('/user/config', [AuthController::class, 'setConfig'])->name('user_config');
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
    Route::get('/carrier', [DocumentCarrierController::class, 'list'])->name('carrier_list');
    Route::get('/carrier/{id}', [DocumentCarrierController::class, 'view'])->name('carrier_view');
    Route::post('/carrier', [DocumentCarrierController::class, 'store'])->name('carrier_store');
    Route::patch('/carrier/{id}', [DocumentCarrierController::class, 'update'])->name('carrier_update');
    Route::delete('/carrier/{id}', [DocumentCarrierController::class, 'delete'])->name('carrier_delete');

    /**
     * ValidationTask
     */
    Route::get('/validation-task', [ValidationTaskController::class, 'list'])->name('validation_task_list');
    Route::get('/validation-task/{id}', [ValidationTaskController::class, 'view'])->name('validation_task_view');
    Route::post('/validation-task', [ValidationTaskController::class, 'store'])->name('validation_task_store');
    Route::patch('/validation-task/{id}', [ValidationTaskController::class, 'update'])->name('validation_task_update');
    Route::delete('/validation-task/{id}', [ValidationTaskController::class, 'delete'])->name('validation_task_delete');

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
    Route::get('/city', [CityController::class, 'list'])->name('city_list');
    Route::get('/city/{id}', [CityController::class, 'view'])->name('city_view');

});

