@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">修改个人信息
                    </div>
                    {{--<div class="panel-body"></div>--}}
                    <table class="table table-bordered table-hover" style="text-align: center">
                        <thead>
                        <tr class="active" style="text-align: center">
                            <th style="text-align: center">{{ trans('view.admin.user.name') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.display_name') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.work_id') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.email') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.public.created_at') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.public.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->display_name? $user->display_name:'-' }}</td>
                            <td>{{ $user->work_id? $user->work_id:'-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <a class="btn btn-info btn-sm"
                                   href="{{ route('admin.personal.edit', ['id' => $user->id]) }}"
                                   onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.edit') }}</a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection