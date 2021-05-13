<?php
namespace App\Traits;

use App\Models\Oasis\SubscriptionRequest;

trait Oasis
{
    public function subscriptionRequest()
    {
        return $this->hasOne(SubscriptionRequest::class);
    }
}
