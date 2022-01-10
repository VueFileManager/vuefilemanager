<?php

namespace Tests\Feature\App\Socialite;

use DB;
use Storage;
use App\Users\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Contracts\Factory as Socialite;


use Tests\TestCase;

class SocialiteTest extends TestCase
{
    /**
     * @test
     */
    public function it_socialite_redirect()
    {
        $response = $this->get('api/socialite/google/redirect');

        $this->assertStringContainsString('accounts.google.com/o/oauth2/auth', $response['url']);
    }

    /**
     * @test
     */
    public function it_socialite_callback()
    {   
        // Set default settings
        DB::table('settings')->insert([
            [
                'name'  => 'registration',
                'value' => 1,
            ], [
                'name'  => 'storage_default',
                'value' => 5,
            ]
        ]);

        // Create fake image
        $fakeImage = UploadedFile::fake()
            ->image('fake-avatar.jpg');

        Http::fake([
            'https://vuefilemanager.com/avatar.jpg' => Http::response($fakeImage->getContent()),
        ]);

        // Create fake user
        $socialiteUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $socialiteUser->token = 'fake_token';
        $socialiteUser->id = 'fake_id';
        $socialiteUser->name = 'Jane Doe';
        $socialiteUser->email = 'howdy@hi5ve.digital';
        $socialiteUser->avatar = 'https://vuefilemanager.com/avatar.jpg';

        // Mock user with FB provider
        $provider = $this->createMock(FacebookProvider::class);
        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        // Mock socialite
        $stub = $this->createMock(Socialite::class);

        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with mock
        $this->app->instance(Socialite::class, $stub);

        $this->getJson('/api/socialite/facebook/callback');

        $this
            ->assertDatabaseHas('users', [
                'email'          => 'howdy@hi5ve.digital',
                'oauth_provider' => 'facebook',
                'password'       => null,
            ])
            ->assertDatabaseHas('user_settings', [
                'name' => 'Jane Doe',
            ]);

        $user = User::first();

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn($size) => Storage::disk('local')
                    ->assertExists("avatars/{$size['name']}-{$user->settings->getRawOriginal('avatar')}")
            );
    }
}
