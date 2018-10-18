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
        public function __construct()
        {
            //
        }


        /*
         * 打印日志
         * */
        public function printLog($sig , $serv = '' , $fd = '' , $from_id = '' , $data = '')
        {
            switch ($sig) {
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

        /*
         * 服务器启动
         * */
        public function onStart()
        {
            $this->printLog('start');
        }

        /*
         * 服务器连接
         * */
        public function onConnect($serv , $fd , $from_id)
        {
            $this->printLog('connect' , $serv , $fd , $from_id);
            $this->sendMsg($serv , $fd , '200');
        }

        /*
         * 服务器接收数据
         * */
        public function onReceive($serv = '' , $fd = '' , $from_id = '' , $data = '')
        {
            $data = '$0037539001805001,135749202635770047\r';

            //心跳包不打印日志
            if (substr($data , 5 , 2) != '08') {
                $this->printLog('receive' , $serv , $fd , $from_id , $data);
            }
            if ($this->verifyData($data) == '555' || $this->verifyData($data) == '999') {
                $this->sendMsg($serv , $fd , $this->verifyData($data));
            } else {
                $this->sendMsg($serv , $fd , $this->proceedData($data));
            }
        }

        /*
         * 服务器关闭
         * */
        public function onClose($serv , $fd , $from_id)
        {
            $this->printLog('close' , $serv , $fd , $from_id);
        }

        /*
         * 编码信息表
         * */
        public function message($code , $type)
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
            ];
            if ($type == 0) {
                return $status[ $code ];
            } else {
                return $code;
            }
        }

        /*
         * 服务器向下位机发送消息
         * */
        public function sendMsg($serv , $fd , $data)
        {
            $len = strlen($data) + 6;
            if (strlen($len) == 1) {
                $sendData = '%' . '000' . ($len) . $data;
            } elseif (strlen($len) == 2) {
                $sendData = '%' . '00' . ($len) . $data;
            } elseif (strlen($len) == 3) {
                $sendData = '%' . '0' . ($len) . $data;
            } else {
                $sendData = '%' . ($len) . $data;
            }

            //$crc = $this->crc16_modbus(substr($sendData,1,-1)); //读取CRC16校验码

            $sendData .= '\r';//数据结尾
            dd($sendData);
            $serv->send($fd , $this->message($sendData , '1'));
        }

        /*
         * 校验发送信息正确性
         * */
        public function verifyData($data)
        {
            //校验起始符号
            if ($data[0] == '$') {
                //校验数据长度
                if ((strlen($data) - 1) == intval(substr($data , 1 , 4))) {
                    //校验CRC16校验码
                    //$crc = $this->crc16_modbus(substr($data , 1 , -4));
                    //if ($crc != substr($data , -4 , 4)) {
                    //return '999';
                    //}
                    return '666';
                } else {
                    return '555';
                }
            }
        }

        /*
         * 生成CRC16校验码
         * */
        public function crc16_modbus($data)
        {
            $crc = 0xFFFF;
            for ($i = 0 ; $i < strlen($data) ; $i++) {
                $crc ^= ord($data[ $i ]);

                for ($j = 8 ; $j != 0 ; $j--) {
                    if (($crc & 0x0001) != 0) {
                        $crc >>= 1;
                        $crc ^= 0xA001;
                    } else $crc >>= 1;
                }
            }

            return sprintf('%04X' , $crc);
        }

        /*
         * 处理业务逻辑
         * */
        public function proceedData($data)
        {
            switch (substr($data , 5 , 2)) {
                //机器入网请求
                case ('01'):
                    $str = explode('\\' , $data);
                    $equip = Equipment::where('verify_code' , substr($str[0] , 7))->first();
                    $time = Carbon::now();
                    if ($equip) {
                        if ($equip->status != '5') {
                            return '010' . substr($time , 0 , 19);//可以接入
                        } else {
                            return '011';//故障不能接入
                        }
                    } else {
                        return '012';//没有注册设备
                    }
                    break;

                //员工登入
                case ('02'):

                    $str = explode(',' , $data);
                    $equip = Equipment::where('verify_code' , substr($str[0] , 7))->first();
                    $user = User::where('work_id' , $str[1])->first();

                    if ( ! $user) {

                        return '022';//无此员工

                    } //elseif ($equip->work_id) {

                    //return '023';//员工机已被其他人使用}

                    elseif (Hash::check(substr($str[2] , 0 , 6) , $user->password)) {

                        $equip->update(['user_id' => $user->id , 'status' => '1']);
                        Speedy::getModelInstance('machine_login_logout_record')
                            ->create([
                                'user_id'     => $user->id ,
                                'verify_code' => $equip->verify_code ,
                                'type'        => '0' ,
                                'status'      => '0' ,
                            ]);

                        return '020';//登录成功

                    } else {

                        return '021';//登录失败

                    }
                    break;

                //员工登出
                case ('03'):

                    $str = explode(',' , $data);
                    $equip = Equipment::where('verify_code' , substr($str[0] , 7))->first();
                    $user = User::where('work_id' , $str[1])->first();

                    if (Hash::check($str[2] , $user->password)) {
                        if ($user->role_id == '5' && $user->id == $equip->user_id) {

                            $equip->update(['user_id' => null]);
                            Speedy::getModelInstance('machine_login_logout_record')
                                ->create([
                                    'user_id'     => $user->id ,
                                    'verify_code' => $equip->verify_code ,
                                    'type'        => '1' ,
                                    'status'      => '0' ,
                                ]);

                            return '030';//员工登出成功

                        } elseif ($user->role_id != '5') {
                            $equip->update(['user_id' => null]);
                            Speedy::getModelInstance('machine_login_logout_record')
                                ->create([
                                    'user_id'     => $user->id ,
                                    'verify_code' => $equip->verify_code ,
                                    'type'        => '1' ,
                                    'status'      => '0' ,
                                ]);

                            return '031';//管理员强制登出员工账号成功

                        } else {

                            return '032';

                        }
                    } else {
                        return '032';//登出失败
                    }
                    break;

                //员工暂停服务、恢复服务
                case ('04'):

                    $str = explode(',' , $data);
                    $equip = Equipment::where('verify_code' , substr($str[0] , 7))->first();
                    $user = User::where('work_id' , $str[1])->first();

                    if ($str[2][0] == '0') {
                        if ($equip->user_id == $user->id && $equip->status == '1') {

                            $equip->update(['status' => '4']);

                            return '040';//员工机操作成功

                        } else {

                            return '041';//员工机操作失败

                        }
                    } else {
                        if ($equip->user_id == $user->id && $equip->status == '4') {
                            $equip->update(['status' => '1']);

                            return '040';
                        } else {
                            return '041';
                        }
                    }
                    break;

                //接单开始
                case ('05'):

                    $str = explode(',' , $data);
                    $equip = Equipment::where('verify_code' , substr($str[0] , 7))->first();
                    $order_num = explode('\\' , $str[ (2) ]);
                    $order = Orders::where('order_num' , $order_num[0])->first();
                    $user = User::where('work_id' , $str[1])->first();

                    if ( ! $order) {

                        return '052';//订单错误或不存在

                    } elseif ($order->finish_time) {

                        return '051';//订单已过期

                    } else {

                        $order->update(['status' => '1' , 'user_id' => $user->id , 'equipment_id' => $equip->id]);

                        return '050';//订单操作开始

                    }
                    break;

                //接单结束
                case ('06'):

                    $str = explode(',' , $data);
                    $order = Orders::where('order_num' , $str[2])->first();

                    if ($order) {

                        //$order->update(['status' => '2' , 'sex' => substr($str[3] , 0 , 1) , 'age' => substr($str[3] , 1 , 2)
                        //, 'handle_time'      => intval(substr($str[3] , 0 , 2)) * 60 , 'finish_time' => Carbon::now()]);
                        $order->update(['status' => '2' , 'sex' => substr($str[3] , 0 , 1) , 'age' => substr($str[3] , 1 , 1)
                            , 'handle_time'      => intval(substr($str[4] , 0 , 2)) * 60]);

                        return '060';//订单操作成功

                    } else {

                        return '061';//订单操作失败

                    }
                    break;

                //员工机心跳包
                case ('08'):
                    return '080';
                    break;

                /*
                 * 收钱机指令开始
                 * 机器入网请求
                 * */
                case ('50'):
                    $str = explode('\\' , $data);
                    $machine_sn = substr($str[0] , 7);
                    $time = substr(Carbon::now() , 0 , 19);

                    $equip = Equipment::where('verify_code' , $machine_sn)->where('valid' , '1')->where('type' , '1')->first();
                    if ( ! $equip) {
                        return '502';
                    } elseif ($equip->status == '5') {
                        return '501';
                    } else {
                        return '500' . $time;
                    }

                    break;

                //收银机心跳包
                case ('51'):
                    $now = Carbon::now()->toTimeString();
                    $start = Carbon::createFromTime('07' , '00' , '00')->toTimeString();
                    $end = Carbon::createFromTime('23' , '59' , '59')->toTimeString();
                    if ($now > $start && $now < $end) {
                        return '510';
                    } else {
                        return '511';
                    }
                    break;

                //询问当天收款价格
                case ('52'):
                    $str = explode('\\' , $data);
                    $machine_sn = substr($str[0] , 7);
                    $shop = Shop::where('machine_sn' , $machine_sn)->where('valid' , '1')->first();
                    if ($shop) {
                        return '520' . ($shop->discount) / 10;
                    } else {
                        return '521';
                    }
                    break;

                //收款请求
                case ('53'):
                    $str = explode(',' , $data);
                    $machine_sn = substr($str[0] , 7);
                    $shop = Shop::where('machine_sn' , $machine_sn)->where('valid' , '1')->first();
                    $dynamic_id = substr(explode('\\' , $str[1])[0] , 0);

                    //支付逻辑开始
                    $response = $this->gotoPay($machine_sn , $dynamic_id , $shop->id);

                    if ($response)
                    {
                        if ($response->order_status == 'PAID')
                        {
                            return '530' . $response->wait_num . ',' . $response->order_num;
                        }else{
                            return '531';
                        }
                    }else{
                        return '531';
                    }

                    break;

                //小票打印成功返回命令
                case ('54'):
                    $str = explode(',' , $data);
                    $order_num = substr(explode('\\' , $str[1])[0] , 0);
                    $order = Orders::where('order_num' , $order_num)->where('valid' , '1')->update([
                        'if_get'   => '1' ,
                        'get_time' => Carbon::now()->toDateTimeString() ,
                    ]);
                    if ($order) {
                        return '540';
                    } else {
                        return '541';
                    }
                    break;

                //请求重新打印上一条订单信息
                case ('55'):
                    $str = explode(',' , $data);
                    $order_num = substr(explode('\\' , $str[1])[0] , 0);
                    $order = Orders::where('order_num' , $order_num)->where('valid' , '1')->update([
                        'if_get'   => '1' ,
                        'get_time' => Carbon::now()->toDateTimeString() ,
                    ]);
                    if ($order) {
                        return '550';
                    } else {
                        return '551';
                    }
                    break;
            }

            return '000';
        }


        /*
         * 支付开始
         * */
        public function gotoPay($machine_id , $dynamic_id , $shop_id)
        {
            $subject = config('shouqianba.subject');
            $operator = config('shouqianba.operator');
            $device_id = config('shouqianba.device_id');

            //判断终端是否已激活
            $terminal = $this->getActivateInfo($device_id);

            //判断是否需要签到
            $checkIn = $this->getCheckInInfo($terminal , $machine_id);

            //开始支付
            if ($checkIn) {
                $pay = $this->payCommit($checkIn->terminal_sn , $this->getOrderNum() , '1' , $dynamic_id , $subject , $operator , $checkIn->terminal_key , $shop_id);

                return $pay;
            } else {
                return 'pay error';
            }
        }

        /*
         * 获取激活信息
         * */
        public function getActivateInfo($machine_id)
        {
            $terminal = TerminalInfo::where('device_id' , $machine_id)->first();

            return $terminal;
        }

        /*
         * 获取签到信息
         * */
        public function getCheckInInfo($terminal , $machine_id)
        {
            //有历史签到信息，每日第一张单进行签到
            if ($terminal) {
                $isToday = $terminal->updated_at->isToday();
                if ($isToday) {

                    echo '当前时间无需签到，可直接使用';

                    return $terminal;

                } else {

                    $response = $this->payCheckIn($terminal->terminal_sn , $terminal->terminal_key);

                    $terminal = $this->updateCheckIn($response);

                    echo '当前时间需要重新签到，签到信息已更新<br>' . $terminal;

                    return $terminal;
                }
            } else {

                $terminal = $this->getVendorInfo($machine_id); //无历史签到信息,获取门店VENDOR信息

                return $terminal;
            }
        }

        /*
         * 更新签到信息
         * */
        public function updateCheckIn($response)
        {
            $result = json_decode($response);
            $terminal = '';

            if ($result->result_code == 200) {
                $result_data = $result->biz_response;

                TerminalInfo::where('device_id' , config('shouqianba.device_id'))
                    ->where('valid' , '1')
                    ->first()
                    ->update([
                        'terminal_sn'  => $result_data->terminal_sn ,
                        'terminal_key' => $result_data->terminal_key ,
                    ]);

                $terminal = TerminalInfo::where('device_id' , config('shouqianba.device_id'))->first();
            }

            return $terminal;
        }

        /*
         * 更新激活信息
         * */
        public function updateActivateInfo($response)
        {
            $result = json_decode($response);
            $terminal = '';

            if ($result->result_code == 200) {
                $result_data = $result->biz_response;
                $terminal = Speedy::getModelInstance('terminal_info')->create([
                    'terminal_sn'   => $result_data->terminal_sn ,
                    'terminal_key'  => $result_data->terminal_key ,
                    'merchant_sn'   => $result_data->merchant_sn ,
                    'merchant_name' => $result_data->merchant_name ,
                    'store_sn'      => $result_data->store_sn ,
                    'store_name'    => $result_data->store_name ,
                ]);
            }

            return $terminal;

        }

        /*
         * 获取Vendor信息
         * */
        public function getVendorInfo($machine_id)
        {
            $rel = ShopMachineConfigRel::where('machine_sn' , $machine_id)
                ->where('valid' , '1')
                ->first();

            $config = PayConfig::where('valid' , '1')
                ->where('id' , $rel->config_id)
                ->first();

            return $activate = $this->payActivate($config); //激活
        }

        /*
         * 发起签到请求
         * */
        public function payCheckIn($terminal_sn , $terminal_key)
        {
            $url = config('shouqianba.url.checkin');

            $data['terminal_sn'] = $terminal_sn;
            $data['device_id'] = config('shouqianba.device_id');
            $params = json_encode($data);

            $sign = $this->getSign($params . $terminal_key);

            $headers = [
                "Format:json" ,
                "Content-Type:application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            return $this->doCurlJsonRequest($url , $params , $headers);
        }

        /*
         * 发起激活请求
         * */
        public function payActivate($config)
        {
            $url = config('shouqianba.url.activate');
            $data = [
                'app_id'    => $config->app_id ,
                'code'      => $config->activate_code ,
                'device_id' => $config->app_id ,
            ];
            $param = json_encode($data);

            $sign = $this->getSign($param . $config->vendor_key);

            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$config->vendor_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest($url , $param , $headers);

            return $this->updateActivateInfo($response);
        }

        /*
         * 发起支付请求
         * */
        public function payCommit($terminal_sn , $order_num , $total_amount , $dynamic_id , $subject , $operator , $terminal_key , $shop_id)
        {
            $url = config('shouqianba.url.pay');

            $data['terminal_sn'] = $terminal_sn;
            $data['client_sn'] = $order_num;
            $data['total_amount'] = $total_amount;
            $data['dynamic_id'] = $dynamic_id;
            $data['subject'] = $subject;
            $data['operator'] = $operator;

            $params = json_encode($data);
            $sign = $this->getSign($params . $terminal_key);
            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest($url , $params , $headers);

            $pay = $this->handleOrder($response , $terminal_key , $shop_id);

            return $pay;
        }

        /*
         * 发起查询订单请求
         * */
        public function payQuery($terminal_sn , $sn , $client_sn , $terminal_key)
        {
            $url = config('shouqianba.url.query');

            $data['terminal_sn'] = $terminal_sn;
            $data['sn'] = $sn;
            $data['client_sn'] = $client_sn;

            $params = json_encode($data);

            $sign = $this->getSign($params . $terminal_key);

            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest($url , $params , $headers);

            echo '查询了订单';

            return $response;
        }

        /*
         * 发起撤销订单请求
         * */
        public function payCancel($terminal_sn , $terminal_key , $client_sn)
        {
            $url = config('shouqianba.url.cancel');

            $data['terminal_sn'] = $terminal_sn;
            $data['client_sn'] = $client_sn;

            $params = json_encode($data);

            $sign = $this->getSign($params . $terminal_key);

            $headers = [
                "Format:json" ,
                "Content-Type: application/json" ,
                "Authorization:$terminal_sn" . ' ' . $sign ,
            ];

            $response = $this->doCurlJsonRequest($url , $params , $headers);

            echo '撤销了订单';

            return $response;
        }

        /*
         * 获取签名
         * */
        public function getSign($signStr)
        {
            $md5 = Md5($signStr);

            return $md5;
        }

        /*
         * 处理订单业务逻辑
         * */
        public function handleOrder($response , $terminal_key , $shop_id)
        {
            $result = json_decode($response);
            $rdata = $result->biz_response->data;

            if ($result->result_code == 200) {

                //创建订单
                $this->createOrder($rdata , $shop_id);
                //检查订单状态
                return $this->checkOrderStatus($rdata , $terminal_key);
            } else {
                return '支付通讯失败';
            }
        }

        /*
         * 创建订单信息
         * */
        public function createOrder($rdata , $shop_id)
        {
            $order = Speedy::getModelInstance('order')->create([
                'order_num'       => $rdata->client_sn ,
                'pay_type'        => $rdata->payway ,
                'get_type'        => '0' ,
                'price'           => $rdata->total_amount ,
                'order_num_sn'    => $rdata->sn ,
                'order_num_trade' => $rdata->client_sn ,
                'order_status'    => $rdata->order_status ,
                'payer_uid'       => $rdata->payer_uid ,
                'payer_login'     => $rdata->payer_login ,
                'shops_id'        => $shop_id ,
            ]);

            echo '创建了订单';

            return $order;
        }

        /*
         * 更新订单信息
         * */
        public function updateOrder($rdata)
        {
            if ($rdata->order_status == 'PAID')
            {
                //获取已又排队号码
                $number = $this->getQueueNumber();
                $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                    'order_status'        => $rdata->order_status ,
                    'wait_num'            => $number ,
                    'channel_finish_time' => $rdata->channel_finish_time ,
                    'pay_time'            => Carbon::now()->toDateTimeString() ,
                ]);

            }else{

                $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                    'order_status' => $rdata->order_status ,
                ]);

            }

            echo '更新了订单';

            return $order;
        }

        /*
         * 校验订单支付状态
         * */
        public function checkOrderStatus($data , $terminal_key)
        {
            $order = '';
            switch ($data->order_status) {
                case 'PAID':

                    //已支付
                    $order = $this->updateOrder($data);
                    break;

                case 'PAY_CANCELED':

                    //支付被取消
                    $order =$this->updateOrder($data);
                    break;

                case 'CANCELED':

                    //支付被系统取消
                    $order =$this->updateOrder($data);
                    break;

                default:

                    //轮询订单状态，轮询总时长40s
                    for ($i = 0;$i<8;$i++)
                    {
                        sleep(5);
                        $response = $this->payQuery($data->terminal_sn , $data->sn , $data->client_sn , $terminal_key);
                        var_dump($response.'<br>');

                        if ($data->order_status == 'PAID' && $data->order_status == 'PAY_CANCELED' && $data->order_status == 'CANCELED')
                        {
                            $result = json_decode($response);
                            $rdata = $result->biz_response->data;
                            $order = $this->updateOrder($rdata);
                            break;
                        }
                    }
                    break;
            }

            return $order;
        }

        /*
         * 获取当前排队人数
         * */
        public function getQueueNumber()
        {
            $nowTime = Carbon::now()->toDateTimeString();
            $start = Carbon::today()->toDateTimeString();
            $middle = Carbon::createFromTime('12' , '00' , '00')->toDateTimeString();
            $end = Carbon::createFromTime('23' , '59' , '59')->toDateTimeString();

            if ($nowTime > $start && $nowTime < $middle) {

                //打印上午排队号
                $number = Orders::whereBetween('pay_time' , [$start , $middle])
                    ->where('valid' , '1')
                    ->where('order_status' , 'PAID')
                    ->count();
                $number < 9 ? $number = 'A0' . ($number + 1):$number = 'A' . ($number + 1);

            } else {

                //打印下午排队号
                $number = Orders::whereBetween('pay_time' , [$middle , $end])
                    ->where('valid' , '1')
                    ->where('order_status' , 'PAID')
                    ->count();
                $number < 9 ? $number = 'P0' . ($number + 1):$number = 'P' . ($number + 1);

            }
            return $number;
        }

        /*
         * 使用CURL发送JSON请求
         * */
        public function doCurlJsonRequest($url , $data , $headers)
        {

            $con = curl_init();

            curl_setopt($con , CURLOPT_SSL_VERIFYPEER , false); // 跳过证书检查
            curl_setopt($con , CURLOPT_SSL_VERIFYHOST , 2);  // 从证书中检查SSL加密算法是否存在
            curl_setopt($con , CURLOPT_URL , $url);
            curl_setopt($con , CURLOPT_HEADER , false);
            curl_setopt($con , CURLOPT_HTTPHEADER , $headers);
            curl_setopt($con , CURLOPT_RETURNTRANSFER , true);
            curl_setopt($con , CURLOPT_POST , true);
            curl_setopt($con , CURLOPT_POSTFIELDS , $data);
            curl_setopt($con , CURLOPT_FOLLOWLOCATION , 0);
            //curl_setopt($con , CURLOPT_TIMEOUT , (int) $timeout);

            $output = curl_exec($con);
            curl_close($con);

            return $output;
        }

        /*
         * 生成唯一15位订单号码
         * */
        public function getOrderNum()
        {
            $time = Carbon::now()->toDateTimeString();
            $order_num = '6' . random_int(0 , 9) . substr(str_replace(['-' , ':' , ':' , ' '] , '' , $time) , 2) . random_int(0 , 9);

            return $order_num;
        }


    }
