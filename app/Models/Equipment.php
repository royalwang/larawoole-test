<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Speedy;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = ['name','type','shops_id','verify_code','status','user_id'];

    public function belongsToShops()
    {
        return $this -> belongsTo('App\Models\Shop','shops_id','id');
    }

    public function belongsToUser()
    {
        return $this -> belongsTo('App\Models\User','user_id','id');
    }

    public function belongsToSp()
    {
        return $this -> belongsTo('App\Models\Sp');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function($equipment){

            //商铺与设备对应关系处理
            Speedy::getModelInstance('user')->where('equipment_id', $equipment->id)->update(['equipment_id' => null]);
        });


    }
}
