<?php
namespace Domain\Transactions\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Users\Actions\FormatUsageEstimatesAction;

class TransactionResource extends JsonResource
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
                'type'          => 'transactions',
                'attributes'    => [
                    'type'       => $this->type,
                    'status'     => $this->status,
                    'note'       => $this->note,
                    'price'      => format_currency($this->amount, $this->currency),
                    'currency'   => $this->currency,
                    'amount'     => $this->amount,
                    'driver'     => $this->driver,
                    'reference'  => $this->reference,
                    'metadata'   => $this->metadata
                        ? resolve(FormatUsageEstimatesAction::class)($this->currency, $this->metadata)
                        : null,
                    'created_at' => format_date($this->created_at, 'd. M. Y'),
                    'updated_at' => format_date($this->updated_at, 'd. M. Y'),
                ],
                'relationships' => [
                    $this->mergeWhen($this->user && $this->user->settings, fn () => [
                        'user' => [
                            'data' => [
                                'id'         => $this->user->id,
                                'type'       => 'users',
                                'attributes' => [
                                    'avatar'     => $this->user->settings->avatar,
                                    'first_name' => $this->user->settings->first_name,
                                    'last_name'  => $this->user->settings->last_name,
                                    'name'       => $this->user->settings->name,
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
