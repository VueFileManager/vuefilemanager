<?php

namespace Domain\Teams\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Domain\Folders\Models\Folder;
use Auth;

class LeaveTeamFolderController extends Controller
{
    public function __invoke(Folder $folder): Response|Application|ResponseFactory
    {
        // Find and delete attached member from team folder
        DB::table('team_folder_members')
            ->where('parent_id', $folder->id)
            ->where('user_id', Auth::id())
            ->delete();

        return response('Done.', 204);
    }
}