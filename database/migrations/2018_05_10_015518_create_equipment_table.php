<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
            $table->primary('id');
            $table->string('name');
            $table->string('verify_code');//机器识别码
            $table->string('type',1);//设备类型：1、收银终端 2、员工机
            $table->string('shops_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('status',1)->default('2');//设备状态：1、已被登录 2、未被登录 3、已断电 4、挂起 5、故障
            $table->string('valid',1)->default('1');
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
        Schema::dropIfExists('equipment');
    }
}
