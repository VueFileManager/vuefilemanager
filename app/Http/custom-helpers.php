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
function get_storage_path($filepath)
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
 * Get only tax for single invoice item
 *
 * @param $item
 * @param false $format
 * @return float|int|string
 */
function invoice_item_only_tax_price($item, $format = false)
{
    $tax = ($item['price'] * $item['amount']) * ($item['tax_rate'] / 100);

    if ($format) {
        return Cashier::formatAmount($tax * 100, 'CZK', 'cs');
    }

    return $tax;
}

/**
 * Get item price with tax for single invoice item
 *
 * @param $item
 * @param false $format
 * @return float|int|string
 */
function invoice_item_with_tax_price($item, $format = false)
{
    $tax = ($item['price'] * $item['amount']) * ($item['tax_rate'] / 100 + 1);

    if ($format) {
        return Cashier::formatAmount($tax * 100, 'CZK', 'cs');
    }

    return $tax;
}

/**
 * @param $invoice
 * @param false $format
 * @return float|int|mixed|string
 */
function invoice_total_discount($invoice, $format = false)
{
    // Percent discount
    if ($invoice['discount_type'] === 'percent') {

        $discount = (invoice_total_net($invoice) + invoice_total_tax($invoice)) * ($invoice['discount_rate'] / 100);

        if ($format) {
            return Cashier::formatAmount($discount * 100, $invoice['currency'], 'cs');
        }

        return $discount;
    }

    // Value discount
    if ($invoice['discount_type'] === 'value') {

        if ($format) {
            return Cashier::formatAmount($invoice['discount_rate'] * 100, $invoice['currency'], 'cs');
        }

        return $invoice['discount_rate'];
    }
}

/**
 * @param $invoice
 * @param false $format
 * @return float|int|string
 */
function invoice_total_net($invoice, $format = false)
{
    $total = 0;

    foreach ($invoice['items'] as $item) {
        $total += $item['amount'] * $item['price'];
    }

    if ($format) {
        return Cashier::formatAmount(($total * 100), $invoice['currency'], 'cs');
    }

    return $total;
}

/**
 * @param $invoice
 * @param false $format
 * @return float|int|string
 */
function invoice_total_tax($invoice, $format = false)
{
    $total = 0;

    foreach ($invoice['items'] as $item) {
        $total += ($item['amount'] * $item['price']) * ($item['tax_rate'] / 100);
    }

    if ($format) {
        return Cashier::formatAmount(($total * 100), $invoice['currency'], 'cs');
    }

    return $total;
}

/**
 * @param $value
 * @param $currency
 * @param string $locale
 * @return string
 */
function format_to_currency($value, $currency = 'CZK', $locale = 'cs')
{
    return Cashier::formatAmount(($value * 100), $currency, $locale);
}