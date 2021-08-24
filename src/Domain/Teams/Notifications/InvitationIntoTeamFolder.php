<?php
namespace Domain\Teams\Notifications;

use App\Users\Models\User;
use Illuminate\Bus\Queueable;
use Domain\Folders\Models\Folder;
use Illuminate\Notifications\Notification;
use Domain\Teams\Models\TeamFolderInvitation;
use Illuminate\Notifications\Messages\MailMessage;

class InvitationIntoTeamFolder extends Notification
{
    use Queueable;

    public function __construct(
        public Folder $teamFolder,
        public TeamFolderInvitation $invitation,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $appTitle = get_settings('app_title') ?? 'VueFileManager';

        $user = User::find($this->invitation->email);

        if ($user) {
            return (new MailMessage)
                ->subject("You are invited to collaboration with team folder in $appTitle")
                ->greeting('Hello!')
                ->line('You are invited to collaboration with team folder')
                ->action('Join into Team Folder', url('/team-folder-invitation', ['id' => $this->invitation->id]))
                ->salutation("Regards, $appTitle");
        }

        return (new MailMessage)
            ->subject("You are invited to collaboration with team folder in $appTitle")
            ->greeting('Hello!')
            ->line('You are invited to collaboration with team folder. But at first, you have to create an account to proceed into team folder.')
            ->action('Join & Create an Account', url('/team-folder-invitation', ['id' => $this->invitation->id]))
            ->salutation("Regards, $appTitle");
    }
}
