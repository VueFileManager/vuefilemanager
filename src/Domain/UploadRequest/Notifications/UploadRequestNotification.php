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
        return ['mail'];
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
        // TODO: add to language strings
        $message = $this->uploadRequest->notes
            ? "PS: {$this->uploadRequest->user->settings->first_name} left you a message: {$this->uploadRequest->notes}"
            : null;

        // TODO: add to language strings
        return (new MailMessage)
            ->subject("{$this->uploadRequest->user->settings->first_name} Request You for File Upload")
            ->greeting('Hello')
            ->line("We are emailing you because {$this->uploadRequest->user->settings->first_name} requested files from you. Please click on the link below and upload your files for {$this->uploadRequest->user->settings->first_name}.")
            ->line($message)
            ->action('Upload your Files', url("/request/{$this->uploadRequest->id}/upload"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
