<?php

namespace App\Http\Api\File;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseService;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileService extends BaseService
{
    public function model(): string
    {
        return File::class;
    }

    public function repository(): string
    {
        return FileRepository::class;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'type' => 'required|string',
            'path' => 'required|string',
            'size' => 'required|numeric',
        ];
    }


    private function generateFileName(UploadedFile $file)
    {
        return uniqid() . '_' . $file->getClientOriginalName();
    }

    public function processFiles($files, $storage_path = '')
    {
        $processedFiles = [];
        foreach ($files as $file) {
            $fileName = $this->generateFileName($file);
            $path = Storage::putFileAs($storage_path, $file, $fileName);
            if ($path) {
                $processedFiles[] = [
                    'name' => $fileName,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ];
            }
        }
        return $processedFiles;
    }

    public function create(Request $request, $getModel = false)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':create';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        try {
            if (!$request->files->count()) $this->sendError(ApiResponseMessages::UNPROCESSABLE_CONTENT, ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT);
            $files = $this->processFiles($request->files->all());
            $data = [];
            foreach ($files as $file) {
                $validator = $this->makeValidator($request->all());
                $validatedData = $validator->validate();
                $data[] = $this->repository->create($validatedData);
            }
            if ($getModel) return $data;
            return $this->sendResponse($data, ApiResponseMessages::CREATED_SUCCESSFULLY);
        } catch (ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                ['errors' => $e->errors()]
            );
        }

    }

}
