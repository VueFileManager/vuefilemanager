<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileManagerFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('folder_id');

            $table->text('thumbnail')->nullable();
            $table->text('name');
            $table->text('basename');

            $table->text('mimetype')->nullable();
            $table->text('filesize');

            $table->text('type')->nullable();
            $table->longText('metadata')->nullable();
            $table->enum('user_scope', ['master', 'editor', 'visitor'])->default('master');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_manager_files');
    }
}
