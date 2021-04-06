<?php

namespace Tests\Feature\App;

use App\Models\Setting;
use App\Models\User;
use App\Services\SetupService;
use DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AppUpgradeTest extends TestCase
{
    use DatabaseMigrations;

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function it_upgrade_default_language_translations()
    {
        $this->setup->seed_default_settings('Extended');

        Setting::create([
            'name'  => 'setup_wizard_success',
            'value' => 'setup-done',
        ]);

        Setting::create([
            'name'  => 'license',
            'value' => 'Extended',
        ]);

        collect(['en', 'sk'])
            ->map(function ($locale) {

                DB::table('languages')->insert([
                    'id'     => Str::uuid(),
                    'name'   => 'English',
                    'locale' => $locale
                ]);

                DB::table('language_translations')->insert([
                    [
                        'key'   => 'activation.stripe.button',
                        'value' => 'Set up your Stripe account',
                        'lang'  => $locale
                    ], [
                        'key'   => 'activation.stripe.description',
                        'value' => 'To charge your users, please set up your Stripe account credentials.',
                        'lang'  => $locale
                    ]
                ]);
            });

        Setting::create([
            'name'  => 'language',
            'value' => 'en',
        ]);

        $user = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($user);

        $this->get('/upgrade/translations')
            ->assertStatus(201);

        collect(['en', 'sk'])
            ->map(function ($locale) {

                $this->assertDatabaseHas('language_translations', [
                    'key' => 'activation.stripe.title',
                    'value' => 'Your Stripe account is not set',
                    'lang' => $locale,
                ]);
            });
    }
}
