<?php
namespace Tests\Support\Demo;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Sharing\Models\Share;
use Support\Demo\Actions\ClearHowdyDemoDataAction;

class DemoTest extends TestCase
{
    /**
     * @test
     */
    public function it_delete_howdy_shared_links()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $howdy = User::factory()
            ->hasSettings()
            ->create([
                'email' => 'howdy@hi5ve.digital',
            ]);

        Share::factory()
            ->create(['user_id' => $user->id]);

        Share::factory()
            ->create(['user_id' => $howdy->id]);

        resolve(ClearHowdyDemoDataAction::class)();

        $this->assertDatabaseHas('shares', [
            'user_id' => $user->id,
        ])->assertDatabaseMissing('shares', [
            'user_id' => $howdy->id,
        ]);
    }
}
