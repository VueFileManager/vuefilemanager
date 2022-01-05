<?php
namespace Domain\Folders\Resources;

use Carbon\Carbon;
use Domain\Sharing\Resources\ShareResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Domain\Teams\Resources\TeamMembersCollection;
use Domain\Teams\Resources\TeamInvitationsCollection;

class FolderResource extends JsonResource
{
    public function toArray($request): array
    {
        // TODO: optimize created_at/updated_at conversion because of performance issue
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
                    'updated_at'    => $this->updated_at,
                    'created_at'    => Carbon::parse($this->created_at)->diffForHumans(),
                    'deleted_at'    => $this->deleted_at ? Carbon::parse($this->deleted_at)->diffForHumans() : null,
                    /*'updated_at'    => format_date(
                        set_time_by_user_timezone($this->updated_at), __t('time')
                    ),
                    'created_at'    => format_date(
                        set_time_by_user_timezone($this->created_at), __t('time')
                    ),*/
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
                    $this->mergeWhen($this->owner, fn () => [
                        'owner' => [
                            'data' => [
                                'type'       => 'owner',
                                'id'         => $this->user_id,
                                'attributes' => [
                                    'name'   => $this->owner->settings->name,
                                    'avatar' => $this->owner->settings->avatar,
                                    'color'  => $this->owner->settings->color,
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
