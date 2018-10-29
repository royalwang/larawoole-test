@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.public.create') . ' ' . trans('view.admin.report.title') }}</div>
                    <form method="post" action="{{ route('admin.report.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.report.name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{$nowUser->display_name}}" readonly>
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.report.job') }}</label>
                                @switch($nowUser->role_id)
                                    @case (1)
                                    <input type="text" name="job" class="form-control" value="超级管理员" readonly>
                                    @break
                                    @case (2)
                                    <input type="text" name="job" class="form-control" value="老板" readonly>
                                    @break
                                    @case (3)
                                    <input type="text" name="job" class="form-control" value="区域经理" readonly>
                                    @break
                                    @case (4)
                                    <input type="text" name="job" class="form-control" value="店长" readonly>
                                    @break
                                    @case (5)
                                    <input type="text" name="job" class="form-control" value="员工" readonly>
                                    @break
                                    @case (6)
                                    <input type="text" name="job" class="form-control" value="导师" readonly>
                                    @break
                                @endswitch
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.report.time') }}</label>
                                <input type="text" name="time" class="form-control" value="{{Carbon\Carbon::now()}}" readonly>
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.report.content') }}</label>
                                <textarea rows="8" name="content" class="form-control" placeholder="" ></textarea>
                            </div>
                        </div>
                        <div class="panel-footer"><button type="submit" class="btn btn-success">{{ trans('view.admin.public.submit') }}</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection