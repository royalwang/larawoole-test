<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';

    protected $fillable = ['name', 'display_name'];

    public function getMenu()
    {
        Auth::user()->role_id;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
