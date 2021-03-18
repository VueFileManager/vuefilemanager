<?php

namespace Tests\Feature\Share;

use App\Models\File;
use App\Models\Share;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PrivateShareContentAccessTest extends TestCase
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
    public function it_authenticate_protected_file_with_correct_password()
    {
        $file = File::factory(File::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => true,
                'password'     => \Hash::make('secret'),
            ]);

        $this->postJson("/api/browse/authenticate/$share->token", [
            'password' => 'secret'
        ])
            ->assertStatus(200)
            ->assertCookie('share_session', json_encode([
                'token'         => $share->token,
                'authenticated' => true,
            ]), false);
    }

    /**
     * @test
     */
    public function it_authenticate_protected_file_with_incorrect_password()
    {
        $file = File::factory(File::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => true,
                'password'     => \Hash::make('secret'),
            ]);

        $this->postJson("/api/browse/authenticate/$share->token", [
            'password' => 'bad-password'
        ])
            ->assertStatus(401)
            ->assertCookieMissing('share_session');
    }
}
