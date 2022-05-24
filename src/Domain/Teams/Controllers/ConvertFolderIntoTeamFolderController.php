<?php
namespace Domain\Teams\Controllers;

use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Domain\Teams\Models\TeamFolderMember;
use Domain\Folders\Resources\FolderResource;
use Illuminate\Auth\Access\AuthorizationException;
use Domain\Teams\Requests\ConvertIntoTeamFolderRequest;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;
use Domain\Teams\Actions\SetTeamFolderPropertyForAllChildrenAction;

class ConvertFolderIntoTeamFolderController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
        public SetTeamFolderPropertyForAllChildrenAction $setTeamFolderPropertyForAllChildren,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function __invoke(
        ConvertIntoTeamFolderRequest $request,
        Folder $folder
    ): JsonResponse {
        // Authorize action
        Gate::authorize('owner', [$folder]);

        // Abort in demo mode
        if (isDemoAccount()) {
            return response()->json(new FolderResource($folder), 201);
        }

        // Check if user didn't exceed max team members limit
        if (! $folder->user->canInviteTeamMembers($request->input('invitations'))) {
            return response()->json([
                'type'    => 'error',
                'message' => 'You exceed your members limit.',
            ], 401);
        }

        // Update root team folder
        $folder->update([
            'team_folder' => true,
            'parent_id'   => null,
        ]);

        // Mark all children folders as team folder
        ($this->setTeamFolderPropertyForAllChildren)($folder, true);

        // Attach owner into members
        TeamFolderMember::create([
            'parent_id'  => $folder->id,
            'user_id'    => $folder->user_id,
            'permission' => 'owner',
        ]);

        // Invite team members
        ($this->inviteMembers)($request->input('invitations'), $folder);

        return response()->json(new FolderResource($folder), 201);
    }
}
