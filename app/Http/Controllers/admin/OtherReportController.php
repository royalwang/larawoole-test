<?php

    namespace App\Http\Controllers\admin;

    use App\Models\area;
    use App\Models\Report;
    use App\Models\Shop;
    use App\Models\Teacher;
    use App\Models\User;
    use Speedy;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;

    class OtherReportController extends BaseController
    {
        protected $permissionName = 'otherreport';

        public function index()
        {
            $nowUser = Auth::user();
            switch ($nowUser->role_id) {
                case ('1'): //超级管理员
                    $users = User::where('role_id' , '!=' , '1')->where('valid' , '1')->get();
                    $user = [];
                    foreach ($users as $v) {
                        array_push($user , $v->id);
                    }
                    $user = array_unique($user);
                    $reports = Report::where('valid' , '1')
                        ->whereIn('user_id' , $user)
                        ->paginate(10);
                    break;
                case ('2'): //老板
                    $users = User::where('role_id' , '!=' , '1')
                        ->where('role_id' , '!=' , '2')
                        ->where('valid' , '1')
                        ->get();
                    $user = [];
                    foreach ($users as $v) {
                        array_push($user , $v->id);
                    }
                    $user = array_unique($user);
                    $reports = Report::where('valid' , '1')
                        ->whereIn('user_id' , $user)
                        ->paginate(10);
                    break;
                case ('3'): //区域经理
                    $area = area::where('user_id' , $nowUser->id)->where('valid' , '1')->first();
                    $shops = Shop::where('area_id' , $area->id)->where('valid' , '1')->get();
                    $shopsId = [];
                    $teachers = [];
                    $user = [];
                    foreach ($shops as $v) {
                        array_push($teachers , $v->teacher_id);
                        array_push($shopsId , $v->id);
                    }
                    $teachers = array_unique($teachers);
                    $shopsId = array_unique($shopsId);
                    $users = User::whereIn('shops_id' , $shopsId)->where('valid' , '1')->get();
                    foreach ($users as $v) {
                        array_push($user , $v->id);
                    }
                    $teacher = Teacher::whereIn('id' , $teachers)->where('valid' , '1')->get();
                    foreach ($teacher as $v) {
                        array_push($user , $v->user_id);
                    }
                    $user = array_unique($user);
                    $reports = Report::whereIn('user_id' , $user)->where('valid' , '1')->paginate(10);
                    break;
                case ('4'): //店长
                    $user = User::where('shops_id' , $nowUser->shops_id)->where('valid' , '1')->get();
                    $users = [];
                    foreach ($user as $v) {
                        array_push($users , $v->id);
                    }
                    $users = array_unique($users);
                    $reports = Report::whereIn('id' , $users)
                        ->where('valid' , '1')
                        ->paginate(10);
                    break;
                case ('5'): //员工
                    $reports = Report::where('user_id' , $nowUser->id)->where('valid' , '1')->paginate(10);
                    break;
                case ('6'): //导师
                    $teacher = Teacher::where('user_id' , $nowUser->id)->where('valid' , '1')->first();
                    $shop = Shop::where('teacher_id' , $teacher->id)->where('valid' , '1')->get();
                    $shops = [];
                    foreach ($shop as $v) {
                        array_push($shops , $v->id);
                    }
                    $shops = array_unique($shops);
                    $user = User::whereIn('shops_id' , $shops)->where('valid' , '1')->get();
                    $users = [];
                    foreach ($user as $v) {
                        array_push($users , $v->id);
                    }
                    $users = array_unique($users);
                    $reports = Report::whereIn('user_id' , $users)->where('valid' , '1')->paginate(10);
                    break;
            }

            return view('vendor.speedy.admin.otherreport.index' , compact('reports'));
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
            $report = Report::where('id' , $id)->first();

            return view('vendor.speedy.admin.otherreport.edit' , compact('report'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function edit()
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

        public function search(Request $request)
        {
            $time = $request->get('datetime');
            $nowUser = Auth::user();
            switch ($nowUser->role_id) {
                case ('1'): //超级管理员
                    $reports = Report::
                    where('valid' , '1')
                        ->whereDate('created_at' , $time)
                        ->paginate(10);
                    break;
                case ('2'): //老板
                    $reports = Report::
                    where('valid' , '1')
                        ->whereDate('created_at' , $time)
                        ->paginate(10);
                    break;
                case ('3'): //区域经理
                    $area = area::where('user_id' , $nowUser->id)->where('valid','1')->first();
                    $shops = Shop::where('area_id' , $area->id)->where('valid','1')->get();
                    $shopsId = [];
                    $teachers = [];
                    $user = [];
                    foreach ($shops as $v) {
                        array_push($teachers , $v->teacher_id);
                        array_push($shopsId , $v->id);
                    }
                    $teachers = array_unique($teachers);
                    $shopsId = array_unique($shopsId);
                    $users = User::whereIn('shops_id' , $shopsId)->where('valid','1')->get();
                    foreach ($users as $v) {
                        array_push($user , $v->id);
                    }
                    $teacher = Teacher::whereIn('id' , $teachers)->where('valid','1')->get();
                    foreach ($teacher as $v) {
                        array_push($user , $v->user_id);
                    }
                    $user = array_unique($user);
                    $reports = Report::whereIn('user_id' , $user)
                        ->where('valid','1')
                        ->whereDate('created_at' , $time)
                        ->paginate(10);
                    break;
                case ('4'): //店长
                    $user = User::where('shops_id' , $nowUser->shops_id)->where('valid','1')->get();
                    $users = [];
                    foreach ($user as $v) {
                        array_push($users , $v->id);
                    }
                    $users = array_unique($users);
                    $reports = Report::whereIn('id' , $users)
                        ->where('valid','1')
                        ->whereDate('created_at' , $time)
                        ->pagnate(10);
                    break;
                case ('5'): //员工
                    $reports = Report::where('user_id' , $nowUser->id)
                        ->where('valid','1')
                        ->whereDate('created_at' , $time)
                        ->paginate(10);
                    break;
                case ('6'): //导师
                    $teacher = Teacher::where('user_id' , $nowUser->id)->where('valid','1')->first();
                    $shop = Shop::where('teacher_id' , $teacher->id)->where('valid','1')->get();
                    $shops = [];
                    foreach ($shop as $v) {
                        array_push($shops , $v->id);
                    }
                    $shops = array_unique($shops);
                    $user = User::whereIn('shops_id' , $shops)->where('valid','1')->get();
                    $users = [];
                    foreach ($user as $v) {
                        array_push($users , $v->id);
                    }
                    $users = array_unique($users);
                    $reports = Report::whereIn('user_id' , $users)
                        ->where('valid','1')
                        ->whereDate('created_at' , $time)
                        ->paginate(10);
                    break;
            }
            return view('vendor.speedy.admin.otherreport.index' , compact('reports' , 'time'));
        }
    }
