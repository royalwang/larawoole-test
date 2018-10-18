<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';

    protected $fillable = ['content','user_id','valid'];

    public function belongsToUser()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public static function boot()
    {
        parent::boot();

    }
}
