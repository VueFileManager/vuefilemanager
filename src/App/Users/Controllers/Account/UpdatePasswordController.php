<?php
namespace App\Users\Controllers\Account;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Users\Requests\UpdateUserPasswordRequest;

class UpdatePasswordController extends Controller
{
    public function __invoke(
        UpdateUserPasswordRequest $request
    ): Response {
        // Check if is demo
        abort_if(is_demo_account(), 204, 'Changed!');

        $user = Auth::user();

        // Store new password
        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return response('Changed!', 204);
    }
}
