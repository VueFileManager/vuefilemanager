<?php
namespace Domain\Teams\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamInvitationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => [
                'id'            => $this->id,
                'type'          => 'invitation',
                'attributes'    => [
                    'parent_id'  => $this->parent_id,
                    'email'      => $this->email,
                    'color'      => $this->color,
                    'status'     => $this->status,
                    'permission' => $this->permission,
                ],
                'relationships' => [
                    $this->mergeWhen($this->inviter, fn () => [
                        'inviter' => [
                            'data' => [
                                'type'       => 'user',
                                'id'         => $this->inviter->id,
                                'attributes' => [
                                    'name'   => $this->inviter->settings->name,
                                    'avatar' => $this->inviter->settings->avatar,
                                    'color'  => $this->inviter->settings->color,
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
