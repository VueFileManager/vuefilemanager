<?php


namespace Domain\Items\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Domain\Items\Actions\DeleteFileOrFolderAction;
use Domain\Items\Requests\DeleteItemRequest;
use Illuminate\Http\Response;

class DeleteFileOrFolderController extends Controller
{
    /**
     * Delete item for authenticated master|editor user
     */
    public function __invoke(
        DeleteItemRequest $request,
        DeleteFileOrFolderAction $deleteFileOrFolder,
    ): Response{
        abort_if(
            is_demo_account(Auth::user()?->email), 204, 'Done.'
        );

        foreach ($request->input('items') as $item) {
            ($deleteFileOrFolder)($item, $item['id']);
        }

        return response('Done', 204);
    }
}