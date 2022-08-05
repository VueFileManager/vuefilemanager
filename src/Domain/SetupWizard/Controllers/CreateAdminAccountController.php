<?php
namespace Domain\SetupWizard\Controllers;

use Artisan;
use App\Users\Models\User;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Domain\Pages\Actions\SeedDefaultPagesAction;
use Domain\Settings\Actions\SeedDefaultSettingsAction;
use Domain\SetupWizard\Requests\StoreAdminAccountRequest;
use Domain\Localization\Actions\SeedDefaultLanguageAction;
use Domain\SetupWizard\Actions\CreateDiskDirectoriesAction;

/**
 * Create and login admin account
 */
class CreateAdminAccountController extends Controller
{
    public function __construct(
        protected StatefulGuard $guard,
        public SeedDefaultPagesAction $seedDefaultPages,
        public SeedDefaultLanguageAction $seedDefaultLanguage,
        public CreateDiskDirectoriesAction $createDiskDirectories,
        public SeedDefaultSettingsAction $seedDefaultSettingsAction,
    ) {
    }

    public function __invoke(
        StoreAdminAccountRequest $request
    ): Response {
        // Create default directories
        ($this->createDiskDirectories)();

        // Create user
        $admin = User::forceCreate([
            'role'              => 'admin',
            'email'             => $request->input('email'),
            'password'          => bcrypt($request->input('password')),
            'email_verified_at' => now(),
        ]);

        // Split username
        $name = split_name($request->input('name'));

        // Store user data
        $admin->settings()->create([
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
                'value' => strtolower($request->input('license')),
            ],
            [
                'name'  => 'purchase_code',
                'value' => $request->input('purchase_code'),
            ],
        ])->each(function ($col) {
            Setting::updateOrCreate([
                'name'  => $col['name'],
            ], [
                'value' => $col['value'],
            ]);
        });

        Artisan::call('key:generate', [
            '--force' => true,
        ]);

        Artisan::call('config:clear');

        // Set up application
        ($this->seedDefaultPages)();
        ($this->seedDefaultSettingsAction)();
        ($this->seedDefaultLanguage)();

        return response('Registration was successful', 204);
    }
}
