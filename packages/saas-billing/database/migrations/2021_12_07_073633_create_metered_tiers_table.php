<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeteredTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metered_tiers', function (Blueprint $table) {
            $table->uuid('metered_feature_id')->index();
            $table->integer('first_unit')->default(1);
            $table->integer('last_unit')->nullable();
            $table->decimal('per_unit', 8, 4);
            $table->decimal('flat_fee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_metered_tiers');
    }
}
