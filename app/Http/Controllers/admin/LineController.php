<?php

    namespace App\Http\Controllers\admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use App\Models\Orders;
    use App\Models\Shop;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;

    class LineController extends BaseController
    {

        protected $permissionName = 'line';

        /**
         * 营销报表列表
         *
         * @access public
         * @since 1.0
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $nowUser    = Auth::user();
            $start_date = '';
            $end_date   = '';
            $shop_id    = $nowUser->shops_id;

            switch ( $nowUser->role_id )
            {
                case ( 1 ):
                    $shops = Shop::where( 'valid' , '1' )->paginate( 10 );
                    break;
                case ( 2 ):
                    $shops = Shop::where( 'valid' , '1' )->paginate( 10 );
                    break;
                case ( 3 ):
                    $area  = $nowUser->belongsToArea ? $nowUser->belongsToArea->id : null;
                    $shops = Shop::where( 'valid' , '1' )->where( 'area_id' , '=' , $area )->paginate( 10 );
                    break;
                case ( 4 ):
                    $shops = Shop::where( 'valid' , '1' )->where( 'id' , '=' , $shop_id )->paginate( 10 );
                    break;
                case ( 5 ):
                    break;
                case ( 6 ):
                    $teacher = Teacher::where( 'valid' , '1' )->where( 'user_id' , $nowUser->id )->first();
                    $shops   = Shop::where( 'valid' , '1' )->where( 'teacher_id' , $teacher->id )->paginate( 10 );
                    break;
                default:
                    break;
            }

            foreach ( $shops as $shop )
            {
                $shop->setAttribute( 'doneOrders' , $shop->hasManyDoneOrder->count() );
                $shop->setAttribute( 'totalProfit' , $shop->hasManyDoneOrder->sum( 'net_amount' ) );
            }

            return view( 'vendor.speedy.admin.line.index' , compact( 'shops' , 'start_date' , 'end_date' ) );
        }

        /**
         * 营销报表日期查询
         *
         * @access public
         * @since 1.0
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function search( Request $request )
        {
            $start_time = Carbon::create(
                substr( $request->get( 'start_datetime' ) , 0 , 4 ) ,
                substr( $request->get( 'start_datetime' ) , 5 , 2 ) ,
                substr( $request->get( 'start_datetime' ) , 8 , 2 ) , '0' , '0' , '0'
            );
            $end_time   = Carbon::create(
                substr( $request->get( 'end_datetime' ) , 0 , 4 ) ,
                substr( $request->get( 'end_datetime' ) , 5 , 2 ) ,
                substr( $request->get( 'end_datetime' ) , 8 , 2 ) , '0' , '0' , '0'
            );

            if ( $request->get( 'view' ) == 'index' )
            {
                $nowUser = Auth::user();
                $shop_id = $nowUser->shops_id;

                switch ( $nowUser->role_id )
                {
                    case ( 1 ):
                        $shops = Shop::where( 'valid' , '1' )->paginate( 10 );
                        break;
                    case ( 2 ):
                        $shops = Shop::where( 'valid' , '1' )->paginate( 10 );
                        break;
                    case ( 3 ):
                        $area  = $nowUser->belongsToArea->id;
                        $shops = Shop::where( 'valid' , '1' )->where( 'area_id' , '=' , $area )->paginate( 10 );
                        break;
                    case ( 4 ):
                        $shops = Shop::where( 'valid' , '1' )->where( 'id' , '=' , $shop_id )->paginate( 10 );
                        break;
                    case ( 5 ):
                        break;
                    case ( 6 ):
                        $teacher = Teacher::where( 'valid' , '1' )->where( 'user_id' , $nowUser->id )->first();
                        $shops   = Shop::where( 'valid' , '1' )->where( 'teacher_id' , $teacher->id )->paginate( 10 );
                        break;
                    default:
                        break;
                }

                foreach ( $shops as $shop )
                {
                    $shop->setAttribute(
                        'doneOrders' ,
                        $shop->hasManyDoneOrder
                            ->where( 'finish_time' , '>=' , $start_time )
                            ->where( 'finish_time' , '<=' , $end_time )
                            ->count()
                    );
                    $shop->setAttribute(
                        'totalProfit' ,
                        $shop->hasManyDoneOrder
                            ->where( 'finish_time' , '>=' , $start_time )
                            ->where( 'finish_time' , '<=' , $end_time )
                            ->sum( 'net_amount' )
                    );

                }

                $start_date = $start_time->toDateString();
                $end_date   = $end_time->toDateString();

                return view( 'vendor.speedy.admin.line.index' , compact( 'shops' , 'start_date' , 'end_date' ) );
            }
            else
            {
                $users = User::where( 'shops_id' , $request->get( 'shop' ) )
                    ->where( 'valid' , '1' )
                    ->paginate( 10 );
                foreach ( $users as $a )
                {
                    $orders = Orders::where( 'user_id' , $a->id )
                        ->where( 'valid' , '1' )
                        ->where( 'status' , '2' )
                        ->where( 'finish_time' , '>=' , $start_time )
                        ->where( 'finish_time' , '<=' , $end_time )
                        ->get();
                    $a->setAttribute( 'totalOrders' , $orders->count() );
                    $a->setAttribute( 'totalProfit' , $orders->sum( 'net_amount' ) );
                    if ( $orders->count() > 0 )
                    {
                        $efficiency = 0;
                        foreach ( $orders as $v )
                        {
                            $s          = Carbon::createFromTimeString( $v->start_handle );
                            $e          = Carbon::createFromTimeString( $v->finish_time );
                            $dif        = $s->diffInSeconds( $e );
                            $efficiency = $efficiency + $dif;
                        }
                        $a->setAttribute( 'efficiency' , intval( $efficiency / $orders->count() ) );
                    }
                    else
                    {
                        $a->setAttribute( 'efficiency' , 0 );
                    }
                }

                $start_date = $start_time->toDateString();
                $end_date   = $end_time->toDateString();

                return view( 'vendor.speedy.admin.line.detail' , compact( 'users' , 'start_date' , 'end_date' ) );
            }
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store( Request $request )
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param  int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function show( $id )
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function edit( $id )
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function update( Request $request , $id )
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy( $id )
        {
            //
        }

        /**
         * 营销详细门店营业情况
         *
         * @access public
         * @since 1.0
         *
         * @param \Illuminate\Http\Request $request
         * @todo 详情页重新搜索时间结果错误
         * @return \Illuminate\Http\Response
         */
        public function detail( Request $request )
        {
            $start_time = $request->get( 'start_date' );
            $end_time   = $request->get( 'end_date' );

            $users = User::where( 'shops_id' , $request->get( 'id' ) )
                ->where( 'valid' , '1' )
                ->paginate( 10 );
            foreach ( $users as $a )
            {
                $orders = Orders::where( 'user_id' , $a->id )
                    ->where( 'valid' , '1' )
                    ->where( 'status' , '2' )
                    ->where( 'finish_time' , '>=' , $start_time )
                    ->where( 'finish_time' , '<=' , $end_time )
                    ->get();
                $a->setAttribute( 'totalOrders' , $orders->count() );
                $a->setAttribute( 'totalProfit' , $orders->sum( 'net_amount' ) );
                if ( $orders->count() > 0 )
                {
                    $efficiency = 0;
                    foreach ( $orders as $v )
                    {
                        $s          = Carbon::createFromTimeString( $v->start_handle );
                        $e          = Carbon::createFromTimeString( $v->finish_time );
                        $dif        = $s->diffInSeconds( $e );
                        $efficiency = $efficiency + $dif;
                    }
                    $a->setAttribute( 'efficiency' , intval( $efficiency / $orders->count() ) );
                }
                else
                {
                    $a->setAttribute( 'efficiency' , 0 );
                }
            }
            $start_date = substr( $start_time , 0 , 10 );
            $end_date   = substr( $end_time , 0 , 10 );

            return view( 'vendor.speedy.admin.line.detail' , compact( 'users' , 'start_date' , 'end_date' ) );
        }
    }
