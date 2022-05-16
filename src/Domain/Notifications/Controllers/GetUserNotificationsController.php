<?php
namespace Domain\Notifications\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Notifications\Resources\NotificationCollection;

class GetUserNotificationsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $notifications = new NotificationCollection(
            auth()->user()->notifications
        );

        return response()->json($notifications);
    }
}
