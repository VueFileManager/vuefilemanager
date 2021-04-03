<?php

namespace App\Actions\Fortify;

use App\Models\Setting;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

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
        if (!intval($settings['registration'])) {
            abort(401);
        }

        Validator::make($input, [
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
            'email'    => $input['email'],
            'password' => bcrypt($input['password']),
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name'             => $input['name'],
                'storage_capacity' => $settings['storage_default'],
            ]);

        UserSettings::reguard();

        return $user;
    }
}
