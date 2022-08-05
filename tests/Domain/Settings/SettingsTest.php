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
        resolve(SeedDefaultSettingsAction::class)();

        $admin = User::factory()
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
        resolve(SeedDefaultSettingsAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson('/api/admin/settings', [
                'name'  => 'header_title',
                'value' => 'New Header Title',
            ])->assertStatus(200);

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

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson('/api/admin/settings', [
                'name'     => 'app_logo',
                'app_logo' => $logo,
            ])->assertStatus(200);

        $this->assertDatabaseMissing('settings', [
            'app_logo' => null,
        ]);

        Storage::assertExists(
            get_settings('app_logo')
        );
    }

    /**
     * @test
     */
    public function it_flush_cache()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/settings/flush-cache')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_store_payment_service_credentials()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->post('/api/admin/settings/payment-service', [
                'service' => 'stripe',
                'key'     => '123456789',
                'secret'  => '123456789',
            ])->assertStatus(200);

        $this->assertDatabaseHas('settings', [
            'name'  => 'allowed_stripe',
            'value' => '1',
        ]);
    }

    /**
     * @test
     */
    public function it_store_social_service_credentials()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->post('/api/admin/settings/social-service', [
                'client_id'     => '123456789',
                'client_secret' => '123456789',
                'service'       => 'facebook',
            ])->assertStatus(200);

        $this->assertDatabaseHas('settings', [
            'name'  => 'allowed_facebook',
            'value' => 1,
        ]);
    }

    /**
     * @test
     */
    public function it_set_email()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/settings/email', [
                'mailDriver'      => 'smtp',
                'smtp.host'       => 'smtp.email.com',
                'smtp.port'       => 25,
                'smtp.username'   => 'john@doe.com',
                'smtp.password'   => 'secret',
                'smtp.encryption' => 'tls',
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_set_storage()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/settings/storage', [
                'storage' => [
                    'driver'   => 's3',
                    'key'      => '123456',
                    'secret'   => '123456',
                    'region'   => 'frankfurt',
                    'bucket'   => 'cloud',
                    'endpoint' => 'https://cloud.frankfurt.storage.com',
                ],
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_set_broadcast()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/settings/broadcast', [
                'driver'  => 'pusher',
                'id'      => '123',
                'key'     => '123456',
                'secret'  => 'mOoiofnssddf',
                'cluster' => 'eu',
                'port'    => null,
                'host'    => null,
            ])->assertStatus(200);
    }
}
