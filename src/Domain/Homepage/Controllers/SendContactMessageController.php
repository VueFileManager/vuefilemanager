<?php
namespace Domain\Homepage\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Domain\Homepage\Mail\SendContactMessage;
use Domain\Homepage\Requests\SendContactMessageRequest;

class SendContactMessageController extends Controller
{
    /**
     * Send contact message from homepage
     */
    public function __invoke(
        SendContactMessageRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The message was successfully send',
        ];

        // Return success in demo mode
        if (is_demo()) {
            return response()->json($message);
        }

        // Get contact mail
        if ($contactEmail = get_settings('contact_email')) {
            Mail::to($contactEmail)->send(new SendContactMessage($request->all()));
        }

        return response()->json($message);
    }
}
