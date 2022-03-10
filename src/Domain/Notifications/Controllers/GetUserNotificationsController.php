<?php
namespace Domain\Notifications\Controllers;

use App\Http\Controllers\Controller;
use Domain\Notifications\Resources\NotificationCollection;

class GetUserNotificationsController extends Controller
{
    public function __invoke(): NotificationCollection
    {
        return new NotificationCollection(
            auth()->user()->notifications
        );
    }
}
