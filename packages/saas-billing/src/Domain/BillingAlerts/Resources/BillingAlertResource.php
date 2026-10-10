<?php
namespace VueFileManager\Subscription\Domain\BillingAlerts\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillingAlertResource extends JsonResource
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
                'type'       => 'balance',
                'attributes' => [
                    'formatted' => format_currency($this->amount, $this->user->balance->currency),
                    'amount'    => $this->amount,
                ],
            ],
        ];
    }
}
