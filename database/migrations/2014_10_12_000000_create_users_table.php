<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->string('id')->unique();
            $table->primary('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('work_id')->unique();//员工工号 12位纯数字
            $table->string('identity')->nullable();//身份证号码
            $table->char('sex')->nullable();//性别  0:男 1:女
            $table->string('valid',1)->default('1');
            $table->date('hire_date')->nullable();//入职时间
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
