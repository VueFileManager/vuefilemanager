<?php

namespace App\Traits;

use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
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

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
