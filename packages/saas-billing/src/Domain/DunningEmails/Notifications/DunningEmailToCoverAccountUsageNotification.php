<?php
namespace VueFileManager\Subscription\Domain\DunningEmails\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;

class DunningEmailToCoverAccountUsageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Dunning $dunning,
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = [
            'limit_usage_in_new_accounts' => [
                [
                    'subject' => __('Please make first payment for your account to fund your usage.'),
                    'line'    => __('We are happy you are using our service. To continue to using our service, please make first payment for your account balance to fund your usage.'),
                ],
                [
                    'subject' => __('📆 Reminder: Please make first payment for your account to fund your usage.'),
                    'line'    => __('We are happy you are using our service. To continue to using our service, please make first payment for your account balance to fund your usage.'),
                ],
                [
                    'subject' => __('‼️ Uh-oh! Your functionality was restricted. Please make payment to continue using your account.'),
                    'line'    => __('We are sorry for the inconvenience with using our service. To continue to using our service, please make first payment for your account balance to fund your usage and your functionality will be allowed as soon as possible.'),
                ],
            ],
            'usage_bigger_than_balance' => [
                [
                    'subject' => __("⚠️ You don't have sufficient funds in your account, please increase your account balance"),
                    'line'    => __('We are happy you are using our service. To continue to using our service, please increase your funds for your account balance to cover your usage.'),
                ],
                [
                    'subject' => __("📆 Reminder: You don't have sufficient funds in your account, please increase your account balance"),
                    'line'    => __('We are happy you are using our service. To continue to using our service, please increase your funds for your account balance to cover your usage.'),
                ],
                [
                    'subject' => __('‼️ Uh-oh! Your functionality was restricted. Please increase your funds for your account balance to cover your usage.'),
                    'line'    => __('We are sorry for the inconvenience with using our service. To continue to using our service, please increase your funds for your account balance to cover your usage and your functionality will be allowed as soon as possible.'),
                ],
            ],
        ];

        $index = $this->dunning->sequence - 1;

        return (new MailMessage)
            ->subject($message[$this->dunning->type][$index]['subject'])
            ->greeting(__('Hi there'))
            ->line($message[$this->dunning->type][$index]['line'])
            ->salutation(__('Regards'));
    }
}
