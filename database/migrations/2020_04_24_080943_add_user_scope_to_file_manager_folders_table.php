<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserScopeToFileManagerFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_manager_folders', function (Blueprint $table) {
            $table->string('user_scope')->after('type')->default('master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_manager_folders', function (Blueprint $table) {
            //
        });
    }
}
