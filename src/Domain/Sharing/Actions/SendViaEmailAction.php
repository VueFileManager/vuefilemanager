<?php
namespace Domain\Sharing\Actions;

use Illuminate\Support\Facades\Notification;
use Domain\Sharing\Notifications\SharedSendViaEmail;

class SendViaEmailAction
{
    public function __invoke(
        array $emails,
        string $token,
    ): void {
        foreach ($emails as $email) {
            Notification::route('mail', $email)
                ->notify(
                    new SharedSendViaEmail($token)
                );
        }
    }
}
