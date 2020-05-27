<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
                'id'         => (string)$this->id,
                'type'       => 'user',
                'attributes' => [
                    'name'       => $this->name,
                    'email'      => $this->email,
                    'avatar'     => $this->avatar,
                    'role'       => $this->role,
                    'storage'    => $this->storage,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ]
            ]
        ];
    }
}
