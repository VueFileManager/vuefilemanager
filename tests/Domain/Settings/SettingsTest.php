<?php
namespace Tests\Domain\Settings;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Domain\Settings\Models\Setting;
use Domain\Settings\Actions\SeedDefaultSettingsAction;

class SettingsTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_page_settings()
    {
        Setting::create([
            'name'  => 'get_started_title',
            'value' => 'Hello World!',
        ]);

        Setting::create([
            'name'  => 'pricing_description',
            'value' => 'Give me a money!',
        ]);

        $this->getJson('/api/settings?column=get_started_title|pricing_description')
            ->assertStatus(200)
            ->assertExactJson([
                'get_started_title'   => 'Hello World!',
                'pricing_description' => 'Give me a money!',
            ]);
    }

    /**
     * @test
     */
    public function it_get_admin_settings()
    {
        resolve(SeedDefaultSettingsAction::class)('Extended');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/settings?column=section_features|section_feature_boxes')
            ->assertStatus(200)
            ->assertJsonFragment([
                'section_features'      => '1',
                'section_feature_boxes' => '1',
            ]);
    }

    /**
     * @test
     */
    public function it_try_get_secured_settings_via_public_api()
    {
        Setting::create([
            'name'  => 'purchase_code',
            'value' => '15a53561-d387-4e0a-8de1-5d1bff34c1ed',
        ]);

        $this->getJson('/api/settings?column=purchase_code')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_update_settings()
    {
        resolve(SeedDefaultSettingsAction::class)('Extended');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson('/api/admin/settings', [
                'name'  => 'header_title',
                'value' => 'New Header Title',
            ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'value' => 'New Header Title',
        ]);
    }

    /**
     * @test
     */
    public function it_update_settings_image()
    {
        Setting::forceCreate([
            'name'  => 'app_logo',
            'value' => null,
        ]);

        $logo = UploadedFile::fake()
            ->image('fake-image.jpg');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson('/api/admin/settings', [
                'name'     => 'app_logo',
                'app_logo' => $logo,
            ])->assertStatus(204);

        $this->assertDatabaseMissing('settings', [
            'app_logo' => null,
        ]);

        Storage::assertExists(
            get_setting('app_logo')
        );
    }

    /**
     * @test
     */
    public function it_flush_cache()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/settings/flush-cache')
            ->assertStatus(204);
    }

    /**
     * @test
     */
    public function it_set_stripe()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/settings/stripe', [
                'currency'      => 'EUR',
                'key'           => '123456789',
                'secret'        => '123456789',
                'webhookSecret' => '123456789',
            ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'name'  => 'stripe_currency',
            'value' => 'EUR',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'payments_configured',
            'value' => 1,
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'payments_active',
            'value' => 1,
        ]);
    }

    /**
     * @test
     */
    public function it_set_email()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/settings/email', [
                'driver'     => 'smtp',
                'host'       => 'smtp.email.com',
                'port'       => 25,
                'username'   => 'john@doe.com',
                'password'   => 'secret',
                'encryption' => 'tls',
            ])->assertStatus(204);
    }
}
