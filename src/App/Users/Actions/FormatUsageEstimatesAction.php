<?php

namespace App\Users\Actions;

use ByteUnits\Metric;
use Illuminate\Support\Collection;

class FormatUsageEstimatesAction
{
    public function __invoke(string $currency, Collection $usage)
    {
        return $usage->map(function ($estimate) use ($currency) {

            // Format usage
            $usage = match ($estimate['feature']) {
                'bandwidth' => Metric::megabytes($estimate['usage'])->format(),
                'storage' => Metric::megabytes($estimate['usage'])->format(),
            };

            // Normalize units
            $amount = $estimate['amount'] / 1000;

            return [
                'feature' => $estimate['feature'],
                'amount'  => $amount,
                'cost'    => format_currency($amount, $currency),
                'usage'   => $usage,
            ];
        });
    }
}