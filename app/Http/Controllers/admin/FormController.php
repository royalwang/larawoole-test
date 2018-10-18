<?php

    namespace App\Http\Controllers\admin;

    use App\Models\area;
    use App\Models\Teacher;
    use Carbon\Carbon;
    use Speedy;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Shop;

    class FormController extends BaseController
    {

        protected $permissionName = 'form';

        public function index()
        {
            $nowUser = Auth::user();
            $now = Carbon::today();

            switch ($nowUser->role_id){
                case ('1'):
                    $shops = Shop::where('valid','1')->paginate(10);
                    break;
                case ('2'):
                    $shops = Shop::where('valid','1')->paginate(10);
                    break;
                case ('3'):
                    $area = area::where('valid','1')->where('user_id',$nowUser->id)->first();
                    $shops = Shop::where('valid','1')->where('area_id',$area->id)->get();
                    break;
                case ('4'):
                    $shops = Shop::where('shops_id',$nowUser->shops_id)->where('valid','1')->get();
                    break;
                case ('6'):
                    $teacher = Teacher::where('valid','1')->where('user_id',$nowUser->id)->first();
                    $shops = Shop::where('valid','1')->where('teacher_id',$teacher->id)->get();
                    break;
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
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request , $id)
        {
           //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
           //
        }

    }
