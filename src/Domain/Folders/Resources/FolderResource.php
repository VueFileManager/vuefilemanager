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
                    'isTeamFolder'  => false,
                    'items'         => $this->items,
                    'trashed_items' => $this->trashed_items,
                    'created_at'    => set_time_by_user_timezone($this->user, $this->created_at),
                    'updated_at'    => set_time_by_user_timezone($this->user, $this->updated_at),
                    'deleted_at'    => $this->deleted_at
                        ? set_time_by_user_timezone($this->user, $this->deleted_at)
                        : null,
                ],
                'relationships' => [
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
                ],
            ],
        ];
    }
}
