@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-info">
                    <div class="panel-heading">{{ trans('view.admin.report.title') }}</div>
                    <form>
                        <div class="panel-body">
                            <div class="form-group" style="border-bottom: 1px solid #ddd;">
                                <label>{{ trans('view.admin.report.name') }}</label>
                                <p>{{$report->belongsToUser->display_name}}</p>
                            </div>
                            <div class="form-group" style="border-bottom: 1px solid #ddd;">
                                <label>{{ trans('view.admin.report.job') }}</label>
                                @switch($report->belongsToUser->role_id)
                                    @case (1)
                                    <p>超级管理员</p>
                                    @break
                                    @case (2)
                                    <p>老板</p>
                                    @break
                                    @case (3)
                                    <p>区域经理</p>
                                    @break
                                    @case (4)
                                    <p>店长</p>
                                    @break
                                    @case (5)
                                    <p>员工</p>
                                    @break
                                    @case (6)
                                    <p>导师</p>
                                    @break
                                @endswitch
                            </div>
                            <div class="form-group" style="border-bottom: 1px solid #ddd;">
                                <label>{{ trans('view.admin.report.time') }}</label>
                                <p>{{$report->created_at}}</p>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.report.content') }}</label>
                                <p style="width: 100%;word-wrap:break-word;word-break:break-all;overflow: hidden;">{{$report->content}}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection