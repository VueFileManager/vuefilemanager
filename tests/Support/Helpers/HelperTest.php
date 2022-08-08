<?php
namespace Tests\Support\Helpers;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Config;

class HelperTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_split_name()
    {
        $firstTest = split_name('Jane Doe');

        $this->assertEquals('Jane', $firstTest['first_name']);
        $this->assertEquals('Doe', $firstTest['last_name']);

        $secondTest = split_name('Jane Doe Hobs');

        $this->assertEquals('Jane', $secondTest['first_name']);
        $this->assertEquals('Doe Hobs', $secondTest['last_name']);

        $thirdTest = split_name('Jane');

        $this->assertEquals('Jane', $thirdTest['first_name']);
        $this->assertEquals('', $thirdTest['last_name']);
    }

    /**
    * @test
    */
    public function it_test_get_records_count()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();
            
        Folder::factory()
            ->count(12)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => null,
            ]);

        File::factory()
            ->count(13)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => null,
            ]);

        $query = [
            'folder' => [
                'parent_id'   => null,
                'team_folder' => false,
                'user_id'     => $user->id,
                'deleted_at'  => null,
            ],
            'file' => [
                'parent_id'   => null,
                'user_id'     => $user->id,
                'deleted_at'  => null,
            ],
        ];

        Config::set('vuefilemanager.paginate.perPage', 5);

        // getRecordsCunt returned array [foldersTake, foldersSkip, filesTake, filesSkip, totalItemsCount]

        // Get folders page
        $this->assertEquals([5, 0, 0, 0, 25], getRecordsCount($query, 1));

        // Get mixed page
        $this->assertEquals([2, 10, 3, 0, 25], getRecordsCount($query, 3));

        // Get files page
        $this->assertEquals([0, 0, 5, 8, 25], getRecordsCount($query, 5));

        // Get all pages
        $this->assertEquals([12, 0, 13, 0, 25], getRecordsCount($query));
    }
}
