<?php

namespace App\Http\Api\Locale;

use App\Enums\ApiResponseMessages;
use Illuminate\Support\Facades\File;

class LocaleService
{
    protected string $currentLocale;
    protected array $locales;

    public static function getAvailableLocales()
    {
        $locales = File::files(base_path('lang'));
        $availableLocales = [];

        foreach ($locales as $locale) {
            $availableLocales[] = pathinfo($locale, PATHINFO_FILENAME);
        }
        return response()->json(["success" => true, "result" => ["locales" => $availableLocales]]);
    }

    public static function getLocales()
    {
        $locales = File::files(base_path('lang'));
        $locales_data = [];
        foreach ($locales as $locale) {

            $path = str_replace('\\', '/', $locale->getPath() . '/' . $locale->getBasename());
            if (File::exists($path)) {
                $contents = File::get($path);
                $data = json_decode($contents, true);
                $locales_data[] = ["locale" => pathinfo($locale, PATHINFO_FILENAME) === "en" ? "en-US" : pathinfo($locale, PATHINFO_FILENAME), "messages" => $data];
            }
        }
        if (count($locales_data))
            return response()->json($locales_data);

        return response(["success" => false, "message" => ApiResponseMessages::RESOURCE_NOT_FOUND], 404);
    }

    public static function getLocale($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $path = str_replace('\\', '/', base_path("lang/{$locale}.json"));
        if (File::exists($path)) {
            $contents = File::get($path);
            $data = json_decode($contents, true);
            return response()->json(["success" => true, "messages" => $data]);
        }
        return response(["success" => false, "message" => ApiResponseMessages::RESOURCE_NOT_FOUND], 404);
    }


}
