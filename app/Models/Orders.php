<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        //平台原有字段
        'order_num', 'name', 'pic', 'get_type', 'if_get', 'user_id', 'shops_id' , 'valid', 'status', 'equipment_id',
        'pay_time','age','sex','handle_time','finish_time','get_time','wait_num','start_handle',

        //收钱吧返回字段
        'pay_type','price','order_num_sn','order_num_trade','order_status','sqb_status','sub_payway','net_amount','subject'
        ,'sqb_finish_time','payment_list' ,'payer_uid','payer_login','channel_finish_time','result_code','error_code','error_message',
        'error_code_standard',
        ];

    protected $primaryKey = 'id';

    public $incrementing=false;

    public function belongsToUser()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function belongsToShop()
    {
        return $this->belongsTo('App\Models\Shop','shops_id','id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($orders) {

        });

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = md5(uniqid());
        });
    }
}
