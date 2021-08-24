<?php

use Domain\Teams\Controllers\ConvertFolderIntoTeamFolderController;
use Domain\Teams\Controllers\InvitationsController;
use Domain\Teams\Controllers\TeamFoldersController;


Route::apiResource('/invitations', InvitationsController::class)
    ->only('destroy', 'update');

Route::post('/folders/convert/{folder}', ConvertFolderIntoTeamFolderController::class);
Route::apiResource('/folders', TeamFoldersController::class);
