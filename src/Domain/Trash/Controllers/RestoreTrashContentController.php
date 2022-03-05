<?php
namespace Domain\Trash\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestoreTrashContentController extends Controller
{
    public function __invoke(
        Request $request
    ): Response {
        abort_if(is_demo_account(), 204, 'Done.');

        // TODO: zrefaktorovat validator do requestu
        $validator = Validator::make($request->input('items'), [
            '*.type' => 'required|string',
            '*.id'   => 'string',
        ]);

        // Return error
        if ($validator->fails()) {
            abort(400, 'Bad input');
        }

        // Get user id
        $user_id = Auth::id();

        foreach ($request->input('items') as $restore) {
            // Get folder
            if ($restore['type'] === 'folder') {
                // Get folder
                $item = Folder::onlyTrashed()
                    ->where('user_id', $user_id)
                    ->where('id', $restore['id'])
                    ->first();

                // Restore item to home directory
                if ($request->has('to_home') && $request->to_home) {
                    $item->parent_id = null;
                    $item->save();
                }
            } else {
                // Get item
                $item = File::onlyTrashed()
                    ->where('user_id', $user_id)
                    ->where('id', $restore['id'])
                    ->first();

                // Restore item to home directory
                if ($request->has('to_home') && $request->to_home) {
                    $item->parent_id = null;
                    $item->save();
                }
            }

            // Restore Item
            $item->restore();
        }

        // Return response
        return response('Done.', 204);
    }
}
