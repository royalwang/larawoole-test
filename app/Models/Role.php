<?php

namespace App\Models;

use Speedy;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    protected $fillable = ['name', 'display_name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function($role){

            //用户与角色对应关系处理
            Speedy::getModelInstance('permission_role')->where('role_id', $role->id)->delete();
            Speedy::getModelInstance('user')->where('role_id', $role->id)->update(['role_id' => null]);
        });
    }
}
