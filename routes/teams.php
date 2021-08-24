<?php

use Domain\Teams\Controllers\AcceptTeamFolderInvitationController;
use Domain\Teams\Controllers\TeamFoldersController;

Route::apiResource('/team-folders', TeamFoldersController::class);

Route::post('/invitations/{invitation}', AcceptTeamFolderInvitationController::class);
