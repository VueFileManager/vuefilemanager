<?php

function mapStripeStatus($status): string
{
    return match ($status) {
        'active', 'trialing' => 'active',
        'canceled', 'unpaid' => 'cancelled',
        'incomplete' => 'inactive',
        'incomplete_expired', 'past_due' => 'completed',
    };
}

function mapStripeAggregateStrategy($strategy): string
{
    return match ($strategy) {
        'sum_of_usage'  => 'sum',
        'maximum_usage' => 'max',
    };
}

function mapStripeTiers($tiers): array
{
    return collect($tiers)
        ->map(fn ($tier) => [
            'up_to'               => 'inf',
            'flat_amount_decimal' => $tier['flat_fee'] ? ($tier['flat_fee'] * 100) : null,
            'unit_amount_decimal' => $tier['per_unit'] * 100,
        ])->toArray();
}
