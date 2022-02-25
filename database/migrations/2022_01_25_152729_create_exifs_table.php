<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exifs', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->uuid('file_id')->index();

            $table->timestamp('date_time_original')->nullable();
            $table->string('artist')->nullable();
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->string('x_resolution')->nullable();
            $table->string('y_resolution')->nullable();
            $table->integer('color_space')->nullable();
            $table->string('camera')->nullable();
            $table->string('model')->nullable();
            $table->string('aperture_value')->nullable();
            $table->string('exposure_time')->nullable();
            $table->string('focal_length')->nullable();
            $table->integer('iso')->nullable();
            $table->string('aperture_f_number')->nullable();
            $table->string('ccd_width')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude_ref')->nullable();
            $table->string('latitude_ref')->nullable();

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
        Schema::dropIfExists('exifs');
    }
}
