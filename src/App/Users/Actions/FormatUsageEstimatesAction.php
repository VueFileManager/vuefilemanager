<?php

namespace App\Users\Actions;

use ByteUnits\Metric;
use Illuminate\Support\Collection;

class FormatUsageEstimatesAction
{
    public function __invoke(string $currency, Collection $usage)
    {
        return $usage->mapWithKeys(function ($estimate) use ($currency) {
            // Format usage
            $usage = match ($estimate['feature']) {
                'bandwidth' => Metric::megabytes($estimate['usage'])->format(),
                'storage' => Metric::megabytes($estimate['usage'])->format(),
                'flatFee' => intval($estimate['usage']) . ' ' . __('Pcs.'),
            };

            // Normalize units
            $amount = match ($estimate['feature']) {
                'bandwidth', 'storage' => $estimate['amount'] / 1000,
                'flatFee' => $estimate['amount'],
            };

            return [
                $estimate['feature'] => [
                    'feature' => $estimate['feature'],
                    'amount'  => $amount,
                    'cost'    => format_currency($amount, $currency),
                    'usage'   => $usage,
                ]
            ];
        });
    }
}
