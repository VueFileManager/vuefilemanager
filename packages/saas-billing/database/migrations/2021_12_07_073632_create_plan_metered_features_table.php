<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanMeteredFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_metered_features', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('plan_id')->index();
            $table->text('key');
            $table->enum('aggregate_strategy', ['sum_of_usage', 'maximum_usage']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_metered_features');
    }
}
