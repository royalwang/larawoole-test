<?php

    namespace App\Http\Controllers\admin;

    use Speedy;
    use Illuminate\Http\Request;
    use App\Models\Shop;

    class ShopsController extends BaseController
    {

        protected $permissionName = 'shop';

        public function index()
        {
            $shops = Shop::where( 'valid' , '1' )->paginate( 10 );

            return view( 'vendor.speedy.admin.shop.index' , compact( 'shops' ) );
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $users = Speedy::getModelInstance( 'user' )->where( 'valid' , '1' )->where( 'role_id' , '4' )->get();

            $cities = Speedy::getModelInstance( 'city_code' )->where( 'valid' , '1' )->get();

            return view( 'vendor.speedy.admin.shop.edit' , compact( 'users' , 'cities' ) );
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

            $validator = $this->mustValidate( 'shop.store' );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }

            $result = Speedy::getModelInstance( 'shop' )->create(
                [
                    'name'           => $request->get( 'name' ) ,
                    'address'        => $request->get( 'address' ) ,
                    'city_code'      => $request->get( 'city' ) ,
                    'establish_time' => $request->get( 'establish_time' ) ,
                    'manager_id'     => $request->get( 'manager_id' ) ,
                    'discount'       => substr( sprintf( "%.2f" , $request->get( 'discount' ) ) , 0 , -1 ) * 100 ,
                ]
            );

            if ( $result && $request->get( 'manager_id' ) )
            {
                $shop = Speedy::getModelInstance( 'shop' )->where( 'name' , $request->get( 'name' ) )->first();
                Speedy::getModelInstance( 'user' )->where( 'id' , $request->get( 'manager_id' ) )->update(
                    [
                        'shops_id' => $shop->id ,
                    ]
                );
            }

            return $result ? redirect()->route( 'admin.shop.index' ) : redirect()->back()->withErrors( trans( 'view.admin.shop.create_shop_failed' ) )->withInput();
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
            $shop = Speedy::getModelInstance( 'shop' )->find( $id );

            $users = Speedy::getModelInstance( 'user' )->where( 'valid' , '1' )->where( 'role_id' , '4' )->get();

            $cities = Speedy::getModelInstance( 'city_code' )->where( 'valid' , '1' )->get();

            return view( 'vendor.speedy.admin.shop.edit' , compact( 'shop' , 'users' , 'cities' ) );
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
            $validator = $this->mustValidate( 'shop.update' , false , 'name' , $id );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }

            $payload = $request->all();

            $data = [
                'name'           => $payload['name'] ,
                'address'        => $payload['address'] ,
                'establish_time' => $payload['establish_time'] ,
                'manager_id'     => $payload['manager_id'] ,
                'discount'       => substr( sprintf( "%.2f" , $payload['discount'] ) , 0 , -1 ) * 100 ,
            ];

            $result = Speedy::getModelInstance( 'shop' )->find( $id )->update( $data );

            if ( $result && $request->get( 'manager_id' ) )
            {
                $shop = Speedy::getModelInstance( 'shop' )->where( 'id' , $id )->first();
                Speedy::getModelInstance( 'user' )->where( 'id' , $request->get( 'manager_id' ) )->update(
                    [
                        'shops_id' => $shop->id ,
                    ]
                );
            }

            return $result ? redirect()->route( 'admin.shop.index' ) : redirect()->back()->withErrors( trans( 'view.admin.user.edit_shop_failed' ) )->withInput();
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
            $result = Speedy::getModelInstance( 'shop' )->where( 'id' , $id )->update(
                [
                    'valid' => '0' ,
                ]
            );

            return $result ? redirect()->route( 'admin.shop.index' ) : redirect()->back()->withErrors( trans( 'view.admin.shop.delete_shop_failed' ) )->withInput();
        }
    }
