<?php
namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Setting;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $settings = Setting::whereIn('name', ['storage_default', 'registration'])
            ->pluck('value', 'name');

        // Check if account registration is enabled
        if (! intval($settings['registration'])) {
            abort(401);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name' => $input['name'],
                'storage_capacity' => $settings['storage_default'],
            ]);

        if(!get_setting('user_verification')) {
            $user->markEmailAsVerified();
        }
        
        UserSettings::reguard();

        return $user;
    }
    
    /**
     * Create a new registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Laravel\Fortify\Contracts\RegisterResponse
     */
    public function store(Request $request,
                          CreatesNewUsers $creator): RegisterResponse
    {
        event(new Registered($user = $creator->create($request->all())));

        if(! get_setting('user_verification')) {
            $this->guard->login($user);
        };

        return app(RegisterResponse::class);
    }
}
