<?php

    namespace App\Http\Controllers;

    use Illuminate\Routing\Controller as BaseController;
    use Carbon\Carbon;
    use App\Models\Orders;

    class TestController extends BaseController
    {
       public function index()
       {
           $nowTime = Carbon::createFromTime('13','37','00')->toDateTimeString();
           $start = Carbon::today()->toDateTimeString();
           $middle = Carbon::createFromTime('12' , '00' , '00')->toDateTimeString();
           $end = Carbon::createFromTime('23' , '59' , '59')->toDateTimeString();
           var_dump($nowTime.'<br>');
           var_dump($start.'<br>');
           var_dump($middle.'<br>');
           var_dump($end.'<br>');
           if ($nowTime > $start && $nowTime < $middle) {
               $number = Orders::whereBetween('pay_time' , [$start , $middle])->where('valid' , '1')->where('order_status' , 'PAID')->get();
           }else{
               $number = Orders::whereBetween('pay_time' , [$middle , $end])->where('valid' , '1')->where('order_status' , 'PAID')->get();
           }
           dd($number);
       }
    }
