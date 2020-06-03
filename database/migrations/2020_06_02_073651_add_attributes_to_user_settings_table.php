<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesToUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->text('billing_name')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('billing_state')->nullable();
            $table->text('billing_city')->nullable();
            $table->text('billing_postal_code')->nullable();
            $table->text('billing_country')->nullable();
            $table->text('billing_phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('billing_name');
            $table->dropColumn('billing_address');
            $table->dropColumn('billing_state');
            $table->dropColumn('billing_city');
            $table->dropColumn('billing_postal_code');
            $table->dropColumn('billing_country');
            $table->dropColumn('billing_phone_number');
        });
    }
}
