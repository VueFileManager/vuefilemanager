<?php
namespace Domain\Sharing\Resources;

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
                'id'         => $this->id,
                'type'       => 'shared',
                'attributes' => [
                    'permission' => $this->permission,
                    'protected'  => $this->is_protected,
                    'item_id'    => $this->item_id,
                    'expire_in'  => (int) $this->expire_in,
                    'token'      => $this->token,
                    'link'       => $this->link,
                    'type'       => $this->type,
                ],
            ],
        ];
    }
}
