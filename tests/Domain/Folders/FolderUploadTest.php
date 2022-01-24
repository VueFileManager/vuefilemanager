<?php

namespace Tests\Domain\Folders;

use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FolderUploadTest extends TestCase
{
    /**
     * @test
     */
    public function check_folder_tree_structure_creation_after_folder_upload()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = UploadedFile::fake()
            ->create('fake-file_1.pdf', 120000, 'application/pdf');

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'filename'  => $file->name,
                'file'      => $file,
                'path'      => "/level_1/level_2/level_3/$file->name",
                'parent_id' => null,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $file = File::first();

        $level_1 = Folder::where('name', 'level_1')->first();
        $level_2 = Folder::where('name', 'level_2')->first();
        $level_3 = Folder::where('name', 'level_3')->first();

        $this->assertEquals(null, $level_1->parent_id);
        $this->assertEquals($level_2->parent_id, $level_1->id);
        $this->assertEquals($level_3->parent_id, $level_2->id);

        $this->assertEquals($level_3->id, $file->parent_id);
    }


    /**
     * @test
     */
    public function check_children_tree_structure_creation_after_folder_upload()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $brother = UploadedFile::fake()
            ->create('brother.pdf', 120000, 'application/pdf');

        $sister = UploadedFile::fake()
            ->create('sister.pdf', 120000, 'application/pdf');

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'filename'  => $brother->name,
                'file'      => $brother,
                'path'      => "/Folder/Brother/$brother->name",
                'parent_id' => null,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'filename'  => $sister->name,
                'file'      => $sister,
                'path'      => "/Folder/Sister/$sister->name",
                'parent_id' => null,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $brotherFile = File::where('name', 'brother.pdf')->first();
        $sisterFile = File::where('name', 'sister.pdf')->first();

        $brotherFolder = Folder::where('name', 'Brother')->first();
        $sisterFolder = Folder::where('name', 'Sister')->first();

        // Check correctness of siblings file appended to the folder
        $this->assertEquals($brotherFolder->id, $brotherFile->parent_id);
        $this->assertEquals($sisterFolder->id, $sisterFile->parent_id);

        $home = Folder::where('name', 'Folder')->first();

        // Check correctness of siblings folders appended to the home folder
        $this->assertEquals($home->id, $brotherFolder->parent_id);
        $this->assertEquals($home->id, $sisterFolder->parent_id);
    }
}