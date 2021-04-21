<?php

use Laravel\Cashier\Cashier;

/**
 * @param $invoice
 * @param false $format
 * @return float|int|mixed|string
 */
function invoice_total_discount($invoice, $format = false)
{
    // Percent discount
    if ($invoice['discount_type'] === 'percent') {

        $discount = invoice_total_net($invoice) * ($invoice['discount_rate'] / 100);

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