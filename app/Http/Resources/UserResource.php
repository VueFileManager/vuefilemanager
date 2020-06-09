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
            'data'          => [
                'id'         => (string)$this->id,
                'type'       => 'user',
                'attributes' => [
                    'name'                 => env('APP_DEMO') ? $faker->name : $this->name,
                    'email'                => env('APP_DEMO') ? $faker->email : $this->email,
                    'avatar'               => $this->avatar,
                    'role'                 => $this->role,
                    'created_at_formatted' => format_date($this->created_at, '%d. %B. %Y'),
                    'created_at'           => $this->created_at,
                    'updated_at'           => $this->updated_at,
                ]
            ],
            'relationships' => [
                'subscription' => $this->activeSubscriptions()->count() !== 0
                    ? new UserSubscription($this->subscription('main'))
                    : null,
                'settings'     => [
                    'data' => [
                        'id'         => (string)$this->settings->id,
                        'type'       => 'settings',
                        'attributes' => [
                            'billing_name'         => $this->settings->billing_name,
                            'billing_address'      => $this->settings->billing_address,
                            'billing_state'        => $this->settings->billing_state,
                            'billing_city'         => $this->settings->billing_city,
                            'billing_postal_code'  => $this->settings->billing_postal_code,
                            'billing_country'      => $this->settings->billing_country,
                            'billing_phone_number' => $this->settings->billing_phone_number,
                        ]
                    ]
                ],
                'storage'      => [
                    'data' => [
                        'id'         => '1',
                        'type'       => 'storage',
                        'attributes' => $this->storage
                    ]
                ],
                'favourites'   => [
                    'data' => [
                        'id'         => '1',
                        'type'       => 'folders_favourite',
                        'attributes' => [
                            'folders' => $this->favourite_folders->makeHidden(['pivot'])
                        ],
                    ],
                ],
                'tree'   => [
                    'data' => [
                        'id'         => '1',
                        'type'       => 'folders_tree',
                        'attributes' => [
                            'folders' => $this->folder_tree
                        ],
                    ],
                ],
            ]
        ];
    }
}
