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
            $table->uuid('id')->primary()->index();
            $table->uuid('user_id')->index();
            $table->uuid('folder_id')->nullable();

            $table->text('thumbnail')->nullable();
            $table->text('name');
            $table->string('basename')->index();

            $table->text('mimetype')->nullable();
            $table->text('filesize');

            $table->text('type')->nullable();
            $table->longText('metadata')->nullable();

            $table->enum('author', ['user', 'member', 'visitor'])->default('user');
            $table->uuid('author_id')->nullable();

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
