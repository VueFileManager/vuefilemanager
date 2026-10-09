<?php
namespace App\Users\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $response = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            fn (User $user, string $password) => $this->resetPassword($user, $password)
        );

        return $response === Password::PASSWORD_RESET
            ? response(['message' => Lang::get($response)])
            : response(['error' => Lang::get($response)], 422);
    }

    /**
     * Reset the given user's password and log him in.
     */
    protected function resetPassword(User $user, string $password): void
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        Auth::guard()->login($user);
    }
}
