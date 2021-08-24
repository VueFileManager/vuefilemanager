<?php

namespace Domain\Teams\Controllers;

use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Domain\Teams\Models\TeamFoldersInvitation;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class InvitationsController extends Controller
{
    public function update(
        TeamFoldersInvitation $invitation
    ): Response {
        $user = User::where('email', $invitation->email)
            ->firstOrFail();

        $invitation->update([
            'status' => 'accepted',
        ]);

        DB::table('team_folder_members')
            ->insert([
                'folder_id'  => $invitation->folder_id,
                'user_id'  => $user->id,
                'permission' => 'can-edit',
            ]);

        return response('Done', 204);
    }

    public function destroy(
        TeamFoldersInvitation $invitation
    ): Response {

        $invitation->update([
            'status' => 'rejected',
        ]);

        return response('Done', 204);
    }
}