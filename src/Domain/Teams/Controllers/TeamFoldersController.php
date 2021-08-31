<?php
namespace Domain\Teams\Controllers;

use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

class TeamFoldersController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
    ) {
    }

    public function show($id): array
    {
        $files = [];
        $teamRootFolder = null;

        $rootId = Str::isUuid($id) ? $id : null;
        $requestedFolder = $rootId ? Folder::findOrFail($rootId) : null;

        $folders = Folder::where('parent_id', $rootId)
            ->where('team_folder', ! Str::isUuid($id))
            ->where('user_id', Auth::id())
            ->sortable()
            ->get();

        if ($requestedFolder) {
            // Get root team folder
            $teamRootIdResults = recursiveFind(
                $requestedFolder->teamRoot->toArray(),
                'id'
            );

            $teamRootId = end($teamRootIdResults);

            $teamRootFolder = $teamRootId
                ? Folder::findOrFail($teamRootId)
                : $requestedFolder;

            // Get files
            $files = File::where('folder_id', $rootId)
                ->where('user_id', Auth::id())
                ->sortable()
                ->get();
        }

        // Collect folders and files to single array
        return [
            'folders'    => new FolderCollection($folders),
            'files'      => new FilesCollection($files),
            'root'       => $requestedFolder ? new FolderResource($requestedFolder) : null,
            'teamFolder' => $teamRootFolder ? new FolderResource($teamRootFolder) : null,
        ];
    }

    public function store(
        CreateTeamFolderRequest $request,
    ): ResponseFactory | Response {
        $data = CreateTeamFolderData::fromRequest($request);

        $folder = Folder::create([
            'user_id'     => $request->user()->id,
            'name'        => $data->name,
            'team_folder' => 1,
        ]);

        // Invite team members
        ($this->inviteMembers)($data->invitations, $folder);

        return response(new FolderResource($folder), 201);
    }

    public function update(
        UpdateTeamFolderMembersRequest $request,
        Folder $folder,
        UpdateInvitationsAction $updateInvitations,
        UpdateMembersAction $updateMembers,
    ): ResponseFactory | Response {
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
        // Delete existing invitations
        DB::table('team_folder_invitations')
            ->where('folder_id', $folder->id)
            ->delete();

        // Delete attached members from folder
        DB::table('team_folder_members')
            ->where('folder_id', $folder->id)
            ->delete();

        $folder->update([
            'team_folder' => 0,
        ]);

        return response('Done.', 204);
    }
}
