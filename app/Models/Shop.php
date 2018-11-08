<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Speedy;

class Shop extends Model
{
    protected $table = 'shops';

    public $incrementing=false;

    protected $fillable = ['name','address','establish_time','manager_id','area_id','teacher_id','discount','city_code'];

    public function hasOneManager()
    {
        return $this->hasOne('App\Models\User','id','manager_id');
    }

    public function belongsToTeacher()
    {
        return $this->belongsTo('App\Models\Teacher','id','teacher_id');
    }

    public function hasManyUsers()
    {
        return $this->hasMany('App\Models\User','shops_id','id')->where('valid','=','1');
    }

    public function hasManyUsersOnWork()
    {
        return $this->hasMany('App\Models\User','shops_id','id')->where('equipment_id','!=',null);
    }

    public function hasManyEquipment()
    {
        return $this->hasMany('App\Models\Equipment');
    }

    public function hasManyOrder()
    {
        return $this -> hasMany('App\Models\Orders','shops_id','id');
    }

    public function hasManyDoneOrder()
    {
        return $this -> hasMany('App\Models\Orders','shops_id','id')->where('status','=','2')->where('valid','=','1');
    }

    public function hasManyOrderWaiting()
    {
        return $this -> hasMany('App\Models\Orders','shops_id','id')->where('if_get','=','1')->where('status','=','0')->where('valid','=','1');
    }

    public function hasManyCustomThroughOrder()
    {
        return $this ->hasManyThrough('App\Modes\User','Order');
    }

    public function hasManySp()
    {
        return $this->hasMany('App\Models\Sp','shops_id','id')->where('valid','=','1')->orderByDesc('updated_at');
    }

    public function belongsToArea()
    {
        return $this->belongsTo('App\Models\area','area_id','id');
    }

    public function hasOneCity()
    {
        return $this->hasOne('App\Models\CityCode','id','city_code');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function($shop){

            //商铺与用户对应关系处理
            Speedy::getModelInstance('user')->where('shops_id', $shop->id)->update(['shops_id' => null]);

            //商铺与设备对应关系处理
//            Speedy::getModelInstance('equipment')->where('shops_id', $shops->id)->update(['shops_id' => null]);
        });

        static::creating(function ($model){
            $model ->{$model->getKeyName()} = md5(uniqid());
            Speedy::getModelInstance('user')->where('id', $model->manager_id)->update(['shops_id' => $model->id]);
        });
    }
}
