<?php
namespace Domain\Sharing\Notifications;

use App\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SharedSendViaEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $token,
        public User $user,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__t('shared_link_email_subject', ['user' => $this->user->settings->name]))
            ->greeting(__t('hello'))
            ->line(__t('shared_link_email_user', ['user' => $this->user->settings->name, 'email' => $this->user->email]))
            ->action(__t('shared_link_email_link'), url('/share', ['token' => $this->token]))
            ->salutation(__t('shared_link_email_salutation', ['app_name' => get_settings('app_title') ?? 'VueFileManager']));
    }
}
