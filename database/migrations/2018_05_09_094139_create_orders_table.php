<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateOrdersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */

        public function up()
        {
            Schema::create(
                'orders' , function ( Blueprint $table )
            {
                $table->string( 'id' );
                $table->primary( 'id' );
                $table->string( 'order_num' );
                $table->string( 'name' )->nullable();
                $table->string( 'pic' )->nullable();
                $table->string( 'get_type' , 1 );//0:现场取票 1:网上预定
                $table->string( 'if_get' , 1 )->nullable();//0:未取到票 1:已取到票
                $table->string( 'user_id' )->nullable();//处理人员id
                $table->string( 'shops_id' )->nullable();//出票店铺
                $table->string( 'valid' , 1 )->default( '1' );
                $table->string( 'status' , 1 )->default( '0' );//0:未处理 1:正在处理 2:处理完毕
                $table->string( 'equipment_id' )->nullable();//处理设备id
                $table->string( 'wait_num' )->nullable();//排队号
                $table->timestamp( 'pay_time' );//支付时间
                $table->timestamp( 'get_time' )->nullable();//取票时间
                $table->string( 'sex' )->nullable();//客户性别
                $table->string( 'age' )->nullable();//客户年龄段 10:5-18,20:18-30,30:30-40,40:40-50,50:50以上
                $table->string( 'handle_time' )->nullable();//处理时间
                $table->timestamp( 'start_handle' )->nullable();//开始处理时间
                $table->timestamp( 'finish_time' )->nullable();//结束处理时间

                //收钱吧返回字段
                $table->string( 'order_num_sn' );//收钱吧系统内部唯一订单号
                $table->string( 'order_num_trade' );//支付通道交易凭证号
                $table->string( 'sqb_status' );//流水状态
                $table->string( 'order_status' );//订单状态
                $table->string( 'pay_type' , 1 );//1:支付宝 3:微信 4:百度钱包 5:京东钱包 6:qq钱包
                $table->string( 'sub_payway' , 2 );//二级支付方式
                $table->string( 'payer_uid' );//支付平台（微信、支付宝）上付款人ID
                $table->string( 'payer_login' );//支付平台（微信、支付宝）上得付款人账号
                $table->string( 'price' )->nullable();//订单价格
                $table->string( 'net_amount' )->nullable();//实收金额
                $table->string( 'subject' )->nullable();//交易概述
                $table->string( 'channel_finish_time' )->nullable();//付款动作在支付服务商的完成时间
                $table->string( 'sqb_finish_time' )->nullable();//付款动作在收钱吧的完成时间
                $table->string( 'payment_list' )->nullable();//活动优惠
                $table->string( 'result_code' )->nullable();//结果码
                $table->string( 'error_code' )->nullable();//错误码
                $table->string( 'error_message' )->nullable();//错误消息
                $table->string( 'error_code_standard' )->nullable();//收钱吧返回错误代码

                $table->timestamps();
            }
            );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists( 'orders' );
        }
    }
