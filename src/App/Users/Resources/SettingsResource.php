<?php
namespace App\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
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
                'id'         => $this->user_id,
                'type'       => 'settings',
                'attributes' => [
                    'avatar'       => $this->avatar,
                    'name'         => $this->name,
                    'first_name'   => $this->first_name,
                    'last_name'    => $this->last_name,
                    'address'      => $this->address,
                    'state'        => $this->state,
                    'city'         => $this->city,
                    'postal_code'  => $this->postal_code,
                    'country'      => $this->country,
                    'phone_number' => $this->phone_number,
                    'timezone'     => $this->timezone,
                ],
            ],
        ];
    }
}
