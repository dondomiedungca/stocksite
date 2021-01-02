<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('previous_transaction_status_id')->nullable();
            $table->unsignedBigInteger('transaction_status_id');
            $table->unsignedBigInteger('payment_status_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->unsignedBigInteger('payment_term_id')->nullable();
            $table->string('transaction_code');
            $table->float('additional_cost', 20,2)->nullable();
            $table->float('discounted_price',20,2)->nullable();
            $table->float('total_price',20,2)->nullable();
            $table->float('cash',20,2)->default(0);
            $table->float('credit',20,2)->default(0);
            $table->float('cheque',20,2)->default(0);
            $table->timestamps();

            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->foreign('previous_transaction_status_id')->references('id')->on('transaction_statuses');
            $table->foreign('transaction_status_id')->references('id')->on('transaction_statuses');
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('tax_id')->references('id')->on('taxes');
            $table->foreign('shipping_id')->references('id')->on('shipping_list');
            $table->foreign('payment_term_id')->references('id')->on('payment_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
