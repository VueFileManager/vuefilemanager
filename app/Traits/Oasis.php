<?php

namespace App\Traits;

use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use App\Models\Oasis\InvoiceProfile;
use App\Models\Oasis\SubscriptionRequest;

trait Oasis
{
    public function subscriptionRequest()
    {
        return $this->hasOne(SubscriptionRequest::class);
    }

    public function invoiceProfile()
    {
        return $this->hasOne(InvoiceProfile::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id', 'id')
            ->orderByDesc('created_at');
    }

    public function regularInvoices()
    {
        return $this->hasMany(Invoice::class)->whereInvoiceType('regular-invoice');
    }

    public function advanceInvoices()
    {
        return $this->hasMany(Invoice::class)->whereInvoiceType('advance-invoice');
    }
}
