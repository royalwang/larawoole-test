<?php

namespace App\Http\Controllers\admin;

use App\Models\Sp;
use App\Models\Teacher;
use Speedy;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class SpController extends BaseController
{

    protected $permissionName = 'sp';

    public function index()
    {
        $nowUser = Auth::user();
        $shop_id = $nowUser->shops_id;

        switch ($nowUser->role_id){
            case (1):
                $shops = Shop::where('valid','1')->orderby('updated_at','desc')->paginate(10);
                break;
            case (2):
                $shops = Shop::where('valid','1')->orderby('updated_at','desc')->paginate(10);
                break;
            case (3):
                $area = $nowUser->belongsToArea->id;
                $shops = Shop::where('valid','1')->where('area_id','=',$area)->orderby('updated_at','desc')->paginate(10);
                break;
            case (4):
                $shops = Shop::where('id','=',$shop_id)->where('valid','1')->orderby('updated_at','desc')->paginate(10);
                break;
            case (5):
                break;
            case (6):
                $teacher = Teacher::where('user_id',$nowUser->id)->where('valid','1')->first();
                $shops = Shop::where('valid','1')->where('teacher_id','=',$teacher->id)->orderby('updated_at','desc')->paginate(10);
                break;
            default:
                break;
        }

        return view('vendor.speedy.admin.sp.index', compact('shops'));
    }

    public function detail(Request $request)
    {
        $id = $request->input('sid');

        $sps = Sp::where('shops_id',$id)->where('valid','1')->paginate(10);

        return view('vendor.speedy.admin.sp.list', compact('sps'));

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
        $equip = Speedy::getModelInstance('equip')->find($id);

        $shop = Speedy::getModelInstance('shop')->where('valid','1')->get();

        return view('vendor.speedy.admin.equipment.edit', compact('equip','shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $payload = $request->all();

        $payload['result'] === 'pass' ? $data = ['status' => '1']:$data = ['status' => '2'];

        $data = array_merge($data,['sp_user' => $request->user()->id]) ;

        $result = Speedy::getModelInstance('sp')->find($payload['spid'])->update($data);

        return $result ? redirect()->route('admin.sp.index') : redirect()->back()->withErrors(trans('view.admin.sp.edit_sp_failed'))->withInput();
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
