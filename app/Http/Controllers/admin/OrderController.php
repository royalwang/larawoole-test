<?php

    namespace App\Http\Controllers\admin;

    use Speedy;
    use Illuminate\Http\Request;
    use App\Models\area;
    use App\Models\Orders;
    use Auth;

    class OrderController extends BaseController
    {
        protected $permissionName = 'order';

        public function index()
        {
            $nowUser = Auth::user();
            switch ( $nowUser->role_id )
            {
                case ( '1' ):
                    $orders = Speedy::getModelInstance( 'order' )
                        ->where( 'valid' , '1' )
                        ->orderBy( 'created_at' , 'DESC' )
                        ->paginate( 10 );
                    break;
                case ( '2' ):
                    $orders = Speedy::getModelInstance( 'order' )
                        ->where( 'valid' , '1' )
                        ->orderBy( 'created_at' , 'DESC' )
                        ->paginate( 10 );
                    break;
                case ( '3' ):
                    $area = area::where( 'valid' , '1' )->where( 'user_id' , $nowUser->id )->first();
                    if ( $area )
                    {
                        $shop  = Shop::where( 'valid' , '1' )->where( 'area_id' , $area->id )->get();
                        $shops = [];
                        foreach ( $shop as $v )
                        {
                            array_push( $shops , $v->id );
                        }
                        $shops  = array_unique( $shops );
                        $orders = Speedy::getModelInstance( 'order' )
                            ->where( 'valid' , '1' )
                            ->whereIn( 'shops_id' , $shops )
                            ->orderBy( 'created_at' , 'DESC' )
                            ->paginate( 10 );
                    }
                    else
                    {
                        $orders = null;
                    }
                    break;
                case ( '4' ):
                    $orders = Speedy::getModelInstance( 'order' )
                        ->where( 'valid' , '1' )
                        ->where( 'shops_id' , $nowUser->shops_id )
                        ->orderBy( 'created_at' , 'DESC' )
                        ->paginate( 10 );
                    break;
                case ( '6' ):
                    $teacher = Teacher::where( 'valid' , '1' )->where( 'user_id' , $nowUser->id )->first();
                    $shop    = Shop::where( 'valid' , '1' )->where( 'teacher_id' , $teacher->id )->get();
                    $shops   = [];
                    foreach ( $shop as $v )
                    {
                        array_push( $shops , $v->id );
                    }
                    $shops  = array_unique( $shops );
                    $orders = Speedy::getModelInstance( 'order' )
                        ->where( 'valid' , '1' )
                        ->whereIn( 'shops_id' , $shops )
                        ->orderBy( 'created_at' , 'DESC' )
                        ->paginate( 10 );
                    break;
            }

            return view( 'vendor.speedy.admin.order.index' , compact( 'orders' ) );
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
            $order = Speedy::getModelInstance( 'order' )->find( $id );

            return view( 'vendor.speedy.admin.order.edit' , compact( 'order' ) );

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
