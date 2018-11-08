@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <a href="{{ route('admin.report.create') }}"
                           class="btn btn-info" style="background-color: #00a0e8;">{{ trans('view.admin.public.create') .' '. trans('view.admin.report.title') }}</a>
                    </div>
                    <div class="panel-body">
                        <form method="post"
                              action="{{ route('admin.report.search')}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <input type="date" name="datetime" class="form-control "
                                           placeholder="{{ trans('view.admin.report.time') }}"
                                           value="{{ isset($time) ? $time:'' }}" required>
                                </div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info" style="background-color: #00a0e8;">选择时间进行查询
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if($reports->count() == 0)
                        @include('vendor.speedy.layouts.null_page')
                    @else
                        <table class="table table-bordered table-hover" style="margin-top: 10px;text-align: center;">
                            <thead>
                            <tr class="active" style="text-align: center">
                                <th style="text-align: center">{{ trans('view.admin.report.name') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.report.shop') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.report.job') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.public.created_at') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.public.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <th style="text-align: center">{{ $report->belongsToUser->display_name }}</th>
                                    <td>{{ $report->belongsToUser->belongsToShop ? $report->belongsToUser->belongsToShop->name:'--' }}</td>
                                    @switch($report->belongsToUser->role_id)
                                        @case (1)
                                        <td>超级管理员</td>
                                        @break
                                        @case (2)
                                        <td>老板</td>
                                        @break
                                        @case (3)
                                        <td>区域经理</td>
                                        @break
                                        @case (4)
                                        <td>店长</td>
                                        @break
                                        @case (5)
                                        <td>员工</td>
                                        @break
                                        @case (6)
                                        <td>导师</td>
                                        @break
                                    @endswitch
                                    <td>{{ $report->created_at }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                           href="{{ route('admin.report.show', ['id' => $report->id]) }}"
                                           onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.report.view') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="panel-footer">{{$reports->links()}}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection