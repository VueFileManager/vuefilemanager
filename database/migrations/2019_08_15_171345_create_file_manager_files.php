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
        Schema::create('file_manager_files', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->integer('unique_id');
            $table->integer('folder_id')->default(0);

            $table->text('thumbnail')->nullable();
            $table->text('name')->nullable();
            $table->text('basename')->nullable();

            $table->text('mimetype')->nullable();
            $table->text('filesize')->nullable();

            $table->enum('type', ['image', 'file'])->nullable();

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
