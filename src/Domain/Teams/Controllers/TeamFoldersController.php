<?php
namespace Domain\Teams\Controllers;

use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Teams\Models\TeamFolderMember;
use Domain\Teams\DTO\CreateTeamFolderData;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Teams\Actions\UpdateMembersAction;
use Domain\Folders\Resources\FolderCollection;
use Domain\Teams\Actions\UpdateInvitationsAction;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Teams\Requests\CreateTeamFolderRequest;
use Domain\Teams\Requests\UpdateTeamFolderMembersRequest;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;
use Domain\Teams\Actions\SetTeamFolderPropertyForAllChildrenAction;

class TeamFoldersController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
        public SetTeamFolderPropertyForAllChildrenAction $setTeamFolderPropertyForAllChildren,
    ) {
    }

    public function show($id): array
    {
        $id = Str::isUuid($id) ? $id : null;

        if ($id) {
            $folders = Folder::where('parent_id', $id)
                ->where('team_folder', true)
                ->sortable()
                ->get();

            $files = File::where('parent_id', $id)
                ->sortable()
                ->get();
        }

        if (! $id) {
            $folders = Folder::where('parent_id', null)
                ->where('team_folder', true)
                ->where('user_id', Auth::id())
                ->sortable()
                ->get();
        }

        // Collect folders and files to single array
        return [
            'folders'    => new FolderCollection($folders),
            'files'      => isset($files) ? new FilesCollection($files) : new FilesCollection([]),
            'root'       => $id ? new FolderResource(Folder::findOrFail($id)) : null,
            'teamFolder' => $id ? new FolderResource(Folder::findOrFail($id)->getLatestParent()) : null,
        ];
    }

    public function store(
        CreateTeamFolderRequest $request,
    ): ResponseFactory | Response {
        // Abort in demo mode
        abort_if(is_demo_account(), 201, 'Done.');

        $data = CreateTeamFolderData::fromRequest($request);

        // Check if user can create team folder
        if (! $request->user()->canCreateTeamFolder()) {
            return response([
                'type'    => 'error',
                'message' => 'This user action is not allowed.',
            ], 401);
        }

        // Check if user didn't exceed max team members limit
        if (! $request->user()->canInviteTeamMembers($data->invitations)) {
            return response([
                'type'    => 'error',
                'message' => 'You exceed your members limit.',
            ], 401);
        }

        // Create folder
        $folder = Folder::create([
            'user_id'     => $request->user()->id,
            'name'        => $data->name,
            'team_folder' => 1,
        ]);

        // Attach owner into members
        TeamFolderMember::create([
            'parent_id'  => $folder->id,
            'user_id'    => $request->user()->id,
            'permission' => 'owner',
        ]);

        // Invite team members
        $this->inviteMembers->onQueue()->execute($data->invitations, $folder);

        return response(new FolderResource($folder), 201);
    }

    public function update(
        UpdateTeamFolderMembersRequest $request,
        Folder $folder,
        UpdateInvitationsAction $updateInvitations,
        UpdateMembersAction $updateMembers,
    ): ResponseFactory | Response {
        // Abort in demo mode
        if (is_demo_account()) {
            return response(new FolderResource($folder), 201);
        }

        // Authorize request
        $this->authorize('owner', $folder);

        // Check if user didn't exceed max team members limit
        if (! $request->user()->canInviteTeamMembers($request->input('invitations'))) {
            return response([
                'type'    => 'error',
                'message' => 'You exceed your members limit.',
            ], 401);
        }

        $updateInvitations(
            $folder,
            $request->input('invitations')
        );

        $updateMembers(
            $folder,
            $request->input('members')
        );

        return response(new FolderResource($folder), 201);
    }

    public function destroy(Folder $folder): ResponseFactory | Response
    {
        // Abort in demo mode
        if (is_demo_account()) {
            return response('Done.', 201);
        }

        $this->authorize('owner', $folder);

        // Delete existing invitations
        DB::table('team_folder_invitations')
            ->where('parent_id', $folder->id)
            ->delete();

        // Delete attached members from folder
        DB::table('team_folder_members')
            ->where('parent_id', $folder->id)
            ->delete();

        ($this->setTeamFolderPropertyForAllChildren)($folder, false);

        $folder->update([
            'team_folder' => 0,
        ]);

        return response('Done.', 204);
    }
}
