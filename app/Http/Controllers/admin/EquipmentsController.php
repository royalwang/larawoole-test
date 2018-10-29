<?php

namespace App\Http\Controllers\admin;

use App\Models\area;
use App\Models\Shop;
use App\Models\Teacher;
use Speedy;
use Illuminate\Http\Request;
use App\Models\Equipment;
use Auth;

class EquipmentsController extends BaseController
{

    protected $permissionName = 'equip';

    public function index()
    {
        $nowUser = Auth::user();
        switch ($nowUser->role_id)
        {
            case ('1'):
                $equips = Equipment::where('valid','1')->orderBy('shops_id','DESC')->paginate(10);
                break;
            case ('2'):
                $equips = Equipment::where('valid','1')->orderBy('shops_id','DESC')->paginate(10);
                break;
            case ('3'):
                $area = area::where('valid','1')->where('user_id',$nowUser->id)->first();
                $shop = Shop::where('valid','1')->where('area_id',$area->id)->get();
                $shops = [];
                foreach ($shop as $v)
                {
                    array_push($shops , $v->id);
                }
                $shops = array_unique($shops);
                $equips = Equipment::where('valid','1')->whereIn('shops_id',$shops)->orderBy('shops_id','DESC')->paginate(10);
                break;
            case ('4'):
                $equips = Equipment::where('valid','1')->where('shops_id',$nowUser->shops_id)->paginate(10);
                break;
            case ('6'):
                $teacher = Teacher::where('valid','1')->where('user_id',$nowUser->id)->first();
                $shop = Shop::where('valid','1')->where('teacher_id',$teacher->id)->get();
                $shops = [];
                foreach ($shop as $v)
                {
                    array_push($shops , $v->id);
                }
                $shops = array_unique($shops);
                $equips = Equipment::where('valid','1')->whereIn('shops_id',$shops)->orderBy('shops_id','DESC')->paginate(10);
                break;
        }

        return view('vendor.speedy.admin.equipment.index', compact('equips'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Speedy::getModelInstance('shop')->where('valid','1')->get();

        return view('vendor.speedy.admin.equipment.edit', compact('shop'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = $this->mustValidate('equip.store');

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $equip = Speedy::getModelInstance('equip')->create([
            'name' => $request->get('name'),
            'verify_code' => $request->get('verify_code'),
            'type' => $request->get('type'),
            'shops_id' => $request->get('shops_id'),
            'status' => '2'
        ]);

        return $equip ? redirect()->route('admin.equip.index') : redirect()->back()->withErrors(trans('view.admin.equip.create_equip_failed'))->withInput();
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
        $validator = $this->mustValidate('equip.update', false, 'name', $id);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payload = $request->all();

        if (isset($payload['type'])){
            $data = ['name' => $payload['name'], 'shops_id' => $payload['shops_id'],'type' => $payload['type'],'status' => $payload['status']];
        }else{
            $data = ['name' => $payload['name'], 'shops_id' => $payload['shops_id'],'status' => $payload['status']];

        }

        Speedy::getModelInstance('equip')->find($id)->update($data);

        if($payload['status'] != '4')
        {
            $result = Speedy::getModelInstance('equip')->find($id)->update(['user_id' => null]);
        }else{
            $result = true;
        }

        return $result ? redirect()->route('admin.equip.index') : redirect()->back()->withErrors(trans('view.admin.user.edit_equip_failed'))->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Speedy::getModelInstance('equip')->where('id',$id)->update([
            'valid' => '0',
        ]);
        return $result ? redirect()->route('admin.equip.index') : redirect()->back()->withErrors(trans('view.admin.equip.delete_equip_failed'))->withInput();
    }
}
