<?php

namespace App\Http\Api\Locale;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{

    public function list(): JsonResponse
    {
        return LocaleService::getAvailableLocales();
    }

    public function lang($locale): JsonResponse
    {
        return LocaleService::getLocales($locale);
    }

    public function locales(): JsonResponse
    {
        return LocaleService::getLocales();
    }

}
