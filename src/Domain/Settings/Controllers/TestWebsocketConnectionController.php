<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\JsonResponse;
use Domain\Settings\Events\TestWebsocketConnectionEvent;

class TestWebsocketConnectionController
{
    public function __invoke(): JsonResponse
    {
        TestWebsocketConnectionEvent::dispatch(
            auth()->user()
        );

        return response()->json([
            'type'    => 'success',
            'message' => 'The websocket test event was successfully dispatched.',
        ]);
    }
}
