<?php
namespace Domain\Notifications\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class FlushUserNotificationsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $successMessage = [
            'type'    => 'success',
            'message' => 'All your notifications was deleted.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        // Delete all notifications
        auth()
            ->user()
            ->notifications()
            ->delete();

        return response()->json($successMessage);
    }
}
