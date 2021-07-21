<?php


namespace Domain\Items\Actions;


use App\Http\Controllers\Controller;
use Domain\Items\Requests\RenameItemRequest;
use Illuminate\Database\Eloquent\Model;

class RenameFileOrFolderAction
{
    /**
     * Rename item name
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
    ): Model {

        // Get item
        $item = get_item($request->input('type'), $id);

        // Rename item
        $item->update([
            'name' => $request->input('name'),
        ]);

        // Return updated item
        return $item;
    }
}