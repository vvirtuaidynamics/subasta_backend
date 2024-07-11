<?php

namespace App\Http\Api\Locale;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{

    public function list()
    {
        return LocaleService::getAvailableLocales();
    }

    public function lang($locale)
    {
        return LocaleService::getLocale($locale);
    }

    public function locales()
    {
        return LocaleService::getLocales();
    }

}
