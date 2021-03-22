<?php

namespace App\Traits;

use App\Models\Oasis\SubscriptionRequest;

trait Oasis
{
    /**
     * Get user subscription request
     *
     * @return mixed
     */
    public function subscriptionRequest()
    {
        return $this->hasOne(SubscriptionRequest::class);
    }
}
