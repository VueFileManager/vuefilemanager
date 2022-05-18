<?php
namespace Domain\Teams\Notifications;

use App\Users\Models\User;
use Illuminate\Bus\Queueable;
use Domain\Folders\Models\Folder;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Domain\Teams\Models\TeamFolderInvitation;
use Illuminate\Notifications\Messages\MailMessage;

class InvitationIntoTeamFolder extends Notification implements ShouldQueue
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
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $appTitle = get_settings('app_title') ?? 'VueFileManager';

        // Try to find the user via email
        $user = User::where('email', $this->invitation->email)
            ->first();

        if ($user) {
            return (new MailMessage)
                ->subject(__t('team_invitation_notify_title', ['app' => $appTitle]))
                ->greeting(__t('hello'))
                ->line(__t('team_invitation_notify_desc'))
                ->action(__t('join_into_team_folder'), url('/team-folder-invitation', ['id' => $this->invitation->id]))
                ->salutation(__t('salutation') . ', ' .  $appTitle);
        }

        return (new MailMessage)
            ->subject(__t('team_invitation_notify_title', ['app' => $appTitle]))
            ->greeting(__t('hello'))
            ->line(__t('team_invitation_notify_desc_without_account'))
            ->action(__t('join_and_create_account'), url('/team-folder-invitation', ['id' => $this->invitation->id]))
            ->salutation(__t('salutation') . ', ' .  $appTitle);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'category'    => 'team-invitation',
            'title'       => __t('new_team_invitation'),
            'description' => __t('x_invite_to_join_team', ['name' => $this->invitation->inviter->settings->name, ]),
            'action'      => [
                'type'   => 'invitation',
                'params' => [
                    'id' => $this->invitation->id,
                ],
            ],
        ];
    }
}
