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
            $table->uuid('id')->primary()->index();
            $table->uuid('user_id')->index();
            $table->uuid('client_id')->nullable()->index();

            $table->enum('invoice_type', [
                'regular-invoice', 'advance-invoice'
            ]);

            $table->text('invoice_number')->nullable();
            $table->text('variable_number')->nullable();

            $table->longText('client');
            $table->longText('user');
            $table->longText('items');

            $table->dateTime('delivery_at')->nullable();
            $table->dateTime('due_at')->nullable();

            $table->enum('discount_type', [
                'percent', 'value'
            ])->nullable();
            $table->integer('discount_rate')->nullable();

            $table->text('currency');

            $table->string('total_discount')->nullable();
            $table->string('total_net')->nullable();
            $table->string('total_tax')->nullable();

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
