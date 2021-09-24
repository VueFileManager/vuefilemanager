<?php
namespace Domain\Items\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Items\Actions\MoveFileOrFolderAction;

class MoveFileOrFolderController extends Controller
{
    public function __construct(
        public MoveFileOrFolderAction $moveFileOrFolder,
    ) {
    }

    /**
     * Move item for authenticated master|editor user
     */
    public function __invoke(
        MoveItemRequest $request,
    ): Response {
        if (is_demo_account()) {
            abort(204, 'Done.');
        }

        ($this->moveFileOrFolder)($request);

        return response('Done.', 204);
    }
}
