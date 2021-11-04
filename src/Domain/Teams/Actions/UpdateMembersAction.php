<?php
namespace Domain\Teams\Actions;

use DB;
use Domain\Folders\Models\Folder;

class UpdateMembersAction
{
    public function __invoke(Folder $folder, $members): void
    {
        $existingMembers = $folder
            ->teamMembers()
            ->pluck('user_id');

        // Get deleted members from request
        $deletedMembers = $existingMembers->diff(
            collect($members)->pluck('id')->toArray()
        );

        // Remove team members from team folder
        if ($deletedMembers->isNotEmpty()) {
            DB::table('team_folder_members')
                ->where('parent_id', $folder->id)
                ->whereIn('user_id', $deletedMembers->toArray())
                ->delete();
        }

        // Update privileges
        collect($members)
            ->each(
                fn ($member) =>
                DB::table('team_folder_members')
                    ->where('parent_id', $folder->id)
                    ->where('user_id', $member['id'])
                    ->update([
                        'permission' => $member['permission'],
                    ])
            );
    }
}
