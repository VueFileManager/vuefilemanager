<?php

namespace App\Http\Resources\Oasis;

use App\Http\Resources\PlanResource;
use App\Http\Resources\PricingResource;
use App\Services\StripeService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class SubscriptionRequestResource extends JsonResource
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
                'id'            => $this->id,
                'type'          => 'subscription-requests',
                'attributes'    => [
                    'requested_plan'       => $this->requested_plan,
                    'status'               => $this->status,
                    'created_at_formatted' => format_date($this->created_at, '%d. %B. %Y'),
                ],
                'relationships' => [
                    'user' => [
                        'data' => [
                            'id'         => $this->user->id,
                            'type'       => 'users',
                            'attributes' => [
                                'name'         => $this->user->settings->name,
                                'address'      => $this->user->settings->address,
                                'state'        => $this->user->settings->state,
                                'city'         => $this->user->settings->city,
                                'postal_code'  => $this->user->settings->postal_code,
                                'country'      => $this->user->settings->country,
                                'phone_number' => $this->user->settings->phone_number,
                            ]
                        ]
                    ],
                    'plan' => new PricingResource(
                        resolve(StripeService::class)->getPlan($this->requested_plan)
                    ),
                ]
            ],
        ];
    }
}
