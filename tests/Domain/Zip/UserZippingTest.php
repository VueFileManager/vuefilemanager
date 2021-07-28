<?php
namespace Tests\Domain\Zip;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Zip\Models\Zip;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;

class UserZippingTest extends TestCase
{
    /**
     * @test
     */
    public function it_zip_multiple_files_and_download_it()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        collect([0, 1])
            ->each(function ($index) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload', [
                    'filename'  => $file->name,
                    'file'      => $file,
                    'folder_id' => null,
                    'is_last'   => 'true',
                ])->assertStatus(201);
            });

        $file_ids = File::all()->pluck('id');

        $this->postJson('/api/zip/files', [
            'items' => $file_ids,
        ])->assertStatus(201);

        $this->assertDatabaseHas('zips', [
            'user_id' => $user->id,
        ]);

        Storage::disk('local')
            ->assertExists(
                'zip/' . Zip::first()->basename
            );
    }

    /**
     * @test
     */
    public function it_zip_folder_with_content_within_and_download()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload', [
                    'filename'  => $file->name,
                    'file'      => $file,
                    'folder_id' => $folder->id,
                    'is_last'   => 'true',
                ])->assertStatus(201);
            });

        $this->getJson("/api/zip/folder/$folder->id")
            ->assertStatus(201);

        $this->assertDatabaseHas('zips', [
            'user_id' => $user->id,
        ]);

        Storage::disk('local')
            ->assertExists(
                'zip/' . Zip::first()->basename
            );
    }
}
