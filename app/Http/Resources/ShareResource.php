<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShareResource extends JsonResource
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
                'type'       => 'shares',
                'attributes' => [
                    'permission' => $this->permission,
                    'protected'  => (int) $this->protected,
                    'item_id'    => (int) $this->item_id,
                    'expire_in'  => (int) $this->expire_in,
                    'token'      => $this->token,
                    'link'       => $this->link,
                    'type'       => $this->type,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ]
            ]
        ];
    }
}
