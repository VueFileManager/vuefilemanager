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
        return $this->hasMany(Client::class, 'user_id', 'id');
    }

    public function regularInvoices()
    {
        return $this->hasMany(Invoice::class)->whereInvoiceType('regular_invoice');
    }

    public function advanceInvoices()
    {
        return $this->hasMany(Invoice::class)->whereInvoiceType('advance_invoice');
    }
}
