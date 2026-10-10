<?php
namespace VueFileManager\Subscription\App\User\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VueFileManager\Subscription\Domain\Credits\Models\Balance;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Domain\Credits\Traits\CreditHelpers;
use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
use VueFileManager\Subscription\Domain\BillingAlerts\Models\BillingAlert;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\FailedPayments\Models\FailedPayment;

trait Billable
{
    use CreditHelpers;

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists() && ($this->subscription->active() || $this->subscription->onGracePeriod());
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['active', 'cancelled'])
            ->latest();
    }

    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class, 'user_id', 'id');
    }

    public function creditCards(): HasMany
    {
        return $this->hasMany(CreditCard::class);
    }

    public function failedPayments(): HasMany
    {
        return $this->hasMany(FailedPayment::class);
    }

    public function billingAlert(): HasOne
    {
        return $this->hasOne(BillingAlert::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'user_id', 'id');
    }

    public function dunning(): HasOne
    {
        return $this->hasOne(Dunning::class, 'user_id', 'id');
    }

    /**
     * Get external service customer id
     */
    public function customerId(string $driver)
    {
        return $this->customers()
            ->where('driver', $driver)
            ->first()
            ->driver_user_id ?? null;
    }
}
