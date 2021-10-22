<?php

use Domain\Teams\Controllers\InvitationsController;
use Domain\Teams\Controllers\NavigationTreeController;
use Domain\Teams\Controllers\TeamFoldersController;
use Domain\Teams\Controllers\BrowseSharedWithMeController;
use Domain\Teams\Controllers\ConvertFolderIntoTeamFolderController;

Route::apiResource('/invitations', InvitationsController::class);
Route::apiResource('/folders', TeamFoldersController::class);

Route::post('/convert/{folder}', ConvertFolderIntoTeamFolderController::class);
Route::get('/shared-with-me/{id}', BrowseSharedWithMeController::class);

Route::get('/tree/{id}', NavigationTreeController::class);
