<?php

    namespace App\Http\Controllers\Pay;

    use App\Models\Orders;
    use App\Models\PayConfig;
    use App\Models\ShopMachineConfigRel;
    use App\Models\TerminalInfo;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Speedy;


    class shouqianbaController extends Controller
    {

        public function __construct(Request $request)
        {
            //
        }

        public function index(Request $request)
        {
            //$machine_id = $request->machine_id;
            //$dynamic_id = $request->dynamic_id;
            $subject = config('shouqianba.subject');
            $operator = config('shouqianba.operator');
            $device_id = config('shouqianba.device_id');
            $machine_id = '9001805001';
            $shop_id = '361583105a1a48de2326c85c6c442e39';
            $dynamic_id = '135715308782974947';

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
            //有历史签到信息
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
            $rel = ShopMachineConfigRel::where('machine_sn' , $machine_id)->where('valid' , '1')->first();
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
         * 发情查询订单请求
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
                $order = $this->createOrder($rdata , $shop_id);

                while ($order->order_status != 'PAID' && $order->order_status != 'PAY_CANCELED' && $order->order_status != 'CANCELED') {
                    sleep(5);
                    $response = $this->payQuery($rdata->terminal_sn , $rdata->sn , $rdata->client_sn , $terminal_key);
                    $result = json_decode($response);
                    $rdata = $result->biz_response->data;
                    $order = $this->updateOrder($rdata);
                    if ($result->biz_response == 'PAY_FAIL') {
                        break;
                    }
                }
                if ($order->order_status != 'PAID' && $order->order_status != 'PAY_CANCELED' && $order->order_status != 'CANCELED') {
                    $response = $this->payCancel($rdata->terminal_sn , $terminal_key , $rdata->client_sn);
                    $result = json_decode($response);
                    $rdata = $result->biz_response->data;
                    if ($result->result_code == 200) {
                        return $this->updateOrder($rdata);
                    } else {
                        return '支付通讯失败';
                    }
                }

                return $order;
            } else {
                return '支付通讯失败';
            }
        }

        /*
         * 创建订单信息
         * */
        public function createOrder($rdata , $shop_id)
        {
            if ($rdata->order_status == 'PAID') {
                $nowTime = Carbon::now()->toTimeString();
                $start = Carbon::today()->toDateTimeString();
                $middle = Carbon::createFromTime('12' , '00' , '00')->toDateTimeString();
                $end = Carbon::createFromTime('23' , '59' , '59')->toDateTimeString();

                if ($nowTime > $start && $nowTime < $middle) {

                    //打印上午排队号
                    $number = Orders::whereBetween('pay_time' , [$start , $middle])->where('valid' , '1')->where('order_status' , 'PAID')->count();
                    if ($number < 9) {
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
                            'pay_time'        => Carbon::createFromTimestamp($rdata->channel_finish_time) ,
                            'wait_num'        => 'A0' . ($number + 1) ,
                        ]);
                    } else {
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
                            'pay_time'        => Carbon::createFromTimestamp($rdata->channel_finish_time) ,
                            'wait_num'        => 'A' . ($number + 1) ,
                        ]);
                    }
                } else {
                    $number = Orders::whereBetween('pay_time' , [$middle , $end])->where('valid' , '1')->where('order_status' , 'PAID')->count();
                    if ($number < 9) {
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
                            'pay_time'        => Carbon::createFromTimestamp($rdata->channel_finish_time) ,
                            'wait_num'        => 'P0' . ($number + 1) ,
                        ]);
                    } else {
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
                            'pay_time'        => Carbon::createFromTimestamp($rdata->channel_finish_time) ,
                            'wait_num'        => 'P' . ($number + 1) ,
                        ]);
                    }
                }
            } else {
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
            }

            echo '创建了订单';

            return $order;
        }

        /*
         * 更新订单信息
         * */
        public function updateOrder($rdata)
        {
            if ($rdata->order_status == 'PAID') {
                $nowTime = Carbon::now()->toTimeString();
                $start = Carbon::today()->toDateTimeString();
                $middle = Carbon::createFromTime('12' , '00' , '00')->toDateTimeString();
                $end = Carbon::createFromTime('23' , '59' , '59')->toDateTimeString();

                if ($nowTime > $start && $nowTime < $middle) {

                    //打印上午排队号
                    $number = Orders::whereBetween('pay_time' , [$start , $middle])->where('valid' , '1')->where('order_status' , 'PAID')->count();
                    if ($number < 9) {
                        $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                            'order_status' => $rdata->order_status ,
                            'wait_num'     => 'A0' . ($number + 1) ,
                        ]);
                    } else {
                        $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                            'order_status' => $rdata->order_status ,
                            'wait_num'     => 'A' . ($number + 1) ,
                        ]);
                    }
                } else {
                    //打印下午排队号
                    $number = Orders::whereBetween('pay_time' , [$middle , $end])->where('valid' , '1')->where('order_status' , 'PAID')->count();
                    if ($number < 9) {
                        $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                            'order_status' => $rdata->order_status ,
                            'wait_num'     => 'P0' . ($number + 1) ,
                        ]);
                    } else {
                        $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                            'order_status' => $rdata->order_status ,
                            'wait_num'     => 'P' . ($number + 1) ,
                        ]);
                    }

                }
            } else {
                $order = Speedy::getModelInstance('order')->where('order_num' , $rdata->client_sn)->update([
                    'order_status' => $rdata->order_status ,
                ]);
            }

            echo '更新了订单';

            return $order;
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
