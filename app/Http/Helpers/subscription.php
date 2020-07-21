<?php

/**
 * Check if current user subscribed plan is highest
 *
 * @param $id
 * @param $subscribed_capacity
 * @return int
 */
function is_highest_plan($plan)
{
    $plans = app('rinvex.subscriptions.plan')->all();

    $unsubscribed = $plans->filter(function ($item) use ($plan) {
        return $item->id !== $plan->id;
    });

    $capacities = $unsubscribed->map(function ($item) {
        return $item->features->first()->value;
    });

    return max(Arr::flatten($capacities)) < $plan->features->first()->value ? 1 : 0;
}
