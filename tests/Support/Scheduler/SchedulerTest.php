<?php

namespace Tests\Support\Scheduler;

use Domain\Settings\Models\Share;
use Domain\Settings\Models\User;
use Domain\Settings\Models\Zip;
use Domain\SetupWizard\Services\SchedulerService;
use Domain\SetupWizard\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    /**
     * @test
     */
    public function it_delete_expired_shared_links()
    {
        $share = Share::factory(Share::class)
            ->create([
                'expire_in' => 24,
                'created_at' => now()->subDay(),
            ]);

        resolve(SchedulerService::class)
            ->delete_expired_shared_links();

        $this->assertDatabaseMissing('shares', [
            'id' => $share->id
        ]);
    }

    /**
     * @test
     */
    public function it_delete_zips_older_than_one_day()
    {
        $file = UploadedFile::fake()
            ->create('archive.zip', 2000, 'application/zip');

        Storage::putFileAs('zip', $file, 'EHWKcuvKzA4Gv29v-archive.zip');

        $zip = Zip::factory(Zip::class)->create([
            'basename'   => 'EHWKcuvKzA4Gv29v-archive.zip',
            'created_at' => now()->subDay(),
        ]);

        resolve(SchedulerService::class)
            ->delete_old_zips();

        $this->assertDatabaseMissing('zips', [
            'id' => $zip->id
        ]);

        Storage::disk('local')
            ->assertMissing('zip/EHWKcuvKzA4Gv29v-archive.zip');
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
            ->each(function ($folder) use ($file){
                Storage::putFileAs($folder, $file, 'fake-file.zip');
            });

        resolve(SchedulerService::class)
            ->delete_failed_files();

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
        $expiredUser = User::factory(User::class)
            ->create([
                'email_verified_at' => null,
                'created_at'        => now()->subDays(31)
            ]);

        $nonExpiredUser = User::factory(User::class)
            ->create([
                'email_verified_at' => null,
                'created_at'        => now()->subDays(14)
            ]);

        $verifiedUser = User::factory(User::class)
            ->create([
                'email_verified_at' => now()->subDays(15),
                'created_at'        => now()->subDays(31)
            ]);

        resolve(SchedulerService::class)
            ->delete_unverified_users();

        $this->assertDatabaseMissing('users', [
            'id' => $expiredUser->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $nonExpiredUser->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $verifiedUser->id,
        ]);
    }
}
