<?php
namespace Domain\Sharing\Controllers;

use Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Sharing\Actions\SendViaEmailAction;
use Domain\Sharing\Requests\ShareByEmailRequest;

class ShareViaEmailController extends Controller
{
    public function __construct(
        private SendViaEmailAction $sendLinkToEmailAction,
    ) {
    }

    public function __invoke(
        ShareByEmailRequest $request,
        string $token,
    ): JsonResponse {
        ($this->sendLinkToEmailAction)->onQueue()->execute(
            emails: $request->input('emails'),
            token: $token,
            user: Auth::user(),
        );

        return response()->json([
            'type'    => 'success',
            'message' => 'The share link was shared via email successfully.',
        ]);
    }
}
