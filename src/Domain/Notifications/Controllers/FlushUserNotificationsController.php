<?php

namespace Domain\Notifications\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class FlushUserNotificationsController extends Controller
{
    public function __invoke(): Response|Application|ResponseFactory
    {
        if (is_demo_account()) {
            return response('Done', 204);
        }

        // Delete all notifications
        auth()->user()->notifications()->delete();

        return response('Done', 204);
    }
}