<?php
namespace Tests\Support\Scheduler;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Sharing\Models\Share;
use Illuminate\Http\UploadedFile;
use Support\Scheduler\Actions\DeleteFailedFilesAction;
use Support\Scheduler\Actions\DeleteUnverifiedUsersAction;
use Support\Scheduler\Actions\DeleteExpiredShareLinksAction;

class SchedulerTest extends TestCase
{
    /**
     * @test
     */
    public function it_delete_expired_shared_links()
    {
        $share = Share::factory()
            ->create([
                'expire_in'  => 24,
                'created_at' => now()->subDay(),
            ]);

        resolve(DeleteExpiredShareLinksAction::class)();

        $this->assertModelMissing($share);
    }

    /**
     * @test
     */
    public function it_delete_failed_files_older_than_one_day()
    {
        $this->travel(-26)->hours();

        $file = UploadedFile::fake()
            ->create('fake-file.zip', 2000, 'application/zip');

        collect(['chunks'])
            ->each(function ($folder) use ($file) {
                Storage::putFileAs($folder, $file, 'fake-file.zip');
            });

        resolve(DeleteFailedFilesAction::class)();

        collect(['chunks'])
            ->each(function ($folder) {
                Storage::disk('local')
                    ->assertMissing("$folder/fake-file.zip");
            });
    }

    /**
     * @test
     */
    public function it_delete_non_verified_users_after_30_days()
    {
        $expiredUser = User::factory()
            ->create([
                'email_verified_at' => null,
                'created_at'        => now()->subDays(31),
            ]);

        $nonExpiredUser = User::factory()
            ->create([
                'email_verified_at' => null,
                'created_at'        => now()->subDays(14),
            ]);

        $verifiedUser = User::factory()
            ->create([
                'email_verified_at' => now()->subDays(15),
                'created_at'        => now()->subDays(31),
            ]);

        resolve(DeleteUnverifiedUsersAction::class)();

        $this
            ->assertModelMissing($expiredUser)
            ->assertModelExists($nonExpiredUser)
            ->assertModelExists($verifiedUser);
    }
}
