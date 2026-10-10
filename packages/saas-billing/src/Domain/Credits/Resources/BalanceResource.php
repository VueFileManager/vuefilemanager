<?php
namespace VueFileManager\Subscription\Domain\Credits\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
                    'formatted' => format_currency($this->amount, $this->currency),
                    'balance'   => $this->amount,
                    'currency'  => $this->currency,
                ],
            ],
        ];
    }
}
