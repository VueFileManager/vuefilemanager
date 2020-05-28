<?php

namespace App\Http\Resources;

use Faker\Factory;
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
        // Faker only for demo purpose
        $faker = Factory::create();

        return [
            'data' => [
                'id'         => (string)$this->id,
                'type'       => 'user',
                'attributes' => [
                    'name'                 => env('APP_DEMO') ? $faker->name : $this->name,
                    'email'                => env('APP_DEMO') ? $faker->email : $this->email,
                    'avatar'               => $this->avatar,
                    'role'                 => $this->role,
                    'storage'              => $this->storage,
                    'created_at_formatted' => format_date($this->created_at, '%d. %B. %Y'),
                    'created_at'           => $this->created_at,
                    'updated_at'           => $this->updated_at,
                ]
            ]
        ];
    }
}
