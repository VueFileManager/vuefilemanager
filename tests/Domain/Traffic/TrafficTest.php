<?php
namespace Tests\Domain\Traffic;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Domain\Traffic\Models\Traffic;
use Illuminate\Database\Eloquent\Model;

class TrafficTest extends TestCase
{
    public UploadedFile $file;
    public Model $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->file = UploadedFile::fake()
            ->image('fake-file.jpg', 1200);

        $this->user = User::factory()
            ->hasSettings()
            ->create();
    }

    /**
     * @test
     */
    public function it_record_user_file_upload()
    {
        $this
            ->actingAs($this->user)
            ->postJson('/api/upload', [
                'filename'  => $this->file->name,
                'file'      => $this->file,
                'parent_id' => null,
                'path'      => '/' . $this->file->name,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this->assertDatabaseHas('traffic', [
            'user_id' => $this->user->id,
            'upload'  => 991,
        ]);
    }

    /**
     * @test
     */
    public function it_record_current_day_and_go_to_the_next_day_and_record_it()
    {
        $this
            ->actingAs($this->user)
            ->postJson('/api/upload', [
                'filename'  => $this->file->name,
                'file'      => $this->file,
                'parent_id' => null,
                'path'      => '/' . $this->file->name,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this->assertDatabaseHas('traffic', [
            'user_id'    => $this->user->id,
            'upload'     => 991,
            'created_at' => now(),
        ]);

        $this->travel(1)->day();

        $secondFile = UploadedFile::fake()
            ->image('fake-file-2.jpg', 1200);

        $this
            ->actingAs($this->user)
            ->postJson('/api/upload', [
                'filename'  => $secondFile->name,
                'file'      => $secondFile,
                'parent_id' => null,
                'path'      => '/' . $secondFile->name,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this->assertDatabaseHas('traffic', [
            'user_id'    => $this->user->id,
            'upload'     => 991,
            'created_at' => now(),
        ]);

        $this->assertDatabaseCount('traffic', 2);
    }

    /**
     * @test
     */
    public function editor_upload_file_into_shared_folder()
    {
        $folder = Folder::factory()
            ->create([
                'user_id' => $this->user->id,
                'author'  => 'user',
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $this->user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        // Check public shared item
        $this->postJson("/api/editor/upload/$share->token", [
            'filename'  => $this->file->name,
            'file'      => $this->file,
            'parent_id' => $folder->id,
            'path'      => '/' . $this->file->name,
            'is_last'   => 'true',
        ])->assertStatus(201);

        $this->assertDatabaseHas('traffic', [
            'user_id' => $this->user->id,
            'upload'  => 991,
        ]);
    }

    /**
     * @test
     */
    public function user_download_file()
    {
        $document = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/{$this->user->id}", $document, $document->name);

        File::factory()
            ->create([
                'filesize' => $document->getSize(),
                'user_id'  => $this->user->id,
                'basename' => $document->name,
                'name'     => $document->name,
            ]);

        $this
            ->actingAs($this->user)
            ->get("/file/$document->name")
            ->assertStatus(200);

        $this->assertDatabaseHas('traffic', [
            'user_id'  => $this->user->id,
            'download' => $document->getSize(),
        ]);
    }

    /**
     * @test
     */
    public function visitor_download_file()
    {
        $document = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/{$this->user->id}", $document, $document->name);

        $file = File::factory()
            ->create([
                'filesize' => $document->getSize(),
                'user_id'  => $this->user->id,
                'basename' => $document->name,
                'name'     => $document->name,
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $this->user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        $this->get("/file/$document->name/shared/$share->token")
            ->assertStatus(200)
            ->assertDownload($document->name);

        $this->assertDatabaseHas('traffic', [
            'user_id'  => $this->user->id,
            'download' => $document->getSize(),
        ]);
    }

    /**
     * @test
     */
    public function it_get_user_traffic_test()
    {
        foreach (range(0, 30) as $day) {
            Traffic::factory()
                ->create([
                    'user_id'    => $this->user->id,
                    'upload'     => 10000 * $day,
                    'download'   => 1000000 * $day,
                    'created_at' => now()->subDays($day),
                    'updated_at' => now()->subDays($day),
                ]);
        }

        $this->actingAs($this->user)
            ->get('/api/user/storage')
            ->assertOk()
            ->assertJsonFragment([
                'download' => '465.00MB',
                'upload'   => '4.65MB',
            ]);
    }
}
