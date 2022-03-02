<?php
namespace App\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMinimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id'         => $this->id,
                'type'       => 'user',
                'attributes' => [
                    'avatar' => $this->settings->avatar,
                    'name'   => $this->settings->name,
                    'color'  => $this->settings->color,
                    'email'  => $this->email,
                ],
            ],
        ];
    }
}
