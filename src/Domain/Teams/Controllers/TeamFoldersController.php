<?php
namespace Domain\Teams\Controllers;

use DB;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Teams\Requests\CreateTeamFolderRequest;
use Domain\Teams\Requests\UpdateTeamFolderMembersRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Files\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Teams\DTO\CreateTeamFolderData;
use Domain\Teams\Actions\UpdateMembersAction;
use Domain\Teams\Actions\UpdateInvitationsAction;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;
use Str;

class TeamFoldersController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
    ) {
    }

    public function show($id)
    {
        $isHomepage = $id === 'undefined';
        $rootId = $id === 'undefined' ? null : $id;
        $requestedFolder = $rootId ? new FolderResource(Folder::findOrFail($rootId)) : null;
        $files = [];

        $folders = Folder::with([
            'teamMembers',
            'teamInvitations',
            'parent:id,name',
            'shared:token,id,item_id,permission,is_protected,expire_in'
        ])
            ->where('parent_id', $rootId)
            ->where('team_folder', $isHomepage)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get();

        if (Str::isUuid($id)) {
            $files = File::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
                ->where('folder_id', $rootId)
                ->where('user_id', Auth::id())
                ->sortable()
                ->get();
        }

        // Collect folders and files to single array
        return [
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
            'root'    => $requestedFolder,
        ];
    }

    public function store(
        CreateTeamFolderRequest $request,
    ): ResponseFactory|Response {
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
    ): ResponseFactory|Response {
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

    public function destroy(Folder $folder): ResponseFactory|Response
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
