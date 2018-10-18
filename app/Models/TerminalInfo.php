<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminalInfo extends Model
{
    protected $table = 'terminal_info';

    protected $fillable = ['terminal_sn','valid','terminal_key','merchant_sn','merchant_name','store_sn','store_name','device_id'];

}
