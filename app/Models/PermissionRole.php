<?php

namespace App\Models;

use Speedy;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_role';

    protected $fillable = ['permission_id', 'role_id'];

    public function permissions()
    {
        return $this->belongsTo(Speedy::getDefaultNamespace('permission'));
    }

    public function roles()
    {
        return $this->belongsTo(Speedy::getDefaultNamespace('role'));
    }
}
