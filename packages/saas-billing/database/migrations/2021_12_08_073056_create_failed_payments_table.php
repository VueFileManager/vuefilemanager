<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_payments', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('user_id')->index();
            $table->decimal('amount');
            $table->string('currency');
            $table->integer('attempts')->default(0);
            $table->enum('source', ['balance', 'credit-card']);
            $table->text('note')->nullable();
            $table->longText('metadata')->nullable();
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
        Schema::dropIfExists('failed_payments');
    }
}
