<?php
namespace Domain\Teams\Controllers;

use Gate;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class LeaveTeamFolderController extends Controller
{
    public function __invoke(Folder $folder): Response|Application|ResponseFactory
    {
        // Abort in demo mode
        if (is_demo_account()) {
            return response('Done.', 204);
        }

        // Authorize action
        if (! Gate::any(['can-edit', 'can-view'], [$folder, null])) {
            abort(403, 'Access Denied');
        }

        // Find and delete attached member from team folder
        DB::table('team_folder_members')
            ->where('parent_id', $folder->id)
            ->where('user_id', auth()->id())
            ->delete();

        return response('Done.', 204);
    }
}
