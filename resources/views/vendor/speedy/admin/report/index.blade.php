@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.report.title') }}
                        <a href="{{ route('admin.report.create') }}" style="float: right;"
                           class="btn btn-primary btn-sm">{{ trans('view.admin.public.create') .' '. trans('view.admin.report.title') }}</a>
                    </div>
                    <div class="panel-body">
                        <div class="panel-default">
                            <form method="post"
                                  action="{{ route('admin.report.search')}}">
                                {{ csrf_field() }}
                                {{ method_field('POST') }}
                                <div class="form-group">
                                    <label>请选择查询时间</label>
                                    <input type="date" name="datetime" class="form-control"
                                           placeholder="{{ trans('view.admin.report.time') }}"
                                           value="{{ isset($time) ? $time:'' }}" required>
                                    <button type="submit" class="btn btn-sm btn-success"
                                            style="float: right;margin-top: 10px;">查询
                                    </button>
                                </div>
                            </form>
                        </div>
                        @if($reports->count() == 0)
                            @include('vendor.speedy.layouts.null_page')
                        @else
                            <table class="table" style="margin-top: 30px;">
                                <thead>
                                <tr>
                                    <th>{{ trans('view.admin.report.name') }}</th>
                                    <th>{{ trans('view.admin.report.shop') }}</th>
                                    <th>{{ trans('view.admin.report.job') }}</th>
                                    <th>{{ trans('view.admin.public.created_at') }}</th>
                                    <th>{{ trans('view.admin.public.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <th scope="row">{{ $report->belongsToUser->display_name }}</th>
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
                                            <a class="btn btn-warning btn-sm"
                                               href="{{ route('admin.report.show', ['id' => $report->id]) }}"
                                               onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.report.view') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$reports->links()}}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection