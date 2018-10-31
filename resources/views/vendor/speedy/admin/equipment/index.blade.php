@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.equip.title') }}
                        <a href="{{ route('admin.equip.create') }}" style="float: right;"
                           class="btn btn-info btn-sm"
                           onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.create') .' '. trans('view.admin.equip.title') }}</a>
                    </div>
                    <div class="panel-body">
                        @if($equips == null || $equips->count() == 0)
                            @include('vendor.speedy.layouts.null_page')
                        @else
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ trans('view.admin.equip.name') }}</th>
                                    <th>{{ trans('view.admin.equip.type') }}</th>
                                    <th>{{ trans('view.admin.equip.shop') }}</th>
                                    <th>{{ trans('view.admin.equip.uuid') }}</th>
                                    <th>{{ trans('view.admin.equip.status') }}</th>
                                    <th>{{ trans('view.admin.equip.user_id') }}</th>
                                    <th>{{ trans('view.admin.public.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($equips as $equip)
                                    <tr>
                                        <th scope="row">{{ $equip->name }}</th>
                                        <td>{{ $equip->type === '1' ? '收钱终端':'员工机' }}</td>
                                        <td>{{ $equip->belongsToShops ? $equip->belongsToShops->name:'-' }}</td>
                                        <td>{{ $equip->verify_code? $equip->verify_code:'-' }}</td>
                                        @switch($equip->status)
                                            @case('1')
                                            <td>已被登录</td>
                                            @break
                                            @case('2')
                                            <td>未被登录</td>
                                            @break
                                            @case('3')
                                            <td>断电</td>
                                            @break
                                            @case('4')
                                            <td>挂起</td>
                                            @break
                                            @case('5')
                                            <td>故障</td>
                                            @break
                                        @endswitch
                                        <td>{{ $equip->belongsToUser ? $equip->belongsToUser->display_name : '无' }}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                               href="{{ route('admin.equip.edit', ['id' => $equip->id]) }}"
                                               onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.edit') }}</a>
                                            <a class="btn btn-danger btn-sm" href="javascript:;"
                                               onclick="document.getElementById('delete-form').action = '{{ route('admin.equip.index') . "/{$equip->id}" }}'"
                                               data-toggle="modal"
                                               data-target="#deleteModal">{{ trans('view.admin.public.destroy') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $equips->links() }}
                        @endif
                    </div>

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
                    <h4 class="modal-title">{{ trans('view.admin.public.destroy') . ' ' . trans('view.admin.equip.title') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('view.admin.equip.sure_to_delete') }}</p>
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