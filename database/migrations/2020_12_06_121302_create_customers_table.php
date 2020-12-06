<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_type_id');
            $table->integer('customer_company_info_id')->nullable();
            $table->integer('customer_address_id')->nullable();
            $table->string('customer_representative_first_name');
            $table->string('customer_representative_middle_name')->nullable();
            $table->string('customer_representative_last_name');
            $table->float('customer_credit', 20, 2);
            $table->string('customer_phone_number')->nullable();
            $table->string('customer_email')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
