<?php

    namespace App\Http\Controllers\admin;

    use App\Models\Shop;
    use App\Models\Sp;
    use App\Models\Teacher;
    use Speedy;
    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    class UserController extends BaseController
    {

        protected $permissionName = 'user';

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $nowUser = Auth::user();
            $roleId  = $nowUser->role_id;
            switch ( $roleId )
            {
                case '1': //超级管理员
                    $users = User::where( 'valid' , '1' )
                        ->where( 'id' , '!=' , $nowUser->id )
                        ->orderby( 'role_id' , 'ASC' )->paginate( 10 );
                    break;
                case '2': //老板
                    $users = User::where( 'role_id' , '!=' , '1' )
                        ->where( 'role_id' , '!=' , '2' )
                        ->where( 'id' , '!=' , $nowUser->id )
                        ->where( 'valid' , '1' )
                        ->orderby( 'role_id' , 'ASC' )
                        ->paginate( 10 );
                    break;
                case '3': //区域经理
                    $area_id   = $nowUser->belongsToArea->id;
                    $shops     = Shop::where( 'area_id' , $area_id )->where( 'valid' , '1' )->get();
                    $teacherId = [];
                    $shopsId   = [];
                    foreach ( $shops as $v )
                    {
                        array_push( $shopsId , $v->id );
                        if ( $v->teacher_id != null )
                        {
                            array_push( $teacherId , $v->teacher_id );
                        }
                    }
                    $users = User::wherein( 'id' , $teacherId )
                        ->orwherein( 'shops_id' , $shopsId )
                        ->where( 'id' , '!=' , $nowUser->id )
                        ->where( 'valid' , '1' )
                        ->orderby( 'role_id' , 'DESC' )
                        ->paginate( 10 );
                    break;
                case '6': //导师
                    $shops   = Shop::where( 'teacher_id' , $nowUser->id )->get();
                    $shopsId = [];
                    foreach ( $shops as $v )
                    {
                        array_push( $shopsId , $v->id );
                    }
                    $users = User::whereIn( 'shops_id' , $shopsId )
                        ->where( 'valid' , '1' )
                        ->where( 'id' , '!=' , $nowUser->id )
                        ->orderby( 'role_id' , 'ASC' )
                        ->paginate( 10 );
                    break;
                case '4': //店长
                    $users = User::where( 'role_id' , '=' , '5' )
                        ->where( 'shops_id' , '=' , $nowUser->shops_id )
                        ->where( 'id' , '!=' , $nowUser->id )
                        ->where( 'valid' , '1' )
                        ->orderby( 'role_id' , 'DESC' )
                        ->paginate( 10 );
                    break;
                default:
                    $users = User::where( 'valid' , '1' )->paginate( 10 );
                    break;
            }

            return view( 'vendor.speedy.admin.user.index' , compact( 'users' ) );
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $nowUser = Auth::user();
            $shops   = Speedy::getModelInstance( 'shop' )->where( 'valid' , '1' )->get();

            switch ( $nowUser->role_id )
            {
                case '1':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '2':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '3':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'id' , '!=' , '2' )
                        ->where( 'id' , '!=' , '3' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '4':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'id' , '!=' , '2' )
                        ->where( 'id' , '!=' , '3' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '6':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'id' , '!=' , '2' )
                        ->where( 'id' , '!=' , '6' )
                        ->where( 'id' , '!=' , '3' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                default:
                    $roles = Speedy::getModelInstance( 'role' )
                        ->all();
                    break;
            }

            return view( 'vendor.speedy.admin.user.edit' , compact( 'roles' , 'shops' ) );
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
            $nowUser = Auth::user();

            $validator = $this->mustValidate( 'user.store' );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }

            switch ( $nowUser->role_id )
            {
                case ( '1' ):
                    $user = Speedy::getModelInstance( 'user' )->create(
                        [
                            'name'         => $request->get( 'name' ) ,
                            'display_name' => $request->get( 'display_name' ) ,
                            'email'        => $request->get( 'email' ) ,
                            'password'     => bcrypt( $request->get( 'password' ) ) ,
                            'role_id'      => $request->get( 'role_id' ) ,
                            'work_id'      => $request->get( 'work_id' ) ,
                        ]
                    );
                    break;
                case ( '2' ):
                    if ( $request->get( 'role_id' ) != '1' )
                    {
                        $user = Speedy::getModelInstance( 'user' )->create(
                            [
                                'name'         => $request->get( 'name' ) ,
                                'display_name' => $request->get( 'display_name' ) ,
                                'email'        => $request->get( 'email' ) ,
                                'password'     => bcrypt( $request->get( 'password' ) ) ,
                                'role_id'      => $request->get( 'role_id' ) ,
                                'work_id'      => $request->get( 'work_id' ) ,
                            ]
                        );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
                case ( '3' ):
                    if ( $request->get( 'role_id' ) != '1'
                        && $request->get( 'role_id' ) != '2'
                        && $request->get( 'role_id' ) != '3' )
                    {
                        $user = Speedy::getModelInstance( 'user' )->create(
                            [
                                'name'         => $request->get( 'name' ) ,
                                'display_name' => $request->get( 'display_name' ) ,
                                'email'        => $request->get( 'email' ) ,
                                'password'     => bcrypt( $request->get( 'password' ) ) ,
                                'role_id'      => $request->get( 'role_id' ) ,
                                'work_id'      => $request->get( 'work_id' ) ,
                            ]
                        );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
                case ( '4' ):
                    if ( $request->get( 'role_id' ) == '5' )
                    {
                        $user = Speedy::getModelInstance( 'user' )->create(
                            [
                                'name'         => $request->get( 'name' ) ,
                                'display_name' => $request->get( 'display_name' ) ,
                                'email'        => $request->get( 'email' ) ,
                                'password'     => bcrypt( $request->get( 'password' ) ) ,
                                'role_id'      => $request->get( 'role_id' ) ,
                                'work_id'      => $request->get( 'work_id' ) ,
                            ]
                        );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
                case ( '5' ):
                    break;
                case ( '6' ):
                    if ( $request->get( 'role_id' ) == '5'
                        || $request->get( 'role_id' ) == '4' )
                    {
                        $user = Speedy::getModelInstance( 'user' )->create(
                            [
                                'name'         => $request->get( 'name' ) ,
                                'display_name' => $request->get( 'display_name' ) ,
                                'email'        => $request->get( 'email' ) ,
                                'password'     => bcrypt( $request->get( 'password' ) ) ,
                                'role_id'      => $request->get( 'role_id' ) ,
                                'work_id'      => $request->get( 'work_id' ) ,
                            ]
                        );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
            }

            return $user ? redirect()->route( 'admin.user.index' ) : redirect()->back()->withErrors( trans( 'view.admin.user.create_user_failed' ) )->withInput();
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
            $user  = Speedy::getModelInstance( 'user' )->find( $id );
            $shops = Speedy::getModelInstance( 'shop' )->where( 'valid' , '1' )->get();

            $nowUser = Auth::user();
            switch ( $nowUser->role_id )
            {
                case '1':
                    $roles = Speedy::getModelInstance( 'role' )->where( 'valid' , '1' )->get();
                    break;
                case '2':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '3':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'id' , '!=' , '2' )
                        ->where( 'id' , '!=' , '3' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '4':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'id' , '!=' , '2' )
                        ->where( 'id' , '!=' , '3' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                case '6':
                    $roles = Speedy::getModelInstance( 'role' )
                        ->where( 'id' , '!=' , '1' )
                        ->where( 'id' , '!=' , '2' )
                        ->where( 'id' , '!=' , '6' )
                        ->where( 'id' , '!=' , '3' )
                        ->where( 'valid' , '1' )
                        ->get();
                    break;
                default:
                    $roles = Speedy::getModelInstance( 'role' )->all();
                    break;
            }

            return view( 'vendor.speedy.admin.user.edit' , compact( 'user' , 'roles' , 'shops' ) );
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
            $nowUser = Auth::user();

            $validator = $this->mustValidate( 'user.update' , false , 'name' , $id );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }

            $payload = $request->all();

            //表单必填数据
            $data = [
                'name'         => $payload['name'] ,
                'email'        => $payload['email'] ,
                'display_name' => $payload['display_name'] ,
                'work_id'      => $payload['work_id'] ,
            ];

            //表单选填数据
            if ( $payload['password'] )
            {
                $data = array_merge( $data , [ 'password' => bcrypt( $payload['password'] ) ] );
            }
            if ( $payload['role_id'] )
            {
                $data = array_merge( $data , [ 'role_id' => $payload['role_id'] ] );
            }
            if ( $payload['shop_id'] )
            {
                if ( $payload['shop_id'] == 'false' )
                {
                    $data = array_merge( $data , [ 'shops_id' => null ] );
                }
                else
                {
                    $data = array_merge( $data , [ 'shops_id' => $payload['shop_id'] ] );
                }
            }

            $editUser = User::where( 'id' , $id )
                ->where( 'valid' , '1' )
                ->first();
            $result   = '';
            switch ( $nowUser->role_id )
            {
                case ( '1' ):
                    /*
                    * 如调整导师权限至其他权限
                    * 清除学员指导老师，调整导师表内容
                    */
                    if ( $editUser->role_id == 6 && $payload['role_id'] != 6 )
                    {
                        $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '1' )->first();
                        $shop       = Shop::where( 'teacher_id' , $teacher_id->id )->where( 'valid' , '1' )->get();
                        foreach ( $shop as $v )
                        {
                            $v->update( [ 'teacher_id' => null ] );
                        }
                        Teacher::where( 'user_id' , $id )->update( [ 'valid' => '0' , 'under_sum' => 0 ] );
                    }

                    /*
                    * 如其他权限调整至导师权限
                    * 如表中已有导师，调整valid值
                    */
                    if ( $payload['role_id'] == 6 )
                    {
                        $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '0' )->first();
                        if ( $teacher_id )
                        {
                            $teacher_id->update( [ 'valid' => '1' ] );
                        }
                        else
                        {
                            Teacher::create(
                                [
                                    'user_id'   => $id ,
                                    'valid'     => '1' ,
                                    'under_sum' => 0 ,
                                ]
                            );
                        }
                    }
                    $result = Speedy::getModelInstance( 'user' )->find( $id )->update( $data );
                    break;
                case ( '2' ):
                    if ( $payload['role_id'] != 1 )
                    {
                        /*
                    * 如调整导师权限至其他权限
                    * 清除学员指导老师，调整导师表内容
                    */
                        if ( $editUser->role_id == 6 && $payload['role_id'] != 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '1' )->first();
                            $shop       = Shop::where( 'teacher_id' , $teacher_id->id )->where( 'valid' , '1' )->get();
                            foreach ( $shop as $v )
                            {
                                $v->update( [ 'teacher_id' => null ] );
                            }
                            Teacher::where( 'user_id' , $id )->update( [ 'valid' => '0' , 'under_sum' => 0 ] );
                        }

                        /*
                        * 如其他权限调整至导师权限
                        * 如表中已有导师，调整valid值
                        */
                        if ( $payload['role_id'] == 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '0' )->first();
                            if ( $teacher_id )
                            {
                                $teacher_id->update( [ 'valid' => '1' ] );
                            }
                            else
                            {
                                Teacher::create(
                                    [
                                        'user_id'   => $id ,
                                        'valid'     => '1' ,
                                        'under_sum' => 0 ,
                                    ]
                                );
                            }
                        }
                        $result = Speedy::getModelInstance( 'user' )->find( $id )->update( $data );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
                case ( '3' ):
                    if ( $payload['role_id'] != 1 && $payload['role_id'] != 2 )
                    {
                        /*
                    * 如调整导师权限至其他权限
                    * 清除学员指导老师，调整导师表内容
                    */
                        if ( $editUser->role_id == 6 && $payload['role_id'] != 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '1' )->first();
                            $shop       = Shop::where( 'teacher_id' , $teacher_id->id )->where( 'valid' , '1' )->get();
                            foreach ( $shop as $v )
                            {
                                $v->update( [ 'teacher_id' => null ] );
                            }
                            Teacher::where( 'user_id' , $id )->update( [ 'valid' => '0' , 'under_sum' => 0 ] );
                        }

                        /*
                        * 如其他权限调整至导师权限
                        * 如表中已有导师，调整valid值
                        */
                        if ( $payload['role_id'] == 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '0' )->first();
                            if ( $teacher_id )
                            {
                                $teacher_id->update( [ 'valid' => '1' ] );
                            }
                            else
                            {
                                Teacher::create(
                                    [
                                        'user_id'   => $id ,
                                        'valid'     => '1' ,
                                        'under_sum' => 0 ,
                                    ]
                                );
                            }
                        }
                        $result = Speedy::getModelInstance( 'user' )->find( $id )->update( $data );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
                case ( '4' ):
                    if ( $payload['role_id'] != 1
                        && $payload['role_id'] != 2
                        && $payload['role_id'] != 3
                        && $payload['role_id'] != 6 )
                    {
                        /*
                    * 如调整导师权限至其他权限
                    * 清除学员指导老师，调整导师表内容
                    */
                        if ( $editUser->role_id == 6 && $payload['role_id'] != 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '1' )->first();
                            $shop       = Shop::where( 'teacher_id' , $teacher_id->id )->where( 'valid' , '1' )->get();
                            foreach ( $shop as $v )
                            {
                                $v->update( [ 'teacher_id' => null ] );
                            }
                            Teacher::where( 'user_id' , $id )->update( [ 'valid' => '0' , 'under_sum' => 0 ] );
                        }

                        /*
                        * 如其他权限调整至导师权限
                        * 如表中已有导师，调整valid值
                        */
                        if ( $payload['role_id'] == 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '0' )->first();
                            if ( $teacher_id )
                            {
                                $teacher_id->update( [ 'valid' => '1' ] );
                            }
                            else
                            {
                                Teacher::create(
                                    [
                                        'user_id'   => $id ,
                                        'valid'     => '1' ,
                                        'under_sum' => 0 ,
                                    ]
                                );
                            }
                        }
                        $result = Speedy::getModelInstance( 'user' )->find( $id )->update( $data );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
                case ( '5' ):
                    abort( 403 , trans( 'view.admin.public.403' ) );
                    break;
                case ( '6' ):
                    if ( $payload['role_id'] != 1
                        && $payload['role_id'] != 2
                        && $payload['role_id'] != 3 )
                    {
                        /*
                    * 如调整导师权限至其他权限
                    * 清除学员指导老师，调整导师表内容
                    */
                        if ( $editUser->role_id == 6 && $payload['role_id'] != 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '1' )->first();
                            $shop       = Shop::where( 'teacher_id' , $teacher_id->id )->where( 'valid' , '1' )->get();
                            foreach ( $shop as $v )
                            {
                                $v->update( [ 'teacher_id' => null ] );
                            }
                            Teacher::where( 'user_id' , $id )->update( [ 'valid' => '0' , 'under_sum' => 0 ] );
                        }

                        /*
                        * 如其他权限调整至导师权限
                        * 如表中已有导师，调整valid值
                        */
                        if ( $payload['role_id'] == 6 )
                        {
                            $teacher_id = Teacher::where( 'user_id' , $id )->where( 'valid' , '0' )->first();
                            if ( $teacher_id )
                            {
                                $teacher_id->update( [ 'valid' => '1' ] );
                            }
                            else
                            {
                                Teacher::create(
                                    [
                                        'user_id'   => $id ,
                                        'valid'     => '1' ,
                                        'under_sum' => 0 ,
                                    ]
                                );
                            }
                        }
                        $result = Speedy::getModelInstance( 'user' )->find( $id )->update( $data );
                    }
                    else
                    {
                        abort( 403 , trans( 'view.admin.public.403' ) );
                    }
                    break;
            }

            return $result ? redirect()->route( 'admin.user.index' ) : redirect()->back()->withErrors( trans( 'view.admin.user.edit_user_failed' ) )->withInput();
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
            //判断是否删除导师，如是则删除对应导师数据
            $user = Speedy::getModelInstance( 'user' )->where( 'id' , $id )->first();
            if ( $user->role_id == '6' )
            {
                Speedy::getModelInstance( 'teacher' )->where( 'user_id' , $user->id )->update(
                    [
                        'valid' => '0' ,
                    ]
                );
            }

            $result = Speedy::getModelInstance( 'user' )->where( 'id' , $id )->update(
                [
                    'valid' => '0' ,
                ]
            );

            return $result ? redirect()->route( 'admin.user.index' ) : redirect()->back()->withErrors( trans( 'view.admin.user.delete_user_failed' ) )->withInput();
        }
    }
