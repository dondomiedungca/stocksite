<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_types', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id');
            $table->unsignedBigInteger('product_type_id');
            $table->bigInteger('quantity');
            $table->float('inventory_total_price', 20, 2);
            $table->bigInteger('total_received')->default(0);
            $table->bigInteger('total_items_to_receive')->default(0);
            $table->string('purchasing_description');
            $table->timestamps();

            $table->foreign('product_type_id')->references('id')->on('product_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasing_types');
    }
}
