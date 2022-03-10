<?php
namespace Domain\Notifications\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class MarkUserNotificationsAsReadController extends Controller
{
    public function __invoke(): Response|Application|ResponseFactory
    {
        if (is_demo_account()) {
            return response('Done', 204);
        }

        // Mark all notifications as read
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response('Done', 204);
    }
}
