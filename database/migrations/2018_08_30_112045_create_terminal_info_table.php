<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerminalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal_info', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('terminal_sn');
            $table->string('terminal_key');
            $table->char('valid')->default('1');
            $table->string('merchant_sn');
            $table->string('terminal_name');
            $table->string('store_sn');
            $table->string('store_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminal_info');
    }
}
