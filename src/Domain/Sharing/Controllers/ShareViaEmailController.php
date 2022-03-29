<?php
namespace Domain\Sharing\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Sharing\Actions\SendViaEmailAction;

class ShareViaEmailController extends Controller
{
    public function __construct(
        private SendViaEmailAction $sendLinkToEmailAction,
    ) {
    }

    public function __invoke(
        Request $request,
        string $token,
    ): Response {
        ($this->sendLinkToEmailAction)->onQueue()->execute(
            emails: $request->input('emails'),
            token: $token,
            user: Auth::user(),
        );

        return response('Done.', 204);
    }
}
