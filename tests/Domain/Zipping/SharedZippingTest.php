<?php


namespace Tests\Domain\Zipping;


use Domain\Settings\Models\File;
use Domain\Settings\Models\Folder;
use Domain\Settings\Models\Share;
use Domain\Settings\Models\User;
use Domain\Settings\Models\Zip;
use Domain\SetupWizard\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

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

                $user = User::factory(User::class)
                    ->create();

                $folder = Folder::factory(Folder::class)
                    ->create([
                        'user_id' => $user->id
                    ]);

                collect([0, 1])
                    ->each(function ($index) use ($folder, $user) {

                        $file = UploadedFile::fake()
                            ->create(Str::random() . "-fake-file-$index.pdf", 1000, 'application/pdf');

                        Storage::putFileAs("files/$user->id", $file, $file->name);

                        File::factory(File::class)
                            ->create([
                                'filesize'  => $file->getSize(),
                                'folder_id' => $folder->id,
                                'user_id'   => $user->id,
                                'basename'  => $file->name,
                                'name'      => "fake-file-$index.pdf",
                            ]);
                    });

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $folder->id,
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
                        ->post("/api/zip/files/$share->token", [
                            'items' => File::all()->pluck('id')
                        ])->assertStatus(201);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->postJson("/api/zip/files/$share->token", [
                        'items' => File::all()->pluck('id')
                    ])->assertStatus(201);
                }

                $this->assertDatabaseHas('zips', [
                    'user_id'      => $user->id,
                    'shared_token' => $share->token,
                ]);

                Storage::assertExists("zip/" . Zip::first()->basename);
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

                $user = User::factory(User::class)
                    ->create();

                $folder = Folder::factory(Folder::class)
                    ->create([
                        'user_id' => $user->id
                    ]);

                File::factory(File::class)
                    ->create([
                        'folder_id' => $folder->id,
                        'user_id'   => $user->id,
                    ]);

                File::factory(File::class)
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $folder->id,
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
                        ->post("/api/zip/files/$share->token", [
                            'items' => File::all()->pluck('id')
                        ])->assertStatus(403);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->postJson("/api/zip/files/$share->token", [
                        'items' => File::all()->pluck('id')
                    ])->assertStatus(403);
                }
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

                $user = User::factory(User::class)
                    ->create();

                $root = Folder::factory(Folder::class)
                    ->create([
                        'user_id' => $user->id
                    ]);

                $children = Folder::factory(Folder::class)
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id
                    ]);

                collect([0, 1])
                    ->each(function ($index) use ($children, $user) {

                        $file = UploadedFile::fake()
                            ->create(Str::random() . "-fake-file-$index.pdf", 1000, 'application/pdf');

                        Storage::putFileAs("files/$user->id", $file, $file->name);

                        File::factory(File::class)
                            ->create([
                                'filesize'  => $file->getSize(),
                                'folder_id' => $children->id,
                                'user_id'   => $user->id,
                                'basename'  => $file->name,
                                'name'      => "fake-file-$index.pdf",
                            ]);
                    });

                $share = Share::factory(Share::class)
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
                        ->get("/api/zip/folder/$children->id/$share->token")
                        ->assertStatus(201);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->getJson("/api/zip/folder/$children->id/$share->token")
                        ->assertStatus(201);
                }

                $this->assertDatabaseHas('zips', [
                    'user_id'      => $user->id,
                    'shared_token' => $share->token,
                ]);

                Zip::all()
                    ->each(function ($zip) {
                        Storage::assertExists("zip/$zip->basename");
                    });
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

                $user = User::factory(User::class)
                    ->create();

                $folder = Folder::factory(Folder::class)
                    ->create([
                        'user_id' => $user->id
                    ]);

                $share = Share::factory(Share::class)
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
                        ->get("/api/zip/folder/$folder->id/$share->token")
                        ->assertStatus(403);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->getJson("/api/zip/folder/$folder->id/$share->token")
                        ->assertStatus(403);
                }
            });
    }
}