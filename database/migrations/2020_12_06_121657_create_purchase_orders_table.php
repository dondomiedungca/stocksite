<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trasaction_id');
            $table->unsignedBigInteger('user_id');
            $table->string('receiver_first_name');
            $table->string('receiver_middle_name')->nullable();
            $table->string('receiver_last_name');
            $table->integer('address_id')->nullable();
            $table->string('receiver_phone_number')->nullable();
            $table->string('receiver_email')->nullable();
            $table->timestamps();

            $table->foreign('trasaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
