<?php


use Illuminate\Support\Facades\Route;


Route::get('/dev', function (Illuminate\Http\Request $request) {
//    $models = get_modules();
    $forms_data = get_forms_data();
    return count($forms_data) > 0 ? $forms_data : [];
})->name('dev');

/**
 * Rutas públicas.
 */
Route::post('/login', [App\Http\Api\Auth\AuthController::class, 'login'])->name('login');
Route::post('/register/{model?}', [App\Http\Api\Auth\AuthController::class, 'register'])->name('register');

/**
 * Obtener formulario por nombre
 */
Route::get('/form/{name}', [App\Http\Api\Form\FormController::class, 'getFormByName'])->name('get_form_by_name');
/**
 * Check unique value {table: table_name,column: column_name, value: value}
 */
Route::post('/unique', [App\Http\Api\Base\HelperController::class, 'check'])->name('check');
/**
 * Get select options data {table: table, column_id: column_value, column_label: column_label, filter: filter}
 */
Route::post('/select', [App\Http\Api\Base\HelperController::class, 'select'])->name('select');


/**
 *  Locales (Gestión del idioma desde el backend)
 */
Route::get('/lang', [App\Http\Api\Locale\LocaleController::class, 'list'])->name('lang_list');
Route::get('/locales', [App\Http\Api\Locale\LocaleController::class, 'locales'])->name('lang_locales');
Route::get('/lang/{locale}', [App\Http\Api\Locale\LocaleController::class, 'lang'])->name('lang_locale');

/**
 * Rutas protegias
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/token', [App\Http\Api\Auth\AuthController::class, 'validateToken'])->name('token');

    Route::get('/profile', [App\Http\Api\Auth\AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [App\Http\Api\Auth\AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [App\Http\Api\Auth\AuthController::class, 'refresh'])->name('refresh');

    /**
     * User
     */
    Route::get('/user', [App\Http\Api\User\UserController::class, 'list'])->name('user_list');
    Route::post('/user/config', [App\Http\Api\Auth\AuthController::class, 'setConfig'])->name('user_config');
    Route::get('/user/{id}', [App\Http\Api\User\UserController::class, 'view'])->name('user_view');
    Route::post('/user', [App\Http\Api\User\UserController::class, 'store'])->name('user_store');
    Route::patch('/user/{id}', [App\Http\Api\User\UserController::class, 'update'])->name('user_update');
    Route::delete('/user/{id}', [App\Http\Api\User\UserController::class, 'delete'])->name('user_delete');

    /**
     * Client
     */
    Route::get('/client', [App\Http\Api\Client\ClientController::class, 'list'])->name('client_list');
    Route::get('/client/{id}', [App\Http\Api\Client\ClientController::class, 'view'])->name('client_view');
    Route::post('/client', [App\Http\Api\Client\ClientController::class, 'store'])->name('client_store');
    Route::patch('/client/{id}', [App\Http\Api\Client\ClientController::class, 'update'])->name('client_update');
    Route::delete('/client/{id}', [App\Http\Api\Client\ClientController::class, 'delete'])->name('client_delete');

    /**
     * Carrier
     */
    Route::get('/carrier', [App\Http\Api\DocumentCarrier\DocumentCarrierController::class, 'list'])->name('carrier_list');
    Route::get('/carrier/{id}', [App\Http\Api\DocumentCarrier\DocumentCarrierController::class, 'view'])->name('carrier_view');
    Route::post('/carrier', [App\Http\Api\DocumentCarrier\DocumentCarrierController::class, 'store'])->name('carrier_store');
    Route::patch('/carrier/{id}', [App\Http\Api\DocumentCarrier\DocumentCarrierController::class, 'update'])->name('carrier_update');
    Route::delete('/carrier/{id}', [App\Http\Api\DocumentCarrier\DocumentCarrierController::class, 'delete'])->name('carrier_delete');

    /**
     * ValidationTask
     */
    Route::get('/validation-task', [App\Http\Api\ValidationTask\ValidationTaskController::class, 'list'])->name('validation_task_list');
    Route::get('/validation-task/{id}', [App\Http\Api\ValidationTask\ValidationTaskController::class, 'view'])->name('validation_task_view');
    Route::post('/validation-task', [App\Http\Api\ValidationTask\ValidationTaskController::class, 'store'])->name('validation_task_store');
    Route::patch('/validation-task/{id}', [App\Http\Api\ValidationTask\ValidationTaskController::class, 'update'])->name('validation_task_update');
    Route::delete('/validation-task/{id}', [App\Http\Api\ValidationTask\ValidationTaskController::class, 'delete'])->name('validation_task_delete');

    /**
     * Country Routes
     */
    Route::get('/country', [App\Http\Api\Country\CountryController::class, 'list'])->name('country_list');
    Route::get('/country/{id}', [App\Http\Api\Country\CountryController::class, 'view'])->name('country_view');

    /**
     * State Routes
     */
    Route::get('/state', [App\Http\Api\State\StateController::class, 'list'])->name('state_list');
    Route::get('/state/{id}', [App\Http\Api\State\StateController::class, 'view'])->name('state_view');

    /**
     * City Routes
     */
    Route::get('/city', [App\Http\Api\City\CityController::class, 'list'])->name('city_list');
    Route::get('/city/{id}', [App\Http\Api\City\CityController::class, 'view'])->name('city_view');


});

