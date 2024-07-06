<?php

namespace App\Http\Api\Carrier;

use App\Http\Api\Base\BaseService;
use App\Models\Carrier;

/**
 * Class UserRepository.
 */
class CarrierService extends BaseService
{
    public function model(): string
    {
        return Carrier::class;
    }

    public function repository(): string
    {
        return CarrierRepository::class;
    }

    public function rules(): array
    {
        return [
            "name" => "required",
            "surname" => "required",
            "email" => "required|unique:users",
            "password" => "required",
            "transportation_card" => "required|file|max:10240",
            "merchandise_insurance" => "required|file|max:10240",
            "high_social_security" => "required|file|max:10240",
            "payment_current" => "required|file|max:10240",
            "vehicle_insurance" => "required|file|max:10240",
            "itv" => "required|file|max:10240",
            "end_date_transportation_card" => "required",
            "end_date_merchandise_insurance" => "required",
            "end_date_high_social_security" => "required",
            "end_date_payment_current" => "required",
            "end_date_vehicle_insurance" => "required",
            "end_date_itv" => "required",
        ];
    }


}
