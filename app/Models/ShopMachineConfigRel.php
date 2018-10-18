<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopMachineConfigRel extends Model
{
    protected $table = 'shop_machine_config_rel';

    protected $fillable = ['machine_sn','config_id','valid'];
}
