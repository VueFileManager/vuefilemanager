<?php

namespace Domain\UploadRequest\Controllers;

use Domain\UploadRequest\Models\UploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class SetUploadRequestAsFilledController
{
    public function __invoke(UploadRequest $uploadRequest): Response|Application|ResponseFactory
    {
        $uploadRequest->update([
            'status' => 'filled',
        ]);

        return response(new UploadRequestResource($uploadRequest), 201);
    }
}