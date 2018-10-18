<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Speedy;

class area extends Model
{
    protected $table = 'area';

    public $incrementing=false;

    protected $fillable = ['name','user_id'];

    public function hasManyShops()
    {
        return $this->hasMany('App\Models\Shop','area_id','id');
    }

    public function hasOneManager()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function($area){

            //区域与店铺对应关系处理
            Speedy::getModelInstance('shop')->where('area_id', $area->id)->update(['area_id' => null]);
        });

        static::creating(function ($model){
            $model ->{$model->getKeyName()} = md5(uniqid());
        });
    }

}
