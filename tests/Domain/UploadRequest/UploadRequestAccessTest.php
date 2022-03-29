<?php
namespace Tests\Domain\UploadRequest;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Illuminate\Http\UploadedFile;
use Domain\UploadRequest\Models\UploadRequest;

class UploadRequestAccessTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_file_from_upload_request_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'active',
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        File::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'basename'  => $file->name,
                'user_id'   => $user->id,
                'name'      => $file->name,
            ]);

        $this
            ->get("/file/$file->name/upload-request/$uploadRequest->id")
            ->assertDownload($file->name);
    }

    /**
     * @test
     */
    public function it_get_thumbnail_from_upload_request_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'active',
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $thumbnail = UploadedFile::fake()
            ->image('fake-thumbnail.jpg');

        Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

        File::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'basename'  => 'fake-thumbnail.jpg',
                'user_id'   => $user->id,
            ]);

        $this
            ->get("/thumbnail/xs-$thumbnail->name/upload-request/$uploadRequest->id")
            ->assertDownload("xs-$thumbnail->name");
    }

    /**
     * @test
     */
    public function it_try_get_file_from_expired_upload_request_folder()
    {
        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'expired',
                'created_at' => now(),
            ]);

        $this
            ->get("/file/fake-file.pdf/upload-request/$uploadRequest->id")
            ->assertStatus(410);
    }

    /**
     * @test
     */
    public function it_try_get_thumbnail_from_expired_upload_request_folder()
    {
        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'filled',
                'created_at' => now(),
            ]);

        $this
            ->get("/thumbnail/xs-fake-thumbnail.jpeg/upload-request/$uploadRequest->id")
            ->assertStatus(410);
    }
}
