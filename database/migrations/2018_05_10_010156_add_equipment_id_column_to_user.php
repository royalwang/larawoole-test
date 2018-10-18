<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEquipmentIdColumnToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table(config('speedy.table.user'), function($table){
            $table->string('equipment_id')->nullable();
            $table->index('equipment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('speedy.table.user'), function($table){
            $table->dropColumn('equipment_id');
        });
    }
}
