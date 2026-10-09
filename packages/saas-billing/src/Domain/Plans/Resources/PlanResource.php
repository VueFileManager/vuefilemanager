<?php
namespace VueFileManager\Subscription\Domain\Plans\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
                'id'          => $this->id,
                'type'        => 'plans',
                'attributes'  => match ($this->type) {
                    'metered' => $this->getMeteredAttributes(),
                    'fixed'   => $this->getFixedAttributes(),
                },
                'meta'       => [
                    // Get gateway driver ids
                    'driver_plan_id' => $this->drivers->pluck('driver_plan_id', 'driver'),
                ],
            ],
        ];
    }

    private function getFixedAttributes(): array
    {
        return [
            'name'        => $this->name,
            'status'      => $this->status,
            'type'        => $this->type,
            'visible'     => $this->visible,
            'currency'    => $this->currency,
            'description' => $this->description,
            'subscribers' => $this->subscriptions->count(),
            'price'       => format_currency($this->amount, $this->currency),
            'amount'      => $this->amount,
            'features'    => $this->fixedFeatures->pluck('value', 'key'),
            'interval'    => $this->interval,
            'created_at'  => $this->created_at->formatLocalized('%d. %b. %Y'),
            'updated_at'  => $this->updated_at->formatLocalized('%d. %b. %Y'),
        ];
    }

    private function getMeteredAttributes(): array
    {
        return [
            'name'        => $this->name,
            'status'      => $this->status,
            'type'        => $this->type,
            'visible'     => $this->visible,
            'currency'    => $this->currency,
            'interval'    => $this->interval,
            'description' => $this->description,
            'subscribers' => $this->subscriptions->count(),
            'features'    => $this->meteredFeatures->mapWithKeys(fn ($price) => [
                $price['key'] => [
                    'aggregate_strategy' => $price['aggregate_strategy'],
                    'tiers'              => $price['tiers'],
                ],
            ]),
            'created_at'  => $this->created_at->formatLocalized('%d. %b. %Y'),
            'updated_at'  => $this->updated_at->formatLocalized('%d. %b. %Y'),
        ];
    }
}
