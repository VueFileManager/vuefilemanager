<?php
namespace Tests\Domain\Zip;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;

class SharedZippingTest extends TestCase
{
    /**
     * @test
     */
    public function visitor_zip_shared_multiple_files()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->create();

                $sharedFolder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $folder = Folder::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $sharedFolder->id,
                    ]);

                collect([0, 1])
                    ->each(function ($index) use ($folder, $user) {
                        $file = UploadedFile::fake()
                            ->create("fake-inner-file-$index.pdf", 1200, 'application/pdf');

                        Storage::putFileAs("files/$user->id", $file, $file->name);

                        File::factory()
                            ->create([
                                'filesize'  => $file->getSize(),
                                'parent_id' => $folder->id,
                                'user_id'   => $user->id,
                                'basename'  => $file->name,
                                'name'      => "fake-file-$index.pdf",
                            ]);
                    });

                collect([0, 1])
                    ->each(function ($index) use ($sharedFolder, $user) {
                        $file = UploadedFile::fake()
                            ->create(Str::random() . "-fake-file-$index.pdf", 1000, 'application/pdf');

                        Storage::putFileAs("files/$user->id", $file, $file->name);

                        File::factory()
                            ->create([
                                'filesize'  => $file->getSize(),
                                'parent_id' => $sharedFolder->id,
                                'user_id'   => $user->id,
                                'basename'  => $file->name,
                                'name'      => "fake-file-$index.pdf",
                            ]);
                    });

                $share = Share::factory()
                    ->create([
                        'item_id'      => $sharedFolder->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                    ]);

                $files = File::all()
                    ->pluck('id')
                    ->toArray();

                // Check shared item protected by password
                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/sharing/zip/{$share->token}?items=$files[0]|file,$files[1]|file,$folder->id|folder")
                        ->assertStatus(200)
                        ->assertHeader('content-type', 'application/x-zip');
                }

                // Check public shared item
                if (! $is_protected) {
                    $this
                        ->get("/api/sharing/zip/{$share->token}?items=$files[0]|file,$files[1]|file,$folder->id|folder")
                        ->assertStatus(200)
                        ->assertHeader('content-type', 'application/x-zip');
                }
            });
    }

    /**
     * @test
     */
    public function visitor_try_zip_not_shared_file_with_already_shared_multiple_files()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->create();

                $folder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                File::factory()
                    ->create([
                        'parent_id' => $folder->id,
                        'user_id'   => $user->id,
                    ]);

                File::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                    ]);

                $files = File::all()
                    ->pluck('id')
                    ->toArray();

                // Check shared item protected by password
                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/sharing/zip/$share->token?items=$files[0]|file,$files[1]|file")
                        ->assertStatus(403);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this
                        ->get("/api/sharing/zip/$share->token?items=$files[0]|file,$files[1]|file")
                        ->assertStatus(403);
                }

                File::all()->each(fn ($file) => $file->delete());
            });
    }

    /**
     * @test
     */
    public function visitor_zip_shared_folder()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->create();

                $root = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $children = Folder::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id,
                    ]);

                collect([0, 1])
                    ->each(function ($index) use ($children, $user) {
                        $file = UploadedFile::fake()
                            ->create(Str::random() . "-fake-file-$index.pdf", 1000, 'application/pdf');

                        Storage::putFileAs("files/$user->id", $file, $file->name);

                        File::factory()
                            ->create([
                                'filesize'  => $file->getSize(),
                                'parent_id' => $children->id,
                                'user_id'   => $user->id,
                                'basename'  => $file->name,
                                'name'      => "fake-file-$index.pdf",
                            ]);
                    });

                $share = Share::factory()
                    ->create([
                        'item_id'      => $children->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/zip/$share->token?items=$children->id|folder")
                        ->assertStatus(200);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/zip/$share->token?items=$children->id|folder")
                        ->assertStatus(200);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_try_zip_not_shared_folder()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->create();

                $folder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
                    ->create([
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/sharing/zip/$share->token?items=$folder->id|folder")
                        ->assertStatus(403);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/sharing/zip/$share->token?items=$folder->id|folder")
                        ->assertStatus(403);
                }
            });
    }
}
