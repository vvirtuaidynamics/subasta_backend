<?php

namespace App\Http\Api\DocumentCarrier;

use App\Http\Api\Base\BaseService;
use App\Models\DocumentCarrier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentCarrierService extends BaseService
{
    public function model(): string
    {
        return DocumentCarrier::class;
    }

    public function repository(): string
    {
        return DocumentCarrierRepository::class;
    }

    public function rules(): array
    {
        return [
            'carrier_id' => 'required|numeric',
            'path' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string',
            'size' => 'required|numeric',
            'document' => 'required|in:transportation_card,merchandise_insurance,high_social_security,payment_current,vehicle_insurance,itv',
            'expire_date' => 'required|date',
            'validated' => 'nullable|boolean'
        ];
    }

    public function processDocumentsCarrier($model, Request $request)
    {
        $requestData = $request->allFiles();
        $files = [];
        $processedData = [];
        foreach ($requestData as $key => $value) {
            if ($value instanceof UploadedFile && $value->isFile()) {
                $files[$key] = ['value' => $value, 'document' => $key];
            }
        }
        $idModel = $model->id;
        $module_path = 'carrier/' . $idModel . '/documents';
        foreach ($files as $key => $file) {
            $extension = $file['value']->getClientOriginalExtension();
            $end_date = 'end_date_' . $file['document'];
            $name_to_save = $file['document'] . '_exp' . Carbon::parse($request->input($end_date))->toDateString() . '_' . Str::random(5) . '.' . $extension;
            $model_path = $file['value']->storeAs($module_path, $name_to_save);
            $document =
                [
                    'carrier_id' => $idModel,
                    'name' => $name_to_save,
                    'document' => $file['document'],
                    'type' => $extension,
                    'path' => $model_path,
                    'size' => $file['value']->getSize(),
                    'expire_date' => $request->input($end_date) . ' 00:00:00'
                ];
            $processedData[] = $document;
            $documentCarrierRepository = new DocumentCarrierRepository();
            $documentCarrierRepository->create($document);
        }
        return $processedData;

    }


}
