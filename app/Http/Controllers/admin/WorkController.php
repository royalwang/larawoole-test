<?php

    namespace App\Http\Controllers\admin;

    use App\Models\Equipment;
    use App\Models\User;
    use Speedy;
    use Illuminate\Http\Request;
    use App\Models\Shop;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;

    class WorkController extends BaseController
    {

        protected $permissionName = 'work';

        /**
         * 门店当天工作情况列表
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $now     = Carbon::today();
            $end_t   = Carbon::tomorrow();
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
                default:
                    break;
            }

            foreach ( $shops as $shop )
            {
                $shop->setAttribute( 'doneOrders' ,
                    $shop->hasManyDoneOrder
                        ->where('created_at','>=',$now)
                        ->where( 'created_at' , '<=' , $end_t )
                        ->count() );
                $shop->setAttribute( 'todayProfit' ,
                    $shop->hasManyDoneOrder
                        ->where('created_at','>=',$now)
                        ->where( 'created_at' , '<=' , $end_t )
                        ->sum( 'net_amount' ) );
                $shop->setAttribute(
                    'waitingOrders' ,
                    $shop->hasManyOrderWaiting
                        ->where( 'created_at' , '>=' , $now )
                        ->where( 'created_at' , '<=' , $end_t )
                        ->count()
                );
                $shop->setAttribute('onWork',
                    Equipment::where('shops_id',$shop->id)
                    ->where('valid','1')
                    ->where('status','1')
                    ->count()
                );
            }

            return view( 'vendor.speedy.admin.work.index' , compact( 'shops' ) );
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
            $users        = User::where( 'shops_id' , '=' , $id )->where( 'valid' , '1' )->get();
            $now          = Carbon::today()->format( 'y-m' );
            $end_t        = Carbon::tomorrow();
            $this_month   = Carbon::today()->format( 'y-m' );
            $startOfMonth = Carbon::today()->startOfMonth();
            $endOfMonth   = Carbon::today()->endOfMonth();

            foreach ( $users as $user )
            {
                $status    = '';
                $workday   = '';
                $now_order = '';
                if ( $user->hasOneEquipment == null )
                {
                    $status = '0';
                }
                else
                {
                    switch ( $user->hasOneEquipment->status )
                    {
                        case ( '1' ):
                            $status = '1';
                            break;
                        case ( '4' ):
                            $status = '1';
                            break;
                    }
                }

                $user->setAttribute( 'status' , $status );

                if ( $user->hasManyOrders != null )
                {
                    $todayOrders = $user->hasManyOrders->where( 'status' , '2' )->where( 'finish_time' , '>=' , $now )->where( 'finish_time' , '<=' , $end_t );
                    $monthOrders = $user->hasManyOrders->where( 'status' , '2' )->where( 'finish_time' , '>=' , $startOfMonth )->where( 'finish_time' , '<=' , $endOfMonth );
                    $user->setAttribute( 'todayOrders' , $todayOrders->count() );
                    $user->setAttribute( 'monthOrders' , $monthOrders->count() );
                }
                else
                {
                    $user->setAttribute( 'todayOrders' , '0' );
                    $user->setAttribute( 'monthOrders' , '0' );
                }

                if ( $user->hasManyWorkAttendance != null )
                {
                    foreach ( $user->hasManyWorkAttendance as $v )
                    {
                        if ( $v->count_date == $this_month )
                        {
                            $workday = $v->days;
                            $user->setAttribute( 'workday' , $workday );
                            break;
                        }
                    }
                }
                else
                {
                    $user->setAttribute( 'workday' , '0' );
                }
            }

            return view( 'vendor.speedy.admin.work.show' , compact( 'users' ) );
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
    }
