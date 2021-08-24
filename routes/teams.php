<?php

use Domain\Teams\Controllers\ConvertFolderIntoTeamFolderController;
use Domain\Teams\Controllers\InvitationsController;
use Domain\Teams\Controllers\TeamFoldersController;



Route::post('/convert/{folder}', ConvertFolderIntoTeamFolderController::class);
Route::apiResource('/invitations', InvitationsController::class);
Route::apiResource('/folders', TeamFoldersController::class);
