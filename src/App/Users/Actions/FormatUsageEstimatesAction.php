<?php
namespace App\Users\Actions;

use ByteUnits\Metric;
use Illuminate\Support\Collection;

class FormatUsageEstimatesAction
{
    public function __invoke(string $currency, Collection|array $usage)
    {
        return collect($usage)
            ->mapWithKeys(function ($estimate) use ($currency) {
                // Format usage
                $usage = match ($estimate['feature']) {
                    'bandwidth', 'storage' => Metric::megabytes($estimate['usage'] * 1000)->format(),
                    'flatFee' => intval($estimate['usage']) . ' ' . __t('pcs.'),
                    'member'  => intval($estimate['usage']) . ' ' . __t('mem.'),
                };

                return [
                    $estimate['feature'] => [
                        'feature' => $estimate['feature'],
                        'amount'  => $estimate['amount'],
                        'cost'    => format_currency($estimate['amount'], $currency),
                        'usage'   => $usage,
                    ],
                ];
            });
    }
}
