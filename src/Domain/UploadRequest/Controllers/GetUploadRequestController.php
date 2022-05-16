<?php
namespace Domain\UploadRequest\Controllers;

use Illuminate\Http\JsonResponse;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;

class GetUploadRequestController
{
    public function __invoke(
        UploadRequest $uploadRequest
    ): JsonResponse {
        return response()->json(new UploadRequestResource($uploadRequest));
    }
}
