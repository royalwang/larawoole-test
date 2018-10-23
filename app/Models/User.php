<?php

    namespace App\Models;

    use Hanson\Speedy\Traits\PermissionTrait;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class User extends Authenticatable
    {
        use Notifiable , PermissionTrait;

        protected $table = 'users';

        public $incrementing = false;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'display_name' , 'email' , 'password' , 'role_id' , 'name' , 'shops_id' , 'work_id,teacher_id' ,
            'identity' , 'sex' ,
        ];

        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
            'password' , 'remember_token' ,
        ];

        public function belongsToArea()
        {
            return $this->belongsTo( 'App\Models\area' , 'id' , 'user_id' );
        }

        public function belongsToShop()
        {
            return $this->belongsTo( 'App\Models\Shop' , 'shops_id' , 'id' );
        }

        public function hasOneEquipment()
        {
            return $this->hasOne( 'App\Models\Equipment' , 'user_id' , 'id' );
        }

        public function hasManyOrders()
        {
            return $this->hasMany( 'App\Models\Orders' , 'user_id' , 'id' );
        }

        public function hasManyWorkAttendance()
        {
            return $this->hasMany( 'App\Models\Workday' , 'user_id' , 'id' );
        }

        public function hasOneDoingOrder()
        {
            return $this->hasOne( 'App\Models\Orders' , 'user_id' , 'id' )->where( 'status' , '=' , '1' );
        }

        public function role()
        {
            return $this->hasOne( 'App\Models\Role' , 'id' , 'role_id' );
        }

        public static function boot()
        {
            parent::boot();

            static::creating(
                function ( $model )
                {
                    $model->{$model->getKeyName()} = md5( uniqid() );
                }
            );
        }
    }
