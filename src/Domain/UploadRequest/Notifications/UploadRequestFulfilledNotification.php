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
        // TODO: add to language strings
        return (new MailMessage)
            ->subject("Your file request was fulfilled in your '{$this->uploadRequest->parent->name}' folder")
            ->greeting('Hello')
            ->line('We are emailing you because your file request was fulfilled. Please click on the link below to show uploaded files.')
            ->action('Show Files', url("/platform/files/{$this->uploadRequest->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'type'        => 'file-request',
            'title'       => 'File Request Filled',
            'description' => "Your file request for '{$this->uploadRequest->parent->name}' folder was filled successfully.",
            'action'      => [
                'type'   => 'route',
                'params' => [
                    'route'  => 'Files',
                    'button' => 'Show Files',
                    'id'     => $this->uploadRequest->id,
                ],
            ],
        ];
    }
}
