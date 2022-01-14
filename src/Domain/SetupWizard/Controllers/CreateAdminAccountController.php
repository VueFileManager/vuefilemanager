<?php
namespace Domain\SetupWizard\Controllers;

use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Pages\Actions\SeedDefaultPagesAction;
use Domain\Settings\Actions\SeedDefaultSettingsAction;
use Domain\Localization\Actions\SeedDefaultLanguageAction;

/**
 * Create and login admin account
 */
class CreateAdminAccountController extends Controller
{
    public function __construct(
        public SeedDefaultPagesAction $seedDefaultPages,
        public SeedDefaultLanguageAction $seedDefaultLanguage,
        public SeedDefaultSettingsAction $seedDefaultSettingsAction,
    ) {
    }

    public function __invoke(
        Request $request
    ): Response {
        // Validate request
        // TODO: validator do requestu
        $request->validate([
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'name'          => 'required|string',
            'purchase_code' => 'required|string',
            'license'       => 'required|string',
            'avatar'        => 'sometimes|file',
        ]);

        // Create user
        $user = User::forceCreate([
            'role'              => 'admin',
            'email'             => $request->input('email'),
            'password'          => bcrypt($request->input('password')),
            'email_verified_at' => now(),
        ]);

        // Split username
        $name = split_name($request->input('name'));

        $user
            ->settings()
            ->create([
                'avatar'     => store_avatar($request, 'avatar'),
                'first_name' => $name['first_name'],
                'last_name'  => $name['last_name'],
            ]);

        collect([
            [
                'name'  => 'setup_wizard_success',
                'value' => 1,
            ],
            [
                'name'  => 'license',
                'value' => $request->input('license'),
            ],
            [
                'name'  => 'purchase_code',
                'value' => $request->input('purchase_code'),
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name'  => $col['name'],
                'value' => $col['value'],
            ]);
        });

        // Set up application
        ($this->seedDefaultPages)();
        ($this->seedDefaultSettingsAction)($request->input('license'));
        ($this->seedDefaultLanguage)();

        // Login account
        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            return response('Registration was successful', 204);
        }

        return response('Something went wrong', 500);
    }
}
