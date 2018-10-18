@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.sp.title').' - '.trans('view.admin.sp.detail') }}
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('view.admin.sp.name') }}</th>
                                <th>{{ trans('view.admin.sp.status') }}</th>
                                <th>{{ trans('view.admin.sp.result') }}
                                <th>{{ trans('view.admin.sp.user_id') }}</th>
                                <th>{{ trans('view.admin.sp.bz_id') }}</th>
                                <th>{{ trans('view.admin.public.created_at') }}</th>
                                <th>{{ trans('view.admin.public.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sps as $sp)
                                <tr>
                                    <td>{{ $sp->hasOneEquip->name }}</td>
                                    @if($sp->status == '0')
                                        <td>未审核</td>
                                        <td>无</td>
                                    @else
                                        <td>已审核</td>
                                        <td>{{ $sp->status === '1' ? '通过':'拒绝' }}</td>
                                    @endif
                                    <td>{{ $sp->hasOneSpUser ? $sp->hasOneSpUser->display_name : '无'}}</td>
                                    <td>{{ $sp->hasOneSpBzUser ? $sp->hasOneSpBzUser->display_name : '无'}}</td>
                                    <td>{{ $sp->created_at }}</td>
                                    <td>
                                        @if($sp->status == '0')
                                            <input id={{"pass"."-".$sp->id}} type="button" class="btn btn-success" value="通过"
                                                   data-toggle="modal" data-target="#confirmModal"
                                                   onclick="check_btn(id)">
                                            <input id={{"reject"."-".$sp->id}} type="button" class="btn btn-warning" value="拒绝"
                                                   data-toggle="modal" data-target="#confirmModal"
                                                   onclick="check_btn(id)">
                                        @else
                                            <input type="button" class="btn btn-success" value="已审核"
                                                   disabled="disabled">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$sps->links()}}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('view.admin.sp.title') . ' ' . trans('view.admin.sp.result') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('view.admin.sp.sure_to_submit') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('view.admin.public.close') }}</button>
                    <button type="button" class="btn btn-danger" onclick="event.preventDefault();
                    document.getElementById('submit-form').submit();">{{ trans('view.admin.sp.confirm') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <form id="submit-form" action="" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input id="result" type="text" name="result" style="display: none">
        <input id="spid" type="text" name="spid" style="display: none">

    </form>
    <script>
        function check_btn(id) {
            var arr = id.split('-');
            if ('pass' == arr[0]) {
                $("#result").val('pass');
                $("#spid").val(arr[1]);
            } else {
                $("#result").val('reject');
                $("#spid").val(arr[1]);
            }
        }

    </script>


@endsection