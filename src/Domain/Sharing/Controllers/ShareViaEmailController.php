<?php
namespace Domain\Sharing\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Sharing\Actions\SendViaEmailAction;

class ShareViaEmailController extends Controller
{
    public function __invoke(
        SendViaEmailAction $sendLinkToEmailAction,
        Request $request,
        string $token,
    ): Response {
        ($sendLinkToEmailAction)(
            $request->input('emails'),
            $token
        );

        return response('Done!', 204);
    }
}
