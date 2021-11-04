<?php
namespace Domain\Teams\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => [
                'id'         => $this->id,
                'type'       => 'member',
                'attributes' => [
                    'email'      => $this->email,
                    'name'       => $this->settings->name,
                    'avatar'     => $this->settings->avatar,
                    'color'      => $this->settings->color,
                    'permission' => $this->pivot->permission,
                ],
            ],
        ];
    }
}
