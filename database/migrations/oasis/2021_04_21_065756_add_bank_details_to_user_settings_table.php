<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankDetailsToUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->text('registration_notes')->nullable();

            $table->text('dic')->nullable();
            $table->text('ic_dph')->nullable();

            $table->text('bank_name')->nullable();
            $table->text('iban')->nullable();
            $table->text('swift')->nullable();
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
            $table->dropColumn(['bank_name','iban','swift']);
        });
    }
}
