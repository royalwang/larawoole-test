<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teacher';

    protected $fillable = ['user_id','valid','under_sum'];

    public function belongsToUser()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function hasManyShop()
    {
        return $this->hasMany('App\Models\Shop','teacher_id','id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function($teacher){

            //导师与用户对应关系处理
            Speedy::getModelInstance('user')->where('teacher_id', $teacher->user_id)->update(['teacher_id' => null]);

        });

        static::creating(function ($model){

        });
    }
}
