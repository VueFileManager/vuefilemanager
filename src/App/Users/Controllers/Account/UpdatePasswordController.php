<?php


namespace App\Users\Controllers\Account;


use App\Http\Controllers\Controller;
use App\Users\Requests\UpdateUserPasswordRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordController extends Controller
{
    public function __invoke(
        UpdateUserPasswordRequest $request
    ): Response{
        $user = Auth::user();

        // Check if is demo
        abort_if(is_demo_account($user->email), 204, 'Changed!');

        // Store new password
        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return response('Changed!', 204);
    }
}