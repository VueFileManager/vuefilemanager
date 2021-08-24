<?php
namespace Domain\Teams\Controllers;

use Illuminate\Http\Request;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Domain\Teams\DTO\CreateTeamFolderData;
use Illuminate\Support\Facades\Notification;
use Domain\Teams\Models\TeamFoldersInvitation;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class TeamFoldersController extends Controller
{
    public function store(Request $request): Response
    {
        $data = CreateTeamFolderData::fromRequest($request);

        $teamFolder = Folder::create([
            'user_id'     => $request->user()->id,
            'name'        => $data->name,
            'team_folder' => 1,
        ]);

        collect($data->members)
            ->each(function ($email) use ($teamFolder) {

                // Create invitation
                $invitation = TeamFoldersInvitation::create([
                    'folder_id' => $teamFolder->id,
                    'email'     => $email,
                ]);

                // Invite user
                Notification::route('mail', $email)
                    ->notify(new InvitationIntoTeamFolder($teamFolder, $invitation));
            });

        return response($teamFolder, 201);
    }
}
