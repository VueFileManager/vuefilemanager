<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->uuid('user_id')->index();

            $table->text('company')->nullable();
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->text('address')->nullable();
            $table->text('state')->nullable();
            $table->text('city')->nullable();
            $table->text('postal_code')->nullable();
            $table->text('country')->nullable();

            $table->string('ico')->nullable();
            $table->string('dic')->nullable();
            $table->string('ic_dph')->nullable();

            $table->text('registration_notes')->nullable();

            $table->text('bank')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift')->nullable();

            $table->string('author')->nullable();
            $table->string('stamp')->nullable();

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
        Schema::dropIfExists('invoice_profiles');
    }
}
