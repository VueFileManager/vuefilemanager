<?php
namespace VueFileManager\Subscription\Domain\FailedPayments\Actions;

use VueFileManager\Subscription\Domain\FailedPayments\Models\FailedPayment;
use VueFileManager\Subscription\Domain\Credits\Exceptions\InsufficientBalanceException;

class RetryWithdrawnFromBalanceAction
{
    public function __invoke($user)
    {
        // Get failed payments and try pay it
        $user
            ->failedPayments()
            ->orderByDesc('amount')
            ->each(function (FailedPayment $failedPayment) use ($user) {
                try {
                    // Withdraw balance
                    $user->withdrawBalance($failedPayment->amount);

                    // Create transaction
                    $failedPayment->user->transactions()->create([
                        'type'     => 'withdrawal',
                        'status'   => 'completed',
                        'note'     => $failedPayment->note,
                        'currency' => $failedPayment->currency,
                        'amount'   => $failedPayment->amount,
                        'driver'   => 'system',
                        'metadata' => $failedPayment->metadata,
                    ]);

                    // delete failed payment
                    $failedPayment->delete();
                } catch (InsufficientBalanceException $e) {
                    // Get notification
                    $InsufficientBalanceNotification = config('subscription.notifications.InsufficientBalanceNotification');

                    // Notify user
                    $user->notify(new $InsufficientBalanceNotification());
                }
            });
    }
}
