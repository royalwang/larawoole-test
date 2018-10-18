<?php

namespace App\Http\Controllers\admin;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Speedy;

class ReportController extends BaseController
{
    protected $permissionName = 'myreport';

    public function index()
    {
        $nowUser = Auth::user();
        $reports = Report::where('user_id',$nowUser->id)
            ->where('valid','1')
            ->orderBy('created_at','DESC')
            ->paginate(10);
        return view('vendor.speedy.admin.report.index' , compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nowUser = Auth::user();
        return view('vendor.speedy.admin.report.edit' , compact('nowUser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nowUser = Auth::user();

        $validator = $this->mustValidate('report.store');

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $report = Speedy::getModelInstance('report')->create([
            'content' => $request->get('content'),
            'user_id' => $nowUser->id,
        ]);

        return $report ? redirect()->route('admin.report.index') : redirect()->back()->withErrors(trans('view.admin.report.create_report_failed'))->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = Report::where('id',$id)->first();
        return view('vendor.speedy.admin.report.show' , compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
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
        //
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

    public function search(Request $request)
    {
        $time = $request->get('datetime');
        $nowUser = Auth::user();
        $reports = Report::where('user_id',$nowUser->id)
            ->where('valid','1')
            ->whereDate('created_at',$time)
            ->paginate(10);
        return view('vendor.speedy.admin.report.index' , compact('reports','time'));
    }
}
