<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use VueFileManager\Subscription\Domain\Plans\Resources\PlanResource;

class SubscriptionResource extends JsonResource
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
                'type'          => 'subscription',
                'attributes'    => [
                    'is_cancelled'  => $this->cancelled(),
                    'driver'        => $this->driverName(),
                    'name'          => $this->name,
                    'status'        => $this->status,
                    'trial_ends_at' => $this->trial_ends_at,
                    'updated_at'    => $this->updated_at->formatLocalized('%d. %b. %Y'),
                    'created_at'    => $this->created_at->formatLocalized('%d. %b. %Y'),
                    'renews_at'     => $this->renews_at
                        ? $this->renews_at->formatLocalized('%d. %b. %Y')
                        : $this->created_at->addDays(28)->formatLocalized('%d. %b. %Y'),
                    'ends_at'       => $this->ends_at?->formatLocalized('%d. %b. %Y'),
                ],
                'relationships' => [
                    'plan' => new PlanResource($this->plan),
                    $this->mergeWhen($this->user && $this->user->settings, fn () => [
                        'user' => [
                            'data' => [
                                'id'         => $this->user->id,
                                'type'       => 'users',
                                'attributes' => [
                                    'avatar'     => $this->user->settings->avatar,
                                    'name'       => $this->user->settings->name,
                                    'first_name' => $this->user->settings->first_name,
                                    'last_name'  => $this->user->settings->last_name,
                                    'color'      => $this->user->settings->color,
                                    'email'      => $this->user->email,
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
