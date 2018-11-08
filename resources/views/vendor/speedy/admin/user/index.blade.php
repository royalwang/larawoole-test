@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading"><a class="btn btn-info" href="{{ route('admin.user.create') }}"
                                                  style="background-color: #00a0e8;"
                                                  onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.create') .' '. trans('view.admin.user.title') }}</a>
                    </div>
                    {{--<div class="panel-body"></div>--}}
                    <table class="table table-bordered table-hover" style="text-align: center">
                        <thead>
                        <tr class="active" style="text-align: center">
                            <th style="text-align: center">{{ trans('view.admin.user.name') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.role') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.display_name') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.work_id') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.identity') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.sex') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.hire_date') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.user.shop') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.public.created_at') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.public.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</th>
                                <td>{{ $user->role ? $user->role->display_name : '-' }}</td>
                                <td>{{ $user->display_name ? $user->display_name : '-' }}</td>
                                <td>{{ $user->work_id ? $user->work_id : '-' }}</td>
                                <td>{{ $user->identity ? $user->identity : '-' }}</td>
                                <td>{{ $user->sex == '0' ? '男' : '女' }}</td>
                                <td>{{ $user->hire_date ? $user->hire_date : '-' }}</td>
                                <td>{{ $user->belongsToShop ? $user->belongsToShop->name : '-' }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                       href="{{ route('admin.user.edit', ['id' => $user->id]) }}"
                                       onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.edit') }}</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:;"
                                       onclick="document.getElementById('delete-form').action = '{{ route('admin.user.index') . "/{$user->id}" }}'"
                                       data-toggle="modal"
                                       data-target="#deleteModal">{{ trans('view.admin.public.destroy') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="panel-footer">{{ $users->links() }}</div>
                </div>

            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('view.admin.public.destroy') . ' ' . trans('view.admin.user.title') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('view.admin.user.sure_to_delete') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('view.admin.public.close') }}</button>
                    <button type="button" class="btn btn-danger" onclick="event.preventDefault();
                    document.getElementById('delete-form').submit();">{{ trans('view.admin.public.destroy') }}</button>
                    <form id="delete-form" action="" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection