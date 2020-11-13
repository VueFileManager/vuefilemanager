<?php

namespace Tests\Unit;

use App\User;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\FileManagerFile;
use Laravel\Passport\Passport;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BulkTest extends TestCase
{
    // use DatabaseMigrations;
    use RefreshDatabase;


    /**
     * @test 
     */

     public function bulk_delete_user ()
     {
         $this->withoutExceptionHandling();

         $data ='{
          "data": [
            {
                "force_delete": false,
                "type": "file",
                "unique_id": 0
            },
            {
                "force_delete": false,
                "type": "file",
                "unique_id": 1
            },
            {
                "force_delete": false,
                "type": "file",
                "unique_id": 2
            }
          ]
          }';
 
        $user = factory(User::class)->create();

       factory(FileManagerFile::class, 3)->create();

         $this->assertDatabaseCount('file_manager_files', 3);

         $this->actingAs($user)->withoutMiddleware()->json('POST','/api/remove-item', json_decode($data , true))
         ->assertStatus(201);

        //  $this->assertDatabaseCount('file_manager_files', 3);

     }
}
