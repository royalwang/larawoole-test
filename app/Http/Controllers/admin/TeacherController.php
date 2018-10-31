<?php

    namespace App\Http\Controllers\admin;

    use App\Models\Shop;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Teacher;
    use Speedy;

    class TeacherController extends BaseController
    {
        protected $permissionName = 'teacher';

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $nowUser = Auth::user();

            if ( $nowUser->role_id == '1' || $nowUser->role_id == '2' || $nowUser->role_id == '3' )
            {
                $teachers = Teacher::where( 'valid' , '1' )->paginate( 10 );
            }
            else
            {
                abort( 403 , trans( 'view.admin.public.403' ) );
            }

            return view( 'vendor.speedy.admin.teacher.index' , compact( 'teachers' ) );
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
            $users = Speedy::getModelInstance( 'user' )->where( 'role_id' , '4' )->where('valid','1')->get();

            $teacher = Teacher::where( 'id' , $id )->first();

            return view( 'vendor.speedy.admin.teacher.edit' , compact( 'users' , 'teacher' ) );
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
            $validator = $this->mustValidate( 'teacher.update' , false , 'user_id' , $id );

            if ( $validator->fails() )
            {
                return redirect()->back()->withErrors( $validator )->withInput();
            }
            $payload = $request->get( 'user_id' );

            //清空关联
            Shop::where( 'valid' , '1' )->where( 'teacher_id' , $id )->update(
                [
                    'teacher_id' => null ,
                ]
            );
            //重新绑定关系
            foreach ( $payload as $v )
            {
                Shop::where( 'manager_id' , $v )->update( [ 'teacher_id' => $id ] );
            }

            $teacher = Teacher::find( $id )->update( [ 'under_sum' => count( $payload ) ] );

            return $teacher ? redirect()->route( 'admin.teacher.index' ) : redirect()->back()->withErrors( trans( 'view.admin.teacher.edit_teacher_failed' ) )->withInput();
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
