<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('stock_number');
            $table->integer('inventory_status_id')->default(1);
            $table->integer('inventory_cosmetic_id')->default(1);
            $table->longText('item_cosmetic_description')->nullable();
            $table->longText('item_description')->nullable();
            $table->float('origin_price', 20,2)->nullable();
            $table->float('selling_price', 20,2)->nullable();
            $table->float('discount_percentage', 20,2)->nullable();
            $table->json('details');
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
        Schema::dropIfExists('inventories');
    }
}
