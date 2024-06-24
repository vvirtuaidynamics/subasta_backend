<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;


#[
    OA\Info(version: "1.0.0", description: "Subasta Backend", title: "Subasta-API Documentation"),
    OA\Server(url: "$(config('app.url'))", description: "local server"),
    OA\Server(url: 'http://staging.example.com', description: "staging server"),
    OA\Server(url: 'http://example.com', description: "production server"),
    OA\SecurityScheme( securityScheme: 'bearerAuth', type: "apiKey", name: "Authorization", in: "header", scheme: "bearer"),
]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
