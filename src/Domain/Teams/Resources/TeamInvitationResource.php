<?php

namespace Domain\Teams\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamInvitationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => [
                'id'         => $this->id,
                'type'       => 'invitation',
                'attributes' => [
                    'email'      => $this->email,
                    'color'      => $this->color,
                    'status'     => $this->status,
                    'permission' => $this->permission,
                ],
            ],
        ];
    }
}
