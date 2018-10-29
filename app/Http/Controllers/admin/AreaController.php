<?php

    namespace App\Http\Controllers\admin;

    use App\Models\Shop;
    use Speedy;
    use Illuminate\Http\Request;
    use App\Models\area;

    class AreaController extends BaseController
    {
        protected $permissionName = 'area';

        public function index()
        {
            $area = area::where( 'valid' , '1' )->orderBy( 'created_at' , 'DESC' )->get();

            return view( 'vendor.speedy.admin.area.index' , compact( 'area' ) );
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $shop = Speedy::getModelInstance( 'shop' )->where( 'valid' , '1' )->get();

            $users = Speedy::getModelInstance( 'user' )->where( 'role_id' , '3' )->where( 'valid' , '1' )->get();

            return view( 'vendor.speedy.admin.area.edit' , compact( 'shop' , 'users' ) );
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

            $validator = $this->mustValidate( 'area.store' );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }

            $area = Speedy::getModelInstance( 'area' )->create(
                [
                    'name'    => $request->get( 'name' ) ,
                    'user_id' => $request->get( 'user_id' ) ,
                ]
            );

            $area_id = area::orderby( 'created_at' , 'DESC' )->first()->id;

            if ( $request->get( 'shop_id' ) )
            {
                foreach ( $request->get( 'shop_id' ) as $id )
                    Speedy::getModelInstance( 'shop' )->where( 'id' , $id )->where( 'valid' , '1' )->update(
                        [
                            'area_id' => $area_id ,
                        ]
                    );
            }


            return $area ? redirect()->route( 'admin.area.index' ) : redirect()->back()->withErrors( trans( 'view.admin.area.create_area_failed' ) )->withInput();
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
            $area = Speedy::getModelInstance( 'area' )->where( 'id' , $id )->where( 'valid' , '1' )->first();

            $shop = Speedy::getModelInstance( 'shop' )->where( 'valid' , '1' )->get();

            $users = Speedy::getModelInstance( 'user' )->where( 'role_id' , '3' )->where( 'valid' , '1' )->get();

            return view( 'vendor.speedy.admin.area.edit' , compact( 'area' , 'users' , 'shop' ) );
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
            $validator = $this->mustValidate( 'area.update' , false , 'name' , $id );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }

            $payload = $request->all();

            $data = [ 'name' => $payload['name'] , 'user_id' => $payload['user_id'] ];

            //清空现有关联
            Shop::where( 'valid' , '1' )->where( 'area_id' , $id )->update(
                [
                    'area_id' => null ,
                ]
            );

            //重新绑定关联
            foreach ( $payload['shop_id'] as $shop_id )
                Speedy::getModelInstance( 'shop' )->find( $shop_id )->update( [ 'area_id' => $id ] );

            $result = Speedy::getModelInstance( 'area' )->find( $id )->update( $data );

            return $result ? redirect()->route( 'admin.area.index' ) : redirect()->back()->withErrors( trans( 'view.admin.user.edit_area_failed' ) )->withInput();
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
            Speedy::getModelInstance( 'shop' )->where( 'area_id' , $id )->update(
                [
                    'area_id' => null ,
                ]
            );
            $result = Speedy::getModelInstance( 'area' )->where( 'id' , $id )->update(
                [
                    'valid' => '0' ,
                ]
            );


            return $result ? redirect()->route( 'admin.area.index' ) : redirect()->back()->withErrors( trans( 'view.admin.area.delete_area_failed' ) )->withInput();
        }

    }
