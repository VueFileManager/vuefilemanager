<?php
namespace Domain\Notifications\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class MarkUserNotificationsAsReadController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $successMessage = [
            'type'    => 'success',
            'message' => 'All your notifications was marked as read.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        // Mark all notifications as read
        auth()
            ->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return response()->json($successMessage);
    }
}
