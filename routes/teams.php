<?php

use Domain\Teams\Controllers\BrowseSharedWithMeController;
use Domain\Teams\Controllers\ConvertFolderIntoTeamFolderController;
use Domain\Teams\Controllers\InvitationsController;
use Domain\Teams\Controllers\TeamFoldersController;

Route::apiResource('/invitations', InvitationsController::class);
Route::apiResource('/folders', TeamFoldersController::class);

Route::post('/convert/{folder}', ConvertFolderIntoTeamFolderController::class);
Route::get('/shared-with-me/{id}', BrowseSharedWithMeController::class);
