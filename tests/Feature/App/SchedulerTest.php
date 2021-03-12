<?php

namespace Tests\Feature\App;

use App\Models\File;
use App\Models\Share;
use App\Models\Zip;
use App\Services\SchedulerService;
use App\Services\SetupService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    use DatabaseMigrations;

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
        $this->scheduler = app()->make(SchedulerService::class);
    }

    /**
     * @test
     */
    public function it_delete_expired_shared_links()
    {
        $share = Share::factory(Share::class)
            ->create([
                'expire_in' => 24,
                'created_at' => Carbon::now()->subDay(),
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
        Storage::fake('local');

        $this->setup->create_directories();

        $file = UploadedFile::fake()
            ->create('archive.zip', 2000, 'application/zip');

        Storage::putFileAs('zip', $file, 'EHWKcuvKzA4Gv29v-archive.zip');

        $zip = Zip::factory(Zip::class)->create([
            'basename'   => 'EHWKcuvKzA4Gv29v-archive.zip',
            'created_at' => Carbon::now()->subDay(),
        ]);

        $this->scheduler->delete_old_zips();

        $this->assertDatabaseMissing('zips', [
            'id' => $zip->id
        ]);

        Storage::disk('local')
            ->assertMissing('zip/EHWKcuvKzA4Gv29v-archive.zip');
    }
}
