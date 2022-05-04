<?php
namespace Tests\Domain\RemoteUpload;

use Event;
use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Domain\RemoteUpload\Events\RemoteFileCreatedEvent;

class RemoteUploadTest extends TestCase
{
    /**
     * @test
     */
    public function it_remotely_upload_new_file()
    {
        Event::fake([
            RemoteFileCreatedEvent::class,
        ]);

        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $fakeFile = UploadedFile::fake()
            ->create('top-secret-document.pdf', 12000000, 'application/pdf');

        Http::fake([
            'https://fake.com/top-secret-document.pdf'     => Http::response($fakeFile->getContent()),
            'https://fake.com/another-secret-document.pdf' => Http::response($fakeFile->getContent()),
        ]);

        $this
            ->actingAs($user)
            ->postJson('/api/upload/remote', [
                'urls'      => [
                    'https://fake.com/top-secret-document.pdf',
                    'https://fake.com/another-secret-document.pdf',
                ],
                'parent_id' => $folder->id,
            ])->assertStatus(201);

        $this
            ->assertDatabaseHas('files', [
                'user_id'   => $user->id,
                'name'      => 'top-secret-document',
                'parent_id' => $folder->id,
            ])
            ->assertDatabaseHas('files', [
                'name'      => 'another-secret-document',
            ]);

        File::all()
            ->each(function ($file) {
                Event::assertDispatched(fn (RemoteFileCreatedEvent $event) => $event->payload['file']->id === $file->id);

                Storage::assertExists("files/$file->user_id/$file->basename");
            });
    }
}
