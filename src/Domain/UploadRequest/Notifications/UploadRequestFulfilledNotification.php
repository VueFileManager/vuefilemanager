<?php
namespace Domain\UploadRequest\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Domain\UploadRequest\Models\UploadRequest;
use Illuminate\Notifications\Messages\MailMessage;

class UploadRequestFulfilledNotification extends Notification implements ShouldQueue
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
     */
    public function via(mixed $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__t('file_request_filled_mail', ['name' => $this->uploadRequest->parent->name]))
            ->greeting(__t('hello'))
            ->line(__t('file_request_filled_mail_note'))
            ->action(__t('show_files'), url("/platform/files/{$this->uploadRequest->id}"))
            ->salutation(__t('thanks_salutation'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'category'    => 'file-request',
            'title'       => __t('file_request_filled'),
            'description' => __t('file_request_filled_desc', ['name' => $this->uploadRequest->parent->name, ]),
            'action'      => [
                'type'   => 'route',
                'params' => [
                    'route'  => 'Files',
                    'button' => __t('show_files'),
                    'id'     => $this->uploadRequest->id,
                ],
            ],
        ];
    }
}
