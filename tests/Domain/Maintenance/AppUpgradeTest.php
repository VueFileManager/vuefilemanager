<?php
namespace Tests\Domain\Maintenance;

use DB;
use Tests\TestCase;
use Illuminate\Support\Str;
use Domain\Settings\Models\User;

class AppUpgradeTest extends TestCase
{
    /**
     * @test
     */
    public function it_upgrade_default_language_translations()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'admin']);

        DB::table('settings')
            ->insert([
                [
                    'name'  => 'language',
                    'value' => 'en',
                ],
                [
                    'name'  => 'license',
                    'value' => 'Extended',
                ],
            ]);

        collect(['en', 'sk'])
            ->map(function ($locale) {
                DB::table('languages')
                    ->insert([
                        'id'     => Str::uuid(),
                        'name'   => 'English',
                        'locale' => $locale,
                    ]);

                DB::table('language_translations')
                    ->insert([
                        [
                            'key'   => 'activation.stripe.button',
                            'value' => 'Set up your Stripe account',
                            'lang'  => $locale,
                        ], [
                            'key'   => 'activation.stripe.description',
                            'value' => 'This is original test description',
                            'lang'  => $locale,
                        ],
                    ]);
            });

        $this
            ->actingAs($user)
            ->get('/upgrade/translations')
            ->assertStatus(201);

        collect(['en', 'sk'])
            ->map(function ($locale) {
                $this->assertDatabaseHas('language_translations', [
                    'key'   => 'activation.stripe.title',
                    'value' => 'Your Stripe account is not set',
                    'lang'  => $locale,
                ]);

                $this->assertDatabaseHas('language_translations', [
                    'key'   => 'activation.stripe.description',
                    'value' => 'This is original test description',
                    'lang'  => $locale,
                ]);

                $this->assertDatabaseMissing('language_translations', [
                    'key'   => 'activation.stripe.description',
                    'value' => 'To charge your users, please set up your Stripe account credentials.',
                    'lang'  => $locale,
                ]);
            });
    }
}
