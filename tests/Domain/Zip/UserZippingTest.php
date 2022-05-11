<?php
namespace Tests\Domain\Zip;

use Tests\TestCase;
use App\Users\Models\User;
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
        $user = User::factory()
            ->hasSettings()
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder) {
                $file = UploadedFile::fake()
                    ->create("fake-inner-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload/chunks', [
                    'name'            => $file->name,
                    'extension'       => 'pdf',
                    'chunk'           => $file,
                    'parent_id'       => $folder->id,
                    'is_last_chunk'   => 1,
                ])->assertStatus(201);
            });

        collect([0, 1])
            ->each(function ($index) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload/chunks', [
                    'name'            => $file->name,
                    'extension'       => 'pdf',
                    'chunk'           => $file,
                    'is_last_chunk'   => 1,
                ])->assertStatus(201);
            });

        $files = File::all()
            ->where('parent_id', null)
            ->pluck('id')
            ->toArray();

        $this
            ->getJson("/api/zip?items=$files[0]|file,$files[1]|file,$folder->id|folder")
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/x-zip');
    }

    /**
     * @test
     */
    public function it_zip_folder_with_content_within_and_download()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload/chunks', [
                    'name'            => $file->name,
                    'extension'       => 'pdf',
                    'chunk'           => $file,
                    'parent_id'       => $folder->id,
                    'is_last_chunk'   => 1,
                ])->assertStatus(201);
            });

        $this->getJson("/api/zip?items=$folder->id|folder")
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/x-zip');
    }
}
