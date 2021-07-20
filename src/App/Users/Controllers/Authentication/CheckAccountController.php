<?php
namespace App\Users\Controllers\Authentication;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Users\Requests\CheckAccountRequest;
use Illuminate\Http\Response;

class CheckAccountController extends Controller
{
    /**
     * Check if user account exist
     */
    public function __invoke(
        CheckAccountRequest $request
    ): array|Response {

        $user = User::whereEmail($request->input('email'))
            ->first();

        if (! $user) {
            return response(__t('user_not_fount'), 404);
        }

        return [
            'name'     => $user->settings->name,
            'avatar'   => $user->settings->avatar,
            'verified' => $user->email_verified_at ? true : false,
        ];
    }
}
