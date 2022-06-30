<?php
namespace Domain\Subscriptions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;

class DunningEmailToCoverAccountUsageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Dunning $dunning
    ) {
    }

    public function via(): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(): MailMessage
    {
        $message = $this->dunningMessages();
        $index = $this->dunning->sequence - 1;

        return (new MailMessage)
            ->subject($message[$this->dunning->type][$index]['subject'])
            ->greeting(__('Hi there'))
            ->line($message[$this->dunning->type][$index]['line'])
            ->action(__t('show_billing'), url('/user/settings/billing'))
            ->salutation(__('Regards'));
    }

    public function toArray(): array
    {
        $message = $this->dunningMessages();
        $index = $this->dunning->sequence - 1;

        return [
            'category'    => 'payment-alert',
            'title'       => $message[$this->dunning->type][$index]['subject'],
            'description' => __t('dunning_notification_description'),
            'action'      => [
                'type'   => 'route',
                'params' => [
                    'route'  => __t('billing'),
                    'button' => __t('show_billing'),
                ],
            ],
        ];
    }

    private function dunningMessages(): array
    {
        return [
            'limit_usage_in_new_accounts' => [
                [
                    'subject' => __t('limit_usage_in_new_accounts_1_subject'),
                    'line'    => __t('limit_usage_in_new_accounts_1_line'),
                ],
                [
                    'subject' => __t('limit_usage_in_new_accounts_2_subject'),
                    'line'    => __t('limit_usage_in_new_accounts_2_line'),
                ],
                [
                    'subject' => __t('limit_usage_in_new_accounts_3_subject'),
                    'line'    => __t('limit_usage_in_new_accounts_3_line'),
                ],
            ],
            'usage_bigger_than_balance'   => [
                [
                    'subject' => __t("usage_bigger_than_balance_1_subject"),
                    'line'    => __t('usage_bigger_than_balance_1_line'),
                ],
                [
                    'subject' => __t('usage_bigger_than_balance_2_subject'),
                    'line'    => __t('usage_bigger_than_balance_2_line'),
                ],
                [
                    'subject' => __t('usage_bigger_than_balance_3_subject'),
                    'line'    => __t('usage_bigger_than_balance_3_line'),
                ],
            ],
        ];
    }
}
