<?php
namespace VueFileManager\Subscription\Domain\FailedPayments\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FailedPaymentResource extends JsonResource
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
                'type'       => 'failed-payment',
                'attributes' => [
                    'amount'     => format_currency($this->amount, $this->currency),
                    'currency'   => $this->currency,
                    'attempts'   => $this->attempts,
                    'source'     => $this->source,
                    'note'       => $this->note,
                    'created_at' => $this->created_at->formatLocalized('%d. %b. %Y'),
                    'updated_at' => $this->updated_at->formatLocalized('%d. %b. %Y'),
                ],
            ],
        ];
    }
}
