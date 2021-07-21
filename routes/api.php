<?php

use App\Users\Actions\CreateNewUserAction;
use Domain\Pages\Controllers\PagesController;
use Domain\Sharing\Controllers\ShareController;
use Domain\Items\Controllers\EditItemsController;
use Domain\Trash\Controllers\DumpTrashController;
use App\Users\Controllers\ResetPasswordController;
use Domain\Zipping\Controllers\ZipFilesController;
use App\Users\Controllers\ForgotPasswordController;
use Domain\Files\Controllers\UploadFileController;
use Domain\Folders\Controllers\FavouriteController;
use Domain\Plans\Controllers\ActivePlansController;
use Domain\Zipping\Controllers\ZipFolderController;
use Domain\Folders\Controllers\CreateFolderController;
use Domain\Sharing\Controllers\ShareViaEmailController;
use Domain\Settings\Controllers\GetSettingsValueController;
use Domain\Trash\Controllers\RestoreTrashContentController;
use Domain\Browsing\Controllers\BrowseLatestFilesController;
use Domain\Browsing\Controllers\BrowseSharedItemsController;
use Domain\Browsing\Controllers\BrowseTrashContentController;
use Domain\Homepage\Controllers\SendContactMessageController;
use Domain\Browsing\Controllers\BrowseFolderContentController;
use Domain\Folders\Controllers\NavigationFolderTreeController;
use Domain\Browsing\Controllers\SearchFilesAndFoldersController;
use Domain\Browsing\Controllers\BrowseParticipantsUploadsController;

// Pages
Route::apiResource('/page', PagesController::class);

// Homepage
Route::post('/contact', SendContactMessageController::class);
Route::get('/pricing', ActivePlansController::class);
Route::get('/settings', GetSettingsValueController::class);

// Register user
Route::post('/register', CreateNewUserAction::class);

// Password reset
Route::group(['prefix' => 'password'], function () {
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset', [ResetPasswordController::class, 'reset']);
});

// User master Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Browse
    Route::group(['prefix' => 'browse'], function () {
        Route::get('/participants', BrowseParticipantsUploadsController::class);
        Route::get('/folders/{id}', BrowseFolderContentController::class);
        Route::get('/navigation', NavigationFolderTreeController::class);
        Route::get('/search', SearchFilesAndFoldersController::class);
        Route::get('/latest', BrowseLatestFilesController::class);
        Route::get('/trash', BrowseTrashContentController::class);
        Route::get('/share', BrowseSharedItemsController::class);
    });

    // Trash
    Route::group(['prefix' => 'trash'], function () {
        Route::post('/restore', RestoreTrashContentController::class);
        Route::delete('/dump', DumpTrashController::class);
    });

    // Share
    Route::post('/share/{token}/email', ShareViaEmailController::class);
    Route::apiResource('/share', ShareController::class);

    // Favourites
    Route::apiResource('/folders/favourites', FavouriteController::class);
});

// User master,editor routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/create-folder', CreateFolderController::class);
    Route::post('/upload', UploadFileController::class);

    Route::patch('/rename/{id}', [EditItemsController::class, 'rename_item']);
    Route::post('/remove', [EditItemsController::class, 'delete_item']);
    Route::post('/move', [EditItemsController::class, 'move']);

    Route::get('/zip/folder/{id}', ZipFolderController::class);
    Route::post('/zip/files', ZipFilesController::class);
});
