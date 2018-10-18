<?php

namespace App\Http\Controllers\admin;

use Speedy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class personalInfoController extends BaseController
{
    protected $permissionName = 'personal';

    public function index()
    {
        $user = Auth::user();
//        throw new Exception('Something went wrong. Time for lunch!');

        return $user ? view('vendor.speedy.admin.personal.index', compact('user')) : redirect()->back()->withErrors(trans('view.admin.user.edit_user_failed'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();

        return view('vendor.speedy.admin.personal.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validator = $this->mustValidate('user.update', false,'name',$id);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payload = $request->all();

        $data = ['name' => $payload['name'],'display_name' => $payload['display_name'], 'email' => $payload['email'],'work_id' => $payload['work_id']];

        if($payload['password']){
            $data = array_merge($data, ['password' => bcrypt($payload['password'])]);
        }

        $result = Speedy::getModelInstance('user')->find($id)->update($data);

        if($user->id == $id){
            return $result ? redirect()->route('admin.personal.index') : redirect()->back()->withErrors(trans('view.admin.user.edit_user_failed'))->withInput();
        }else{
            abort(403, trans('view.admin.public.403'));
            return redirect()->back()->withErrors(trans('view.admin.user.edit_user_failed'))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
