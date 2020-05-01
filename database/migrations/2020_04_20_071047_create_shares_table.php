<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('token', 16)->unique();
            $table->bigInteger('item_id');
            $table->enum('type', ['file', 'files', 'folder']);
            $table->enum('permission', ['visitor', 'editor'])->nullable();
            $table->boolean('protected');
            $table->string('password')->nullable();
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
        Schema::dropIfExists('shares');
    }
}
