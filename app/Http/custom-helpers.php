<?php

use Laravel\Cashier\Cashier;

/**
 * @param $name
 * @return bool
 */
function is_route($name)
{
    return Route::currentRouteName() === $name;
}

/**
 * @param $filepath
 * @return string
 */
function base64_from_storage_image($filepath)
{
    if (is_null($filepath)) {
        return null;
    }

    if (! Storage::exists($filepath)) {
        return null;
    }

    return 'data:' . Storage::mimeType($filepath) . ';base64,' . base64_encode(Storage::get($filepath));
}

/**
 * Return invoice path to storage
 *
 * @param $invoice
 * @return string
 */
function invoice_path($invoice)
{
    return "files/{$invoice->user_id}/invoice-{$invoice->id}.pdf";
}

/**
 * Get only tax for single invoice item
 *
 * @param $item
 * @return float|int|string
 */
function invoice_item_only_tax_price($item)
{
    return ($item['price'] * $item['amount']) * ($item['tax_rate'] / 100);
}

/**
 * Get item price with tax for single invoice item
 *
 * @param $item
 * @return float|int|string
 */
function invoice_item_with_tax_price($item)
{
    return ($item['price'] * $item['amount']) * ($item['tax_rate'] / 100 + 1);
}

/**
 * @param $invoice
 * @return \Illuminate\Support\Collection
 */
function invoice_tax_base($invoice)
{
    $bag = collect([]);

    // Count tax base
    foreach ($invoice['items'] as $item) {
        if ($bag->whereNotIn('rate', $item['tax_rate'])) {
            $bag->push([
                'rate' => $item['tax_rate'],
                'total' => $item['price'] * $item['amount'],
            ]);
        } else {
            $bag->map(function ($bagItem) use ($item) {
                if ($bagItem['rate'] === $item['tax_rate']) {
                    $bagItem['total'] += ($item['price'] * $item['amount']);
                }
            });
        }
    }

    // Count discount
    if ($invoice['discount_type']) {
        return $bag->map(function ($bagItem) use ($invoice) {
            if ($invoice['discount_type'] === 'percent') {
                $bagItem['total'] -= $bagItem['total'] * ($invoice['discount_rate'] / 100);
            }

            if ($invoice['discount_type'] === 'value') {
                $percentage_of_discount = $invoice['discount_rate'] / (invoice_total($invoice) + $invoice['discount_rate']);

                $bagItem['total'] -= $bagItem['total'] * $percentage_of_discount;
            }

            return $bagItem;
        });
    }

    return $bag;
}

/**
 * @param $invoice
 * @return \Illuminate\Support\Collection
 */
function invoice_tax_summary($invoice)
{
    $bag = collect([]);

    // Count tax base
    foreach ($invoice['items'] as $item) {
        if ($bag->whereNotIn('rate', $item['tax_rate'])) {
            $bag->push([
                'rate' => $item['tax_rate'],
                'total' => ($item['price'] * $item['amount']) * ($item['tax_rate'] / 100),
            ]);
        } else {
            $bag->map(function ($bagItem) use ($item) {
                if ($bagItem['rate'] === $item['tax_rate']) {
                    $bagItem['total'] += ($item['price'] * $item['amount']) * ($item['tax_rate'] / 100);
                }
            });
        }
    }

    // Count discount
    if ($invoice['discount_type']) {
        return $bag->map(function ($bagItem) use ($invoice) {
            if ($invoice['discount_type'] === 'percent') {
                $bagItem['total'] -= $bagItem['total'] * ($invoice['discount_rate'] / 100);
            }

            if ($invoice['discount_type'] === 'value') {
                $percentage_of_discount = $invoice['discount_rate'] / (invoice_total($invoice) + $invoice['discount_rate']);

                $bagItem['total'] -= $bagItem['total'] * $percentage_of_discount;
            }

            return $bagItem;
        });
    }

    return $bag;
}

/**
 * @param $invoice
 * @return float|int|string
 */
function invoice_total($invoice)
{
    $total = 0;

    foreach ($invoice['items'] as $item) {
        $total_without_tax = $item['amount'] * $item['price'];

        if ($item['tax_rate']) {
            $total_without_tax += $total_without_tax * ($item['tax_rate'] / 100);
        }

        $total += $total_without_tax;
    }

    if ($invoice['discount_type']) {
        if ($invoice['discount_type'] === 'percent') {
            $total -= $total * ($invoice['discount_rate'] / 100);
        }

        if ($invoice['discount_type'] === 'value') {
            $total -= $invoice['discount_rate'];
        }
    }

    return $total;
}

/**
 * @param $invoice
 * @param false $format
 * @return float|int|string
 */
function invoice_total_tax($invoice)
{
    $total = 0;

    foreach ($invoice['items'] as $item) {
        $total += ($item['amount'] * $item['price']) * ($item['tax_rate'] / 100);
    }

    return $total;
}

/**
 * @param $value
 * @param string $currency
 * @param string $locale
 * @return string
 */
function format_to_currency($value, $currency = 'CZK', $locale = 'cs')
{
    $amount = round($value, 2) * 100;

    return Cashier::formatAmount((int) $amount, $currency, $locale);
}
