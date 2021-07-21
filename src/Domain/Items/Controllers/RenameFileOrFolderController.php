<?php


namespace Domain\Items\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Domain\Folders\Actions\UpdateFolderPropertyAction;
use Domain\Items\Actions\RenameFileOrFolderAction;
use Domain\Items\Requests\RenameItemRequest;
use Illuminate\Database\Eloquent\Model;

class RenameFileOrFolderController extends Controller
{
    /**
     * Rename item for authenticated master|editor user
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
        RenameFileOrFolderAction $renameFileOrFolder,
        UpdateFolderPropertyAction $updateFolderProperty
    ): Model {

        if (is_demo_account(Auth::user()?->email)) {
            return $this->demo->rename_item($request, $id);
        }

        // If request contain icon or color, then change it
        if ($request->filled('emoji') || $request->filled('color')) {
           ($updateFolderProperty)($request, $id);
        }

        // Rename Item
        return ($renameFileOrFolder)($request, $id);
    }
}