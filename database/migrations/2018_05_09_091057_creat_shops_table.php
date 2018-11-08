<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {

            $table->string('id')->unique();
            $table->primary('id');
            $table->string('name');
            $table->string('manager_id')->default(null)->nullable();
            $table->string('area_id')->default(null)->nullable();
            $table->string('teacher_id')->default(null)->nullable();
            $table->string('address');
            $table->string('city_code')->default(null)->nullable();
            $table->timestamp('establish_time')->default(null)->nullable();
            $table->string('valid',1)->default('1');
            $table->string('discount')->nullable();
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
        Schema::dropIfExists('shops');
    }
}
