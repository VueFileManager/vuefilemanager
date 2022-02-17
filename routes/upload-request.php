<?php

use Domain\UploadRequest\Controllers\CreateUploadRequestController;
use Domain\UploadRequest\Controllers\GetUploadRequestController;

Route::get('/{uploadRequest}', GetUploadRequestController::class);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/', CreateUploadRequestController::class);
});
