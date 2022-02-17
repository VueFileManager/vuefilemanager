<?php
namespace Domain\UploadRequest\Controllers;

use Domain\UploadRequest\Models\UploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;

class GetUploadRequestController
{
    public function __invoke(UploadRequest $uploadRequest)
    {
        return new UploadRequestResource($uploadRequest);
    }
}
