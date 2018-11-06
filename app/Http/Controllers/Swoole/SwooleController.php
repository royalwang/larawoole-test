<?php

    namespace App\Http\Controllers\Swoole;

    use App\Http\Controllers\Controller;
    use App\Models\Shop;
    use Carbon\Carbon;
    use App\Models\Equipment;
    use App\Models\User;
    use App\Models\Orders;
    use Illuminate\Support\Facades\Hash;
    use App\Models\TerminalInfo;
    use App\Models\PayConfig;
    use App\Models\ShopMachineConfigRel;
    use Speedy;

    class SwooleController extends Controller
    {
        protected $pay_code = '';

        public function __construct()
        {
            //
        }

        /**
         * 打印日志
         *
         * @access public
         *
         * @param object $sig 非必填服务器对象
         * @param object $fd 与下位机连接线程对象
         * @param string $from_id 线程ID
         * @param string $data 接收下位机数据
         *
         * @since 1.0
         * @return void
         */
        public function printLog( $sig , $serv = '' , $fd = '' , $from_id = '' , $data = '' )
        {
            switch ( $sig )
            {
                case 'start':
                    echo Carbon::now() . ' Swoole onStart!' . PHP_EOL;
                    break;
                case 'connect':
                    echo Carbon::now() . " fd:{$fd} onConnect!,connect with {$from_id}." . PHP_EOL;
                    break;
                case 'receive':
                    echo Carbon::now() . " fd:{$fd} onReceive data: {$data} from {$from_id}!" . PHP_EOL;
                    break;
                case 'close':
                    echo Carbon::now() . " Client {$from_id} close connection with fd:{$fd}" . PHP_EOL;
                    break;
            }
        }

        /**
         * 服务器启动
         *
         * @access public
         * @since 1.0
         * @return void
         */
        public function onStart()
        {
            $this->printLog( 'start' );
        }

        /**
         * 服务器连接成功
         *
         * @access public
         *
         * @param object $serv 服务器对象
         * @param object $fd 与下位机连接线程对象
         * @param string $from_id 线程ID
         *
         * @since 1.0
         * @return void
         */
        public function onConnect( $serv , $fd , $from_id )
        {
            $this->printLog( 'connect' , $serv , $fd , $from_id );
            $this->sendMsg( $serv , $fd , '200' );
        }

        /**
         * 服务器接收数据
         *
         * @access public
         *
         * @param object $serv 服务器对象
         * @param object $fd 与下位机连接线程对象
         * @param string $from_id 线程ID
         * @param string $data 接收下位机数据
         *
         * @since 1.0
         * @return void
         */
        public function onReceive( $serv = '' , $fd = '' , $from_id = '' , $data = '' )
        {
            //测试接收代码
            //$data = '$0034579001805001,641810130840057\r';//查询订单
            //$data = '$0018529001805001\r';//查询价格
            //$data = '$0037539001805001,123456789012345678\r';//创建订单
            //$data = '$0034569001805001,631810131052067\r';//提交支付
            //$data = '$0034549001805001,651810161843006\r';//打印小票成功
            //$data = '$0047058001810002,800001180009,651810161843006\r';//开始接单
            //$data = '$0053068001810002,800001180009,651810161843006,M2,04\r';//销单
            $data = '$0018589001811002\r';//获取门店信号灯,30秒发送一次更新状态

            //$this->pay_code = '135800752045370032';
            //心跳包不打印日志
            if ( substr( $data , 5 , 2 ) != '08' )
            {
                $this->printLog( 'receive' , $serv , $fd , $from_id , $data );
            }
            if ( $this->verifyData( $data ) == '555' || $this->verifyData( $data ) == '999' )
            {
                $this->sendMsg( $serv , $fd , $this->verifyData( $data ) );
            }
            else
            {
                $this->sendMsg( $serv , $fd , $this->proceedData( $data ) );
            }
        }

        /**
         * 服务器关闭
         *
         * @access public
         *
         * @param object $serv 服务器对象
         * @param object $fd 与下位机连接线程对象
         * @param string $from_id 线程ID
         *
         * @since 1.0
         * @return void
         */
        public function onClose( $serv , $fd , $from_id )
        {
            $this->printLog( 'close' , $serv , $fd , $from_id );
        }

        /**
         * 编码信息表
         *
         * @access public
         *
         * @param string $serv 向下位机指令代码
         * @param object $type 发送类型 0：发送编码翻译 1：直接发送编码
         *
         * @since 1.0
         * @return string
         */
        public function message( $code , $type )
        {
            $status = [
                '010' => 'Access Agreed' ,
                '011' => 'Equip Breakdown' ,
                '012' => 'Not Exist Equip' ,
                '020' => 'Login Success' ,
                '021' => 'Login Fail' ,
                '022' => 'Not Exist Staff' ,
                '023' => 'Occupy' ,
                '030' => 'Staff Logout Success' ,
                '031' => 'Manager Logout Success' ,
                '032' => 'Logout Fail' ,
                '040' => 'Command Success' ,
                '041' => 'Command Fail' ,
                '050' => 'Order Start' ,
                '051' => 'Expire' ,
                '052' => 'Order Error' ,
                '060' => 'Command Success' ,
                '061' => 'Command Fail' ,
                '200' => 'TCP Connected || Data correct' ,
                '400' => 'TCP Connect Fail' ,
                '000' => 'Inner Error' ,
                '666' => 'DATA correct' ,
                '555' => 'Data Error' ,
                '999' => 'CRC Error' ,
                '080' => 'Heart Bit' ,
                '500' => 'Access Agreed' ,
                '501' => 'Equip Breakdown' ,
                '502' => 'Not Exist Equip' ,
                '510' => 'Shop Opened' ,
                '511' => 'Shop Closed' ,
                '520' => 'Reply Price' ,
                '530' => 'Get Order Success' ,
                '531' => 'Get Order Fail' ,
                '561' => 'Pay Success' ,
                '560' => 'Pay Fail' ,
                '570' => 'Pay Success' ,
                '571' => 'Pay Fail' ,
                '540' => 'Print Success' ,
                '550' => 'Print Success' ,
                '580' => 'Green',
                '581' => 'Yellow',
                '582' => 'Red',
                '583' => 'Light Error',
            ];
            if ( $type == 0 )
            {
                return $status[ $code ];
            }
            else
            {
                return $code;
            }
        }

        /**
         * 向下位机发送指令代码
         *
         * @access public
         *
         * @param string $data 向下位机指令代码
         * @param object $serv 服务器对象
         * @param object $fd 与下位机连接线程对象
         *
         * @since 1.0
         * @return void
         */
        public function sendMsg( $serv , $fd , $data )
        {
            $len = strlen( $data ) + 6;
            if ( strlen( $len ) == 1 )
            {
                $sendData = '%' . '000' . ( $len ) . $data;
            }
            else if ( strlen( $len ) == 2 )
            {
                $sendData = '%' . '00' . ( $len ) . $data;
            }
            else if ( strlen( $len ) == 3 )
            {
                $sendData = '%' . '0' . ( $len ) . $data;
            }
            else
            {
                $sendData = '%' . ( $len ) . $data;
            }

            //$crc = $this->crc16_modbus(substr($sendData,1,-1)); //读取CRC16校验码

            $sendData .= '\r';//数据结尾
            dd( $sendData );
            $serv->send( $fd , $this->message( $sendData , '1' ) );
        }

        /*
         * 校验发送信息正确性
         * */
        public function verifyData( $data )
        {
            //校验起始符号
            if ( $data[0] == '$' )
            {
                //校验数据长度
                if ( ( strlen( $data ) - 1 ) == intval( substr( $data , 1 , 4 ) ) )
                {
                    //校验CRC16校验码
                    //$crc = $this->crc16_modbus(substr($data , 1 , -4));
                    //if ($crc != substr($data , -4 , 4)) {
                    //return '999';
                    //}
                    return '666';
                }
                else
                {
                    return '555';
                }
            }
        }

        /**
         * 开始处理下位机指令
         *
         * @access public
         *
         * @param string $data 下位机指令代码
         *
         * @since 1.0
         * @return string
         */
        public function proceedData( $data )
        {
            switch ( substr( $data , 5 , 2 ) )
            {
                //机器入网请求
                case ( '01' ):
                    $str   = explode( '\\' , $data );
                    $equip = Equipment::where( 'verify_code' , substr( $str[0] , 7 ) )->first();
                    $time  = Carbon::now();
                    if ( $equip )
                    {
                        if ( $equip->status != '5' )
                        {
                            return '010' . substr( $time , 0 , 19 );//可以接入
                        }
                        else
                        {
                            return '011';//故障不能接入
                        }
                    }
                    else
                    {
                        return '012';//没有注册设备
                    }
                    break;

                //员工登入
                case ( '02' ):

                    $str   = explode( ',' , $data );
                    $equip = Equipment::where( 'verify_code' , substr( $str[0] , 7 ) )->first();
                    $user  = User::where( 'work_id' , $str[1] )->first();

                    if ( ! $user )
                    {

                        return '022';//无此员工

                    } //elseif ($equip->work_id) {

                    //return '023';//员工机已被其他人使用}

                    else if ( Hash::check( substr( $str[2] , 0 , 6 ) , $user->password ) )
                    {

                        $equip->update( [ 'user_id' => $user->id , 'status' => '1' ] );
                        Speedy::getModelInstance( 'machine_login_logout_record' )
                            ->create(
                                [
                                    'user_id'     => $user->id ,
                                    'verify_code' => $equip->verify_code ,
                                    'type'        => '0' ,
                                    'status'      => '0' ,
                                ]
                            );

                        return '020';//登录成功

                    }
                    else
                    {

                        return '021';//登录失败

                    }
                    break;

                //员工登出
                case ( '03' ):

                    $str   = explode( ',' , $data );
                    $equip = Equipment::where( 'verify_code' , substr( $str[0] , 7 ) )->first();
                    $user  = User::where( 'work_id' , $str[1] )->first();

                    if ( Hash::check( $str[2] , $user->password ) )
                    {
                        if ( $user->role_id == '5' && $user->id == $equip->user_id )
                        {

                            $equip->update( [ 'user_id' => null ] );
                            Speedy::getModelInstance( 'machine_login_logout_record' )
                                ->create(
                                    [
                                        'user_id'     => $user->id ,
                                        'verify_code' => $equip->verify_code ,
                                        'type'        => '1' ,
                                        'status'      => '0' ,
                                    ]
                                );

                            return '030';//员工登出成功

                        }
                        else if ( $user->role_id != '5' )
                        {
                            $equip->update( [ 'user_id' => null ] );
                            Speedy::getModelInstance( 'machine_login_logout_record' )
                                ->create(
                                    [
                                        'user_id'     => $user->id ,
                                        'verify_code' => $equip->verify_code ,
                                        'type'        => '1' ,
                                        'status'      => '0' ,
                                    ]
                                );

                            return '031';//管理员强制登出员工账号成功

                        }
                        else
                        {

                            return '032';

                        }
                    }
                    else
                    {
                        return '032';//登出失败
                    }
                    break;

                //员工暂停服务、恢复服务
                case ( '04' ):

                    $str   = explode( ',' , $data );
                    $equip = Equipment::where( 'verify_code' , substr( $str[0] , 7 ) )->first();
                    $user  = User::where( 'work_id' , $str[1] )->first();

                    if ( $str[2][0] == '0' )
                    {
                        if ( $equip->user_id == $user->id && $equip->status == '1' )
                        {

                            $equip->update( [ 'status' => '4' ] );

                            return '040';//员工机操作成功

                        }
                        else
                        {

                            return '041';//员工机操作失败

                        }
                    }
                    else
                    {
                        if ( $equip->user_id == $user->id && $equip->status == '4' )
                        {
                            $equip->update( [ 'status' => '1' ] );

                            return '040';
                        }
                        else
                        {
                            return '041';
                        }
                    }
                    break;

                //接单开始
                case ( '05' ):

                    $str       = explode( ',' , $data );
                    $equip     = Equipment::where( 'verify_code' , substr( $str[0] , 7 ) )->first();
                    $order_num = explode( '\\' , $str[ ( 2 ) ] );
                    $order     = Orders::where( 'order_num' , $order_num[0] )->first();
                    $user      = User::where( 'work_id' , $str[1] )->first();

                    if ( ! $order )
                    {

                        return '052';//订单错误或不存在

                    }
                    else if ( $order->finish_time != 0 )
                    {

                        return '051';//订单已过期

                    }
                    else
                    {

                        $order->update(
                            [
                                'status'       => '1' ,
                                'user_id'      => $user->id ,
                                'equipment_id' => $equip->id ,
                                'start_handle' => Carbon::now() ,
                            ]
                        );

                        return '050';//订单操作开始

                    }
                    break;

                //接单结束
                case ( '06' ):

                    $str   = explode( ',' , $data );
                    $order = Orders::where( 'order_num' , $str[2] )->first();

                    if ( $order )
                    {
                        $order->update(
                            [
                                'status'        => '2' , 'sex' => substr( $str[3] , 0 , 1 ) ,
                                'age'           => substr( $str[3] , 1 , 2 )
                                , 'handle_time' => intval( substr( $str[4] , 0 ) ) * 60 ,
                                'finish_time'   => Carbon::now() ,
                            ]
                        );

                        return '060';//订单操作成功

                    }
                    else
                    {

                        return '061';//订单操作失败

                    }
                    break;

                //心跳包
                case ( '08' ):
                    return '080';
                    break;

                /*
                 * 收钱机指令开始
                 * 机器入网请求
                 * */
                case ( '50' ):
                    $str        = explode( '\\' , $data );
                    $machine_sn = substr( $str[0] , 7 );
                    $time       = substr( Carbon::now() , 0 , 19 );

                    $equip = Equipment::where( 'verify_code' , $machine_sn )->where( 'valid' , '1' )->where( 'type' , '1' )->first();
                    if ( ! $equip )
                    {
                        return '502';
                    }
                    else if ( $equip->status == '5' )
                    {
                        return '501';
                    }
                    else
                    {
                        return '500' . $time;
                    }

                    break;

                //心跳包
                case ( '51' ):
                    $now   = Carbon::now()->toTimeString();
                    $start = Carbon::createFromTime( '07' , '00' , '00' )->toTimeString();
                    $end   = Carbon::createFromTime( '02' , '59' , '59' )->toTimeString();
                    if ( $now > $start || $now < $end )
                    {
                        return '510';
                    }
                    else
                    {
                        return '511';
                    }
                    break;

                //询问当天收款价格
                case ( '52' ):
                    $str        = explode( '\\' , $data );
                    $machine_sn = substr( $str[0] , 7 );
                    $shop       = Shop::where( 'machine_sn' , $machine_sn )->where( 'valid' , '1' )->first();
                    if ( $shop )
                    {
                        return '520' . ( $shop->discount ) / 10;
                    }
                    else
                    {
                        return '521';
                    }
                    break;

                //收款请求
                case ( '53' ):
                    $str            = explode( ',' , $data );
                    $machine_sn     = substr( $str[0] , 7 );
                    $shop           = Shop::where( 'machine_sn' , $machine_sn )->where( 'valid' , '1' )->first();
                    $order_num      = $this->getOrderNum();
                    $this->pay_code = substr( explode( '\\' , $str[1] )[0] , 0 );

                    //创建订单
                    $this->createOrder( $order_num , $shop->id );

                    return '530' . $order_num;
                    break;

                //开始支付
                case ( '56' ):
                    $str        = explode( ',' , $data );
                    $order_num  = substr( explode( '\\' , $str[1] )[0] , 0 );
                    $dynamic_id = $this->pay_code;

                    $machine_sn = substr( $str[0] , 7 );
                    $shop       = Shop::where( 'machine_sn' , $machine_sn )->where( 'valid' , '1' )->first();
                    //支付逻辑开始
                    $response = $this->gotoPay( $machine_sn , $dynamic_id , $order_num , $shop->discount );

                    if ( $response->data )
                    {
                        if ( $response->data->order_status == 'PAID' )
                        {
                            $order = Orders::where( 'order_num' , $response->data->client_sn )->first();

                            return '560' . $order->wait_num;
                        }
                        else
                        {
                            return '561';
                        }
                    }
                    else
                    {
                        return '561';
                    }
                    break;

                //查询支付结果
                case ( '57' ):
                    $str       = explode( ',' , $data );
                    $order_num = substr( explode( '\\' , $str[1] )[0] , 0 );

                    $response = $this->gotoPayQuery( $order_num );

                    if ( $response )
                    {
                        $order = Orders::where( 'order_num' , $order_num )->first();

                        return '570' . $order->wait_num;
                    }
                    else
                    {
                        return '571';
                    }
                    break;

                //小票打印成功返回命令
                case ( '54' ):
                    $str       = explode( ',' , $data );
                    $order_num = substr( explode( '\\' , $str[1] )[0] , 0 );
                    $order     = Orders::where( 'order_num' , $order_num )->update(
                        [
                            'if_get'   => '1' ,
                            'get_time' => Carbon::now()->toDateTimeString() ,
                        ]
                    );
                    if ( $order )
                    {
                        return '540';
                    }
                    else
                    {
                        return '541';
                    }
                    break;

                //请求重新打印上一条订单信息
                case ( '55' ):
                    $str       = explode( ',' , $data );
                    $order_num = substr( explode( '\\' , $str[1] )[0] , 0 );
                    $order     = Orders::where( 'order_num' , $order_num )->where( 'valid' , '1' )->update(
                        [
                            'if_get'   => '1' ,
                            'get_time' => Carbon::now()->toDateTimeString() ,
                        ]
                    );
                    if ( $order )
                    {
                        return '550';
                    }
                    else
                    {
                        return '551';
                    }
                    break;

                //获取门店指示灯
                case ( '58' ):
                    $str      = explode( '\\' , $data );
                    $equip_id = substr( $str[0] , 7 );
                    $light    = $this->getShopLight( $equip_id );

                    return '58' . $light;
                    break;
            }

            return '000';
        }

        /**
         * 开始支付业务
         *
         * @access public
         *
         * @param string $machine_id 收银机序列号
         * @param string $dynamic_id 支付码
         * @param string $order_num 订单号
         *
         * @since 1.0
         * @return object|string
         */
        public function gotoPay( $machine_id , $dynamic_id , $order_num , $price )
        {
            $subject   = config( 'shouqianba.subject' );
            $operator  = config( 'shouqianba.operator' );
            $device_id = config( 'shouqianba.device_id' );

            //判断终端是否已激活
            $terminal = $this->getActivateInfo( $device_id );

            //判断是否需要签到
            $checkIn = $this->getCheckInInfo( $terminal , $machine_id , $device_id );

            //开始支付
            if ( $checkIn )
            {
                $pay = $this->payCommit( $checkIn->terminal_sn , $order_num , $price , $dynamic_id , $subject , $operator , $checkIn->terminal_key );

                return $pay;
            }
            else
            {
                echo 'pay error';
            }
        }

        /**
         * 开始查询订单
         *
         * @access public
         *
         * @param string $client_sn 订单号
         *
         * @since 1.0
         * @return boolean
         */
        public function gotoPayQuery( $client_sn )
        {
            $device_id = config( 'shouqianba.device_id' );
            $terminal  = $this->getActivateInfo( $device_id );

            //查询订单
            $response = $this->payQuery( $terminal->terminal_sn , '' , $client_sn , $terminal->terminal_key );

            $result = json_decode( $response );
            if ( $result->result_code == '200' )
            {
                if ( $result->biz_response->result_code == 'SUCCESS' )
                {
                    if ( $result->biz_response->data->order_status == 'PAID' )
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        /**
         * 开始处理订单业务逻辑
         *
         * @access public
         *
         * @param object $response 接口响应response
         * @param string $terminal_key 终端密钥，查询订单必填参数
         *
         * @since 1.0
         * @return string
         */
        public function handleOrder( $response , $terminal_key )
        {
            $result = json_decode( $response );
            if ( $result->result_code == '200' )
            {
                $rdata = $result->biz_response;
                if ( $result->result_code == '200' )
                {
                    //检查订单状态
                    return $this->checkOrderStatus( $rdata , $terminal_key );
                }
                else
                {
                    echo '支付通讯失败';

                    return '支付通讯失败';
                }
            }
            else
            {
                return $result->error_message;
            }
        }

        /**
         * 校验订单支付状态
         *
         * 校验订单支付状态，如已支付状态为最终状态（
         * PAID
         * PAY_CANCELED
         * REFUNDED
         * PARTIAL_REFUNDED
         * CANCELED
         * ），更新订单信息，否则轮询查询订单状态至最终状态为止或请求时间结束（40秒）
         * @access public
         *
         * @param object $data 接口响应response
         * @param string $terminal_key 终端密钥，查询订单必填参数
         *
         * @since 1.0
         * @return object
         */
        public function checkOrderStatus( $data , $terminal_key )
        {
            $rorder = '';
            if ( $data->result_code = 'PAY_SUCCESS' || $data->result_code = 'PAY_FAIL' || $data->result_code = 'FAIL' )
            {
                $rorder = $this->updateOrder( $data );
            }
            else
            {
                if ( $data->data->order_status == 'PAID' &&
                    $data->data->order_status == 'PAY_CANCELED' &&
                    $data->data->order_status == 'REFUNDED' &&
                    $data->data->order_status == 'PARTIAL_REFUNDED' &&
                    $data->data->order_status == 'CANCELED'
                )
                {
                    return $rorder = $this->updateOrder( $data );
                }
                else
                {
                    //轮询订单状态，轮询总时长40s
                    for ( $i = 0 ; $i < 8 ; $i++ )
                    {
                        sleep( 5 );
                        $response = $this->payQuery( $data->data->terminal_sn , $data->data->sn , $data->data->client_sn , $terminal_key );

                        if ( $response->biz_response->data->order_status == 'PAID' &&
                            $response->biz_response->data->order_status == 'PAY_CANCELED' &&
                            $response->biz_response->data->order_status == 'REFUNDED' &&
                            $response->biz_response->data->order_status == 'PARTIAL_REFUNDED' &&
                            $response->biz_response->data->order_status == 'CANCELED'
                        )
                        {
                            $rorder = $this->updateOrder( $response->biz_response );
                            break;
                        }
                    }
                }
            }

            return $rorder;
        }

        /**
         * 创建新订单信息
         *
         * @access public
         *
         * @param string $order_num 订单号码
         * @param string $shop_id 门店ID
         *
         * @since 1.0
         * @return object
         */
        public function createOrder( $order_num , $shop_id )
        {
            $order = Speedy::getModelInstance( 'order' )->create(
                [
                    //平台字段
                    'shops_id'  => $shop_id ,
                    'get_type'  => '0' ,
                    'order_num' => $order_num ,
                ]
            );

            echo '创建了订单';

            return $order;
        }

        /**
         * 更新订单信息
         *
         * 根据接口返回结果更新订单信息，如支付成功需更新排队号
         * @access public
         *
         * @param object $rdata 接口响应response
         *
         * @since 1.0
         * @return object
         * @todo 返回值未完全，优化逻辑减少请求量
         */
        public function updateOrder( $rdata )
        {
            if ( $rdata->data )
            {
                if ( $rdata->data->order_status == 'PAID' )
                {
                    //获取已又排队号码
                    $number = $this->getQueueNumber();
                    Speedy::getModelInstance( 'order' )->where( 'order_num' , $rdata->data->client_sn )->update(
                        [
                            'wait_num'      => $number ,
                            'pay_time'      => Carbon::now()->toDateTimeString() ,

                            //收钱吧字段
                            'result_code'   => $rdata->result_code ,
                            'error_code'    => $rdata->error_code ,
                            //'error_code_standard' => $rdata->error_code_standard ,
                            'error_message' => $rdata->error_message ,

                            'order_num_sn'        => $rdata->data->sn ,
                            'order_num_trade'     => $rdata->data->trade_no ,
                            'sqb_finish_time'     => $rdata->data->finish_time ,
                            'channel_finish_time' => $rdata->data->channel_finish_time ,
                            'sqb_status'          => $rdata->data->status ,
                            'order_status'        => $rdata->data->order_status ,
                            'pay_type'            => $rdata->data->payway ,
                            'sub_payway'          => $rdata->data->sub_payway ,
                            'payer_uid'           => $rdata->data->payer_uid ,
                            'payer_login'         => $rdata->data->payer_login ,
                            'price'               => $rdata->data->total_amount ,
                            'net_amount'          => $rdata->data->net_amount ,
                            'subject'             => $rdata->data->subject ,
                            //'error_code_standard' => $rdata->error_code_standard ,
                        ]
                    );

                }
                else
                {
                    Speedy::getModelInstance( 'order' )->where( 'order_num' , $rdata->data->client_sn )->update(
                        [
                            //收钱吧字段
                            'result_code'   => $rdata->result_code ,
                            'error_code'    => $rdata->error_code ,
                            //'error_code_standard' => $rdata->error_code_standard ,
                            'error_message' => $rdata->error_message ,

                            'order_num_sn'        => $rdata->data->sn ,
                            'order_num_trade'     => $rdata->data->trade_no ,
                            'sqb_finish_time'     => $rdata->data->finish_time ,
                            'channel_finish_time' => $rdata->data->channel_finish_time ,
                            'sqb_status'          => $rdata->data->status ,
                            'order_status'        => $rdata->data->order_status ,
                            'pay_type'            => $rdata->data->payway ,
                            'sub_payway'          => $rdata->data->sub_payway ,
                            'payer_uid'           => $rdata->data->payer_uid ,
                            'payer_login'         => $rdata->data->payer_login ,
                            'price'               => $rdata->data->total_amount ,
                            'net_amount'          => $rdata->data->net_amount ,
                            'subject'             => $rdata->data->subject ,
                            //'error_code_standard' => $rdata->error_code_standard ,
                        ]
                    );
                }
            }

            return $rdata;
        }

        /**
         * 获取支付配置
         *
         * @access public
         *
         * @param string $device_id 支付设备号
         *
         * @since 1.0
         * @return object
         */
        public function getVendorInfo( $device_id )
        {
            $rel = ShopMachineConfigRel::where( 'machine_sn' , $device_id )
                ->where( 'valid' , '1' )
                ->first();

            $config = PayConfig::where( 'valid' , '1' )
                ->where( 'id' , $rel->config_id )
                ->first();

            return $activate = $this->payActivate( $config ); //激活
        }

        /**
         * 获取门店排队号码
         *
         * 排队号码根据当前等待人数进行计算，上下午分开标识，上午排队号用A开头字符进行标识，下午排队号用P
         * @access public
         * @since 1.0
         * @return string
         */
        public function getQueueNumber()
        {
            $nowTime = Carbon::now()->toDateTimeString();
            $start   = Carbon::today()->toDateTimeString();
            $middle  = Carbon::createFromTime( '12' , '00' , '00' )->toDateTimeString();
            $end     = Carbon::createFromTime( '23' , '59' , '59' )->toDateTimeString();

            if ( $nowTime > $start && $nowTime < $middle )
            {

                //打印上午排队号
                $number = Orders::whereBetween( 'pay_time' , [ $start , $middle ] )
                    ->where( 'valid' , '1' )
                    ->where( 'order_status' , 'PAID' )
                    ->count();
                $number < 9 ? $number = 'A0' . ( $number + 1 ) : $number = 'A' . ( $number + 1 );

            }
            else
            {

                //打印下午排队号
                $number = Orders::whereBetween( 'pay_time' , [ $middle , $end ] )
                    ->where( 'valid' , '1' )
                    ->where( 'order_status' , 'PAID' )
                    ->count();
                $number < 9 ? $number = 'P0' . ( $number + 1 ) : $number = 'P' . ( $number + 1 );

            }

            return $number;
        }

        /**
         * 获取支付激活信息
         *
         * @access public
         *
         * @param string $device_id 支付设备号
         *
         * @since 1.0
         * @return object
         */
        public function getActivateInfo( $device_id )
        {
            $terminal = TerminalInfo::where( 'device_id' , $device_id )->first();

            echo 'terminal:' . $terminal . '<br>';

            return $terminal;
        }

        /**
         * 获取支付设备签到信息
         *
         * @access public
         *
         * @param object $terminal 历史签到信息
         * @param string $machine_id 收音机序列号
         * @param string $device_id 支付设备号
         *
         * @since 1.0
         * @return object
         */
        public function getCheckInInfo( $terminal , $machine_id , $device_id )
        {
            //有历史签到信息
            if ( $terminal )
            {
                $isToday = $terminal->updated_at->isToday();
                if ( $isToday )
                {

                    echo '<br>当前时间无需签到，可直接使用<br>';
                    echo 'terminal:' . $terminal . '<br>';

                    return $terminal;

                }
                else
                {

                    $response = $this->payCheckIn( $terminal->terminal_sn , $terminal->terminal_key );

                    $terminal = $this->updateCheckIn( $response );

                    echo '<br>当前时间需要重新签到，签到信息已更新<br>';
                    echo 'terminal:' . $terminal . '<br>';

                    return $terminal;
                }
            }
            else
            {

                $terminal = $this->getVendorInfo( $device_id ); //无历史签到信息,获取门店VENDOR信息

                return $terminal;
            }
        }

        /**
         * 更新支付设备签到信息
         *
         * @access public
         *
         * @param string $response 激活请求响应response
         *
         * @since 1.0
         * @return object
         */
        public function updateCheckIn( $response )
        {
            $result   = json_decode( $response );
            $terminal = '';

            if ( $result->result_code == 200 )
            {
                $result_data = $result->biz_response;

                TerminalInfo::where( 'device_id' , config( 'shouqianba.device_id' ) )
                    ->where( 'valid' , '1' )
                    ->first()
                    ->update(
                        [
                            'terminal_sn'  => $result_data->terminal_sn ,
                            'terminal_key' => $result_data->terminal_key ,
                        ]
                    );

                $terminal = TerminalInfo::where( 'device_id' , config( 'shouqianba.device_id' ) )->first();
            }

            return $terminal;
        }

        /**
         * 更新支付激活信息
         *
         * @access public
         *
         * @param string $response 激活请求响应response
         *
         * @since 1.0
         * @return object
         */
        public function updateActivateInfo( $response )
        {
            $result   = json_decode( $response );
            $terminal = '';
            echo '<br>重新激活<br>';
            if ( $result->result_code == 200 )
            {
                $result_data = $result->biz_response;

                $terminal = Speedy::getModelInstance( 'terminal_info' )->create(
                    [
                        'terminal_sn'   => $result_data->terminal_sn ,
                        'terminal_key'  => $result_data->terminal_key ,
                        'merchant_sn'   => $result_data->merchant_sn ,
                        'merchant_name' => $result_data->merchant_name ,
                        'store_sn'      => $result_data->store_sn ,
                        'store_name'    => $result_data->store_name ,
                        'device_id'     => config( 'shouqianba.device_id' ) ,
                    ]
                );
            }

            echo '<br>' . 'terminal:' . $terminal . '<br>';

            return $terminal;

        }

        /**
         * 发起签到请求
         *
         * @access public
         *
         * @param string $terminal_key 终端密钥
         * @param string $terminal_sn 终端序列号
         *
         * @since 1.0
         * @return string
         */
        public function payCheckIn( $terminal_sn , $terminal_key )
        {
            $url = config( 'shouqianba.url.checkin' );

            $data['terminal_sn'] = $terminal_sn;
            $data['device_id']   = config( 'shouqianba.device_id' );
            $params              = json_encode( $data );

            $sign = $this->getSign( $params . $terminal_key );

            $headers = [
                "Format:json" ,
                "Content-Type:application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            return $this->doCurlJsonRequest( $url , $params , $headers );
        }

        /**
         * 发起激活请求
         *
         * @access public
         *
         * @param string $config 支付配置
         *
         * @since 1.0
         * @return object
         */
        public function payActivate( $config )
        {
            $url   = config( 'shouqianba.url.activate' );
            $data  = [
                'app_id'    => $config->app_id ,
                'code'      => $config->activate_code ,
                'device_id' => $config->device_id ,
            ];
            $param = json_encode( $data );

            $sign = $this->getSign( $param . $config->vendor_key );

            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$config->vendor_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest( $url , $param , $headers );
            echo '<br>绑定激活信息<br>';
            echo '$response:' . $response;

            return $this->updateActivateInfo( $response );
        }

        /**
         * 发起支付请求
         *
         * @access public
         *
         * @param string $terminal_sn 终端序列号
         * @param string $terminal_key 终端密钥
         * @param string $total_amount 支付金额，以分为单位
         * @param string $order_num 订单号
         * @param string $dynamic_id 支付码
         * @param string $subject 商品描述
         * @param string $operator 操作人员
         *
         * @since 1.0
         * @return object
         */
        public function payCommit( $terminal_sn , $order_num , $total_amount , $dynamic_id , $subject , $operator , $terminal_key )
        {
            $url = config( 'shouqianba.url.pay' );

            $data['terminal_sn']  = $terminal_sn;
            $data['client_sn']    = $order_num;
            $data['total_amount'] = $total_amount;
            $data['dynamic_id']   = $dynamic_id;
            $data['subject']      = $subject;
            $data['operator']     = $operator;

            $params  = json_encode( $data );
            $sign    = $this->getSign( $params . $terminal_key );
            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest( $url , $params , $headers );

            $pay = $this->handleOrder( $response , $terminal_key );

            return $pay;
        }

        /**
         * 发起查询订单请求
         *
         * @access public
         *
         * @param string $terminal_sn 终端序列号
         * @param string $sn 服务商唯一订单号
         * @param string $terminal_key 终端密钥，生成签名需用到
         * @param string $client_sn 订单号
         *
         * @since 1.0
         * @return string
         */
        public function payQuery( $terminal_sn , $sn , $client_sn , $terminal_key )
        {
            $url = config( 'shouqianba.url.query' );

            $data['terminal_sn'] = $terminal_sn;
            $data['sn']          = $sn;
            $data['client_sn']   = $client_sn;

            $params = json_encode( $data );

            $sign = $this->getSign( $params . $terminal_key );

            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest( $url , $params , $headers );

            echo '查询了订单';

            return $response;
        }

        /**
         * 发起撤销订单请求
         *
         * @access public
         *
         * @param string $terminal_sn 终端序列号
         * @param string $terminal_key 终端密钥，生成签名需用到
         * @param string $client_sn 订单号
         *
         * @since 1.0
         * @return string
         * @todo 撤销功能未在支付逻辑中加入
         */
        public function payCancel( $terminal_sn , $terminal_key , $client_sn )
        {
            $url = config( 'shouqianba.url.cancel' );

            $data['terminal_sn'] = $terminal_sn;
            $data['client_sn']   = $client_sn;

            $params = json_encode( $data );

            $sign = $this->getSign( $params . $terminal_key );

            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest( $url , $params , $headers );

            echo '撤销了订单';

            return $response;
        }

        /**
         * 使用CURL发送JSON请求
         *
         * @access public
         *
         * @param string $url 请求地址
         * @param string $data json串数据
         * @param array $headers 头设置
         *
         * @since 1.0
         * @return string
         */
        public function doCurlJsonRequest( $url , $data , $headers )
        {

            $con = curl_init();

            curl_setopt( $con , CURLOPT_SSL_VERIFYPEER , false ); // 跳过证书检查
            curl_setopt( $con , CURLOPT_SSL_VERIFYHOST , 2 );  // 从证书中检查SSL加密算法是否存在
            curl_setopt( $con , CURLOPT_URL , $url );
            curl_setopt( $con , CURLOPT_HEADER , false );
            curl_setopt( $con , CURLOPT_HTTPHEADER , $headers );
            curl_setopt( $con , CURLOPT_RETURNTRANSFER , true );
            curl_setopt( $con , CURLOPT_POST , true );
            curl_setopt( $con , CURLOPT_POSTFIELDS , $data );
            curl_setopt( $con , CURLOPT_FOLLOWLOCATION , 0 );
            //curl_setopt($con , CURLOPT_TIMEOUT , (int) $timeout);

            $output = curl_exec( $con );
            curl_close( $con );

            return $output;
        }

        /**
         * 获取加密签名
         *
         * @access public
         *
         * @param string $signStr 签名字符串
         *
         * @since 1.0
         * @return string
         */
        public function getSign( $signStr )
        {
            $md5 = Md5( $signStr );

            return $md5;
        }

        /**
         * 生成15位唯有订单号码
         *
         * 以6开头，拼接当前时间与两位随机数字
         * @access public
         * @since 1.0
         * @return string
         */
        public function getOrderNum()
        {
            $time      = Carbon::now()->toDateTimeString();
            $order_num = '6' . random_int( 0 , 9 ) . substr( str_replace( [ '-' , ':' , ':' , ' ' ] , '' , $time ) , 2 )
                . random_int( 0 , 9 );

            return $order_num;
        }

        /**
         * 获取门店指示灯
         *
         * 根据门店当前正在等待的人数决定指示灯颜色等候人数：[0,3)  绿灯，等候人数：[3,5]  黄灯，等候人数：（5，∞） 红灯，注意开闭区间
         *
         * @access public
         *
         * @param string $equip_id 机器识别码
         *
         * @since 1.0
         * @return string
         */
        public function getShopLight( $equip_id )
        {
            $now   = Carbon::today();
            $end_t = Carbon::tomorrow();
            $equip = Equipment::where( 'verify_code' , $equip_id )->where( 'valid' , '1' )->first();
            if ( $equip )
            {
                $num = $equip->belongsToShops->hasManyOrderWaiting
                    ->where( 'created_at' , '>=' , $now )
                    ->where( 'created_at' , '<=' , $end_t )
                    ->count();
                var_dump( '$num: ' . $num );
                if ( $num < 3 )
                {
                    return '0';
                }
                else if ( $num <= 5 )
                {
                    return '1';
                }
                else
                {
                    return '2';
                }
            }
            else
            {
                return '3';
            }
        }

        /**
         * 生成CRC16校验码
         *
         * @access public
         *
         * @param string $data 下位机指令代码
         *
         * @since 1.0
         * @return string
         * @todo 暂未加入到校验数据中
         */
        public function crc16_modbus( $data )
        {
            $crc = 0xFFFF;
            for ( $i = 0 ; $i < strlen( $data ) ; $i++ )
            {
                $crc ^= ord( $data[ $i ] );

                for ( $j = 8 ; $j != 0 ; $j-- )
                {
                    if ( ( $crc & 0x0001 ) != 0 )
                    {
                        $crc >>= 1;
                        $crc ^= 0xA001;
                    }
                    else $crc >>= 1;
                }
            }

            return sprintf( '%04X' , $crc );
        }
    }

    /**
     *                                   ┌─┐                ┌─┐
     *                            ┌──┘  ┴───────┘  ┴──┐
     *                            │                                       │
     *                            │                ───                │
     *                            │         ─┬┘       └┬─         │
     *                            │                                       │
     *                            │                ─┴─                │
     *                            │                                       │
     *                            └───┐                    ┌───┘
     *                                     │                    │
     *                                     │                    │
     *                                     │                    └──────────────┐
     *                                     │                                                       │
     *                                     │                                                       ├─┐
     *                                     │                                                       ┌─┘
     *                                     │                                                       │
     *                                     └─┐     ┐    ┌───────┬──┐    ┌──┘
     *                                          │  ─┤  ─┤                │  ─┤  ─┤
     *                                          └──┴──┘               └──┴──┘
     *
     *                                                    Protected By God
     *                                                            No Bug
     *                                                    Earn More Money
     */
