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
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('item_id');
            $table->string('token', 16)->unique();
            $table->enum('type', ['file', 'folder']);
            $table->enum('permission', ['visitor', 'editor'])->nullable();
            $table->boolean('is_protected')->default(0);
            $table->string('password')->nullable();
            $table->integer('expire_in')->nullable();
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
