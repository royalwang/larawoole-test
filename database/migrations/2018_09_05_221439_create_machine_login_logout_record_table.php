<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineLoginLogoutRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_login_logout_record', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('user_id')->nullable();
            $table->string('type',1)->nullable(); // 0:登入 , 1:登出
            $table->string('verify_code')->nullable();
            $table->string('status',1)->nullable(); // 0:正常 , 1:异常 , 2:店长操作
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_login_logout_record');
    }
}
