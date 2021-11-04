<?php
namespace Domain\Teams\Controllers;

use Auth;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class LeaveTeamFolderController extends Controller
{
    public function __invoke(Folder $folder): Response | Application | ResponseFactory
    {
        // Find and delete attached member from team folder
        DB::table('team_folder_members')
            ->where('parent_id', $folder->id)
            ->where('user_id', Auth::id())
            ->delete();

        return response('Done.', 204);
    }
}
