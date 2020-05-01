<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserScopeToFileManagerFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_manager_files', function (Blueprint $table) {
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
        Schema::table('file_manager_files', function (Blueprint $table) {
            //
        });
    }
}
