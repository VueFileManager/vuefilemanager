<?php


namespace Domain\Homepage\Controllers;


use App\Http\Controllers\Controller;
use Domain\Homepage\Mail\SendContactMessage;
use Domain\Homepage\Requests\SendContactMessageRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class SendContactMessageController extends Controller
{
    /**
     * Send contact message from homepage
     */
    public function __invoke(
        SendContactMessageRequest $request
    ): Response {
        Mail::to(
            get_setting('contact_email')
        )->send(
            new SendContactMessage($request->all())
        );

        return response('Done', 201);
    }
}