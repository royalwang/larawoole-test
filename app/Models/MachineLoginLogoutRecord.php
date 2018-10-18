<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineLoginLogoutRecord extends Model
{
    protected $table = 'machine_login_logout_record';

    protected $fillable = ['user_id','type','verify_code','status'];
}
