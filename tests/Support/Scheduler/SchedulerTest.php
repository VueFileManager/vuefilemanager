<?php

namespace Tests\Feature\App;

use App\Models\Share;
use App\Models\User;
use App\Models\Zip;
use App\Services\SchedulerService;
use App\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setup = resolve(SetupService::class);
        $this->scheduler = resolve(SchedulerService::class);
    }

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

        $this->scheduler->delete_expired_shared_links();

        $this->assertDatabaseMissing('shares', [
            'id' => $share->id
        ]);
    }

    /**
     * @test
     */
    public function it_delete_zips_older_than_one_day()
    {
        $this->setup->create_directories();

        $file = UploadedFile::fake()
            ->create('archive.zip', 2000, 'application/zip');

        Storage::putFileAs('zip', $file, 'EHWKcuvKzA4Gv29v-archive.zip');

        $zip = Zip::factory(Zip::class)->create([
            'basename'   => 'EHWKcuvKzA4Gv29v-archive.zip',
            'created_at' => now()->subDay(),
        ]);

        $this->scheduler->delete_old_zips();

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
        $this->setup->create_directories();

        $this->travel(-26)->hours();

        $file = UploadedFile::fake()
            ->create('fake-file.zip', 2000, 'application/zip');

        collect(['chunks'])
            ->each(function ($folder) use ($file){
                Storage::putFileAs($folder, $file, 'fake-file.zip');
            });

        $this->scheduler->delete_failed_files();

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

        $this->scheduler->delete_unverified_users();

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
