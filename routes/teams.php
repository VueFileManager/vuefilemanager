<?php

use Domain\Teams\Controllers\InvitationsController;
use Domain\Teams\Controllers\TeamFoldersController;
use Domain\Teams\Controllers\NavigationTreeController;
use Domain\Teams\Controllers\LeaveTeamFolderController;
use Domain\Teams\Controllers\BrowseSharedWithMeController;
use Domain\Teams\Controllers\ConvertFolderIntoTeamFolderController;

Route::apiResource('/invitations', InvitationsController::class);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/shared-with-me/{id}', BrowseSharedWithMeController::class);
    Route::apiResource('/folders', TeamFoldersController::class);

    Route::group(['prefix' => '/folders'], function() {
        Route::post('/{folder}/convert', ConvertFolderIntoTeamFolderController::class);
        Route::delete('/{folder}/leave', LeaveTeamFolderController::class);
        Route::get('/{folder}/tree', NavigationTreeController::class);
    });
});
