<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('status')->default(0);
            $table->boolean('sandbox')->default(0);
            $table->text('name');
            $table->text('slug');
            $table->text('logo');
            $table->text('client_id')->nullable();
            $table->text('secret')->nullable();
            $table->text('webhook')->nullable();
            $table->longText('optional')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
}
