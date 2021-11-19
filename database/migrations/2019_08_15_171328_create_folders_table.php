<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->uuid('user_id')->index();
            $table->uuid('parent_id')->nullable();
            $table->text('name');
            $table->string('color')->nullable();
            $table->longText('emoji')->nullable();

            $table->boolean('team_folder')->default(0);

            $table->enum('author', ['user', 'member', 'visitor'])->default('user');

            $table->softDeletes();
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders');
    }
}
