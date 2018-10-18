@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('view.admin.user.name') }}</th>
                                <th>{{ trans('view.admin.user.display_name') }}</th>
                                <th>{{ trans('view.admin.user.work_id') }}</th>
                                <th>{{ trans('view.admin.user.email') }}</th>
                                <th>{{ trans('view.admin.public.created_at') }}</th>
                                <th>{{ trans('view.admin.public.action') }}</th>
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
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.personal.edit', ['id' => $user->id]) }}" onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.edit') }}</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection