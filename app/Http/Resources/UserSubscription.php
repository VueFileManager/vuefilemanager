<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscription extends JsonResource
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
                'type'       => 'subscription',
                'attributes' => [
                    'name'      => $this->name,
                    'slug'      => $this->slug,
                    'starts_at' => format_date($this->starts_at, '%d. %B. %Y'),
                    'ends_at'   => format_date($this->ends_at, '%d. %B. %Y'),
                ]
            ]
        ];
    }
}
