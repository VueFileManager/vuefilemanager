<?php
namespace Domain\UploadRequest\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Domain\UploadRequest\Models\UploadRequest;
use Illuminate\Notifications\Messages\MailMessage;

class UploadRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public UploadRequest $uploadRequest
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Format optional message
        $message = $this->uploadRequest->notes
            ? __t('file_request_optional_message', ['name' => $this->uploadRequest->user->settings->first_name, 'notes' => $this->uploadRequest->notes])
            : null;

        return (new MailMessage)
            ->subject(__t('file_request_notify_title', ['name' => $this->uploadRequest->user->settings->first_name]))
            ->greeting(__t('hello'))
            ->line(__t('file_request_notify_description', ['name' => $this->uploadRequest->user->settings->first_name]))
            ->line($message)
            ->action(__t('upload_your_files'), url("/request/{$this->uploadRequest->id}/upload"))
            ->salutation(__t('thanks_salutation'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'category'    => 'file-request',
            'title'       => __t('file_request_notify_title', ['name' => $this->uploadRequest->user->settings->first_name]),
            'description' => __t('file_request_notify_center_description', ['name' => $this->uploadRequest->user->settings->first_name]),
            'action'      => [
                'type'   => 'url',
                'params' => [
                    'target' => 'blank',
                    'url'    => url("/request/{$this->uploadRequest->id}/upload"),
                    'button' => __t('upload_files'),
                ],
            ],
        ];
    }
}
