<?php
namespace Tests\App\Socialite;

use Mockery;
use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Contracts\Factory as Socialite;

class SocialiteTest extends TestCase
{
    /**
     * @test
     */
    public function socialite_redirect_user()
    {
        $response = $this->get('api/socialite/google/redirect');

        $this->assertStringContainsString('accounts.google.com/o/oauth2/auth', $response->json()['data']['url']);
    }

    /**
     * @test
     */
    public function socialite_execute_provider_callback()
    {
        // Create fake image
        $fakeImage = UploadedFile::fake()
            ->image('fake-avatar.jpg');

        Http::fake([
            'https://vuefilemanager.com/avatar.jpg' => Http::response($fakeImage->getContent()),
        ]);

        // Create fake user
        $socialiteUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $socialiteUser
            ->shouldReceive('getId')
            ->andReturn('fake_id')
            ->shouldReceive('getEmail')
            ->andReturn('howdy@hi5ve.digital')
            ->shouldReceive('getName')
            ->andReturn('Jane Doe')
            ->shouldReceive('getAvatar')
            ->andReturn('https://vuefilemanager.com/avatar.jpg');

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

        $this->getJson('/socialite/facebook/callback')
            ->assertRedirect();

        $this
            ->assertDatabaseHas('users', [
                'email'          => 'howdy@hi5ve.digital',
                'oauth_provider' => 'facebook',
                'password'       => null,
            ])
            ->assertDatabaseHas('user_settings', [
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
            ]);

        $user = User::first();

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn ($size) => Storage::disk('local')
                    ->assertExists("avatars/{$size['name']}-{$user->settings->getRawOriginal('avatar')}")
            );
    }
}
