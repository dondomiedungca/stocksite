<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_uploaded', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->integer('total_content')->default(0);
            $table->integer('new')->default(0);
            $table->integer('exist')->default(0);
            $table->integer('valid')->default(0);
            $table->integer('invalid')->default(0);
            $table->integer('user_id');
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
        Schema::dropIfExists('file_uploaded');
    }
}
