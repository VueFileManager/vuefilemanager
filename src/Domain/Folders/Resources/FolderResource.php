<?php
namespace Domain\Folders\Resources;

use Domain\Sharing\Resources\ShareResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Domain\Teams\Resources\TeamMembersCollection;
use Domain\Teams\Resources\TeamInvitationsCollection;

class FolderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => [
                'id'            => $this->id,
                'type'          => 'folder',
                'attributes'    => [
                    'name'          => $this->name,
                    'color'         => $this->color,
                    'emoji'         => $this->emoji,
                    'filesize'      => $this->filesize,
                    'isTeamFolder'  => $this->team_folder,
                    'items'         => $this->items,
                    'trashed_items' => $this->trashed_items,
                    'created_at'    => set_time_by_user_timezone($this->user, $this->created_at),
                    'updated_at'    => set_time_by_user_timezone($this->user, $this->updated_at),
                    'deleted_at'    => $this->deleted_at
                        ? set_time_by_user_timezone($this->user, $this->deleted_at)
                        : null,
                ],
                'relationships' => [
                    $this->mergeWhen($this->teamMembers, fn () => [
                        'members' => new TeamMembersCollection($this->teamMembers),
                    ]),
                    $this->mergeWhen($this->teamInvitations, fn () => [
                        'invitations' => new TeamInvitationsCollection($this->teamInvitations),
                    ]),
                    $this->mergeWhen($this->shared, fn () => [
                        'shared' => new ShareResource($this->shared),
                    ]),
                    $this->mergeWhen($this->parent, fn () => [
                        'parent' => [
                            'data' => [
                                'type'       => 'folder',
                                'id'         => $this->parent->id,
                                'attributes' => [
                                    'name' => $this->parent->name,
                                ],
                            ],
                        ],
                    ]),
                    $this->mergeWhen($this->user, fn () => [
                        'user' => [
                            'data' => [
                                'type'       => 'user',
                                'id'         => $this->user_id,
                                'attributes' => [
                                    'name'   => $this->user->settings->name,
                                    'avatar' => $this->user->settings->avatar,
                                    'color'  => $this->user->settings->color,
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
