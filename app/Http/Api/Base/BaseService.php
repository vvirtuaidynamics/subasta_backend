<?php

namespace App\Http\Api\Base;

use App\Enums\ApiStatus;
use App\Enums\ApiResponseMessages;
use App\Enums\ApiResponseCodes;
use App\Http\Api\Base\BaseServiceInterface;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;

abstract class BaseService
{
    use ApiResponseFormatTrait;


}
