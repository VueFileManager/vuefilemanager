<?php


namespace Domain\Items\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Domain\Files\Models\File as UserFile;
use Domain\Folders\Models\Folder;
use Domain\Items\Actions\MoveFileOrFolderAction;
use Domain\Items\Requests\MoveItemRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class MoveFileOrFolderController extends Controller
{
    /**
     * Move item for authenticated master|editor user
     */
    public function __invoke(
        MoveItemRequest $request,
        MoveFileOrFolderAction $moveFileOrFolder,
    ): Response {

        abort_if(
            is_demo_account(Auth::user()?->email), 204, 'Done.'
        );

        ($moveFileOrFolder)(
            $request, $request->input('to_id')
        );

        return response('Done!', 204);
    }
}