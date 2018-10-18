<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shops_id');
            $table->string('equipment_id');
            $table->string('sp_user');
            $table->string('bz_user');
            $table->string('status',1)->default('0');//0：未审批 1：通过 2：拒绝
            $table->string('valid',1)->default(1);
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
        Schema::dropIfExists('sp');
    }
}
