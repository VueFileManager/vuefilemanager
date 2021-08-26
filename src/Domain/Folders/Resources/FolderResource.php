<?php

namespace Domain\Folders\Resources;

use Domain\Teams\Resources\TeamInvitationsCollection;
use Domain\Teams\Resources\TeamMembersCollection;
use Illuminate\Http\Resources\Json\JsonResource;

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
                    'isTeamFolder'  => $this->team_folder,
                    'items'         => $this->items,
                    'trashed_items' => $this->trashed_items,
                    'updated_at'    => format_date(
                        set_time_by_user_timezone($this->updated_at), __t('time')
                    ),
                    'created_at'    => format_date(
                        set_time_by_user_timezone($this->created_at), __t('time')
                    ),
                ],
                'relationships' => [
                    $this->mergeWhen($this->teamMembers, fn () => [
                        'members' => new TeamMembersCollection($this->teamMembers),
                    ]),
                    $this->mergeWhen($this->teamInvitations, fn () => [
                        'invitations' => new TeamInvitationsCollection($this->teamInvitations),
                    ]),
                    $this->mergeWhen($this->shared, fn () => [
                        'shared' => [
                            'data' => [
                                'id'         => $this->shared->id,
                                'type'       => 'shared',
                                'attributes' => [
                                    'expire_in'  => $this->shared->expire_in,
                                    'link'       => $this->shared->link,
                                    'permission' => $this->shared->permission,
                                    'protected'  => $this->shared->protected,
                                    'token'      => $this->shared->token,
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
