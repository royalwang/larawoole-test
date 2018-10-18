<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    protected $table = 'workdays';

    public function belongsToUser()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model){
            $model ->{$model->getKeyName()} = md5(uniqid());
        });
    }
}
