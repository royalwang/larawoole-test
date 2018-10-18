<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sp extends Model
{
    protected $table = 'sp';

    protected $fillable = ['shops_id','equipment_id','sp_user','bz_user','status'];

    public function belongsToShop()
    {
        return $this -> belongsTo('shops','shops_id','id');
    }

    public function hasOneEquip()
    {
        return $this -> hasOne('App\Models\Equipment','id','equipment_id');
    }

    public function hasOneSpUser()
    {
        return $this ->hasOne('App\Models\User','id','sp_user');
    }

    public function hasOneSpBzUser()
    {
        return $this ->hasOne('App\Models\User','id','bz_user');
    }

}
