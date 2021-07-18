<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Users\Models\UserSettings;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\StatefulGuard;

class CreateNewUserAction extends Controller
{
    use PasswordValidationRules;

    public function __construct(
        protected StatefulGuard $guard
    ) {
    }

    /**
     * Validate and create a newly registered user.
     */
    public function __invoke(Request $request): Response
    {
        $settings = Setting::whereIn('name', [
            'storage_default', 'registration',
        ])
            ->pluck('value', 'name');

        // Check if account registration is enabled
        if (! intval($settings['registration'])) {
            abort(401);
        }

        Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name'             => $request->name,
                'storage_capacity' => $settings['storage_default'],
            ]);

        if (! get_setting('user_verification')) {
            $user->markEmailAsVerified();
        }

        UserSettings::reguard();

        event(new Registered($user));

        if (! get_setting('user_verification')) {
            $this->guard->login($user);
        }

        return response('User registered successfully', 201);
    }
}
