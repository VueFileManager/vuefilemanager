<?php
namespace Tests\Domain\Maintenance;

use DB;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Support\Upgrading\Actions\UpdateSystemAction;

class AppUpgradeTest extends TestCase
{
    /**
     * @test
     */
    public function it_upgrade_default_language_translations()
    {
        $user = User::factory()
            ->create(['role' => 'admin']);

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
                            'key'   => 'type',
                            'value' => 'Type',
                            'lang'  => $locale,
                        ], [
                            'key'   => 'cancel',
                            'value' => 'Cancel',
                            'lang'  => $locale,
                        ],
                    ]);
            });

        resolve(UpdateSystemAction::class)();

        collect(['en', 'sk'])
            ->map(function ($locale) {
                $this->assertDatabaseHas('language_translations', [
                    'key'   => 'close',
                    'value' => 'Close',
                    'lang'  => $locale,
                ]);

                $this->assertDatabaseHas('language_translations', [
                    'key'   => 'create_folder',
                    'value' => 'Create folder',
                    'lang'  => $locale,
                ]);
            });
    }

    /**
     * @test
     */
    public function it_upgrade_app()
    {
        resolve(UpdateSystemAction::class)();

        $this->assertDatabaseHas('app_updates', [
            'version' => '2_0_10',
        ]);
    }
}
