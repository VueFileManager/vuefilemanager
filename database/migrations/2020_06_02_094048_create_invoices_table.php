<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token');
            $table->text('order');
            $table->text('provider');
            $table->text('user_id');
            $table->text('plan_id');
            $table->longText('seller');
            $table->longText('client');
            $table->longText('bag');
            $table->longText('notes')->nullable();
            $table->text('total');
            $table->text('currency');
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
        Schema::dropIfExists('invoices');
    }
}
