<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopMachineConfigRelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_machine_config_rel', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('machine_sn')->nullable();
            $table->string('config_id')->nullable();
            $table->char('valid','1')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_machine_config_rel');
    }
}
