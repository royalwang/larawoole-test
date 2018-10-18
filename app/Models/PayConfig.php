<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayConfig extends Model
{
    protected $table = 'pay_config';

    protected $fillable = ['valid'];
}
