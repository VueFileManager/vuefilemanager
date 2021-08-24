<?php

use Domain\Teams\Controllers\InvitationsController;
use Domain\Teams\Controllers\TeamFoldersController;


Route::apiResource('/invitations', InvitationsController::class)
    ->only('destroy', 'update');

Route::apiResource('/team-folders', TeamFoldersController::class);
