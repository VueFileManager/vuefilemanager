<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFolderIconOptionsToFileManagerFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_manager_folders', function (Blueprint $table) {
            $table->string('icon_color')->after('user_scope')->nullable();
            $table->string('icon_emoji')->after('icon_color')->nullable();
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
