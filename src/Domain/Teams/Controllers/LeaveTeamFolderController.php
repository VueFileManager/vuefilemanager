<?php
namespace Domain\Teams\Controllers;

use Gate;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeaveTeamFolderController extends Controller
{
    public function __invoke(
        Folder $folder
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'You left the team folder.',
        ];

        // Abort in demo mode
        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        // Authorize action
        if (! Gate::any(['can-edit', 'can-view'], [$folder, null])) {
            return response()->json([
                'type'    => 'error',
                'message' => 'You are not member of this team folder.',
            ], 403);
        }

        // Find and delete attached member from team folder
        DB::table('team_folder_members')
            ->where('parent_id', $folder->id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json($successMessage);
    }
}
