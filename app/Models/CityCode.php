<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class CityCode extends Model
    {
        protected $table = 'city_code';

        protected $fillable = [
            'city' , 'code' , 'valid' , 'role_id' ,
        ];

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
