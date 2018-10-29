@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.area.title') }}
                        <a href="{{ route('admin.area.create') }}" style="float: right;" class="btn btn-info btn-sm "
                           onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.create') .' '. trans('view.admin.area.title') }}</a>
                    </div>
                    <div class="panel-body">
                        @if($area->count() == 0)
                            @include('vendor.speedy.layouts.null_page')
                        @else
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ trans('view.admin.area.name') }}</th>
                                    <th>{{ trans('view.admin.area.manager') }}</th>
                                    <th>{{ trans('view.admin.area.shop_num') }}</th>
                                    <th>{{ trans('view.admin.public.created_at') }}</th>
                                    <th>{{ trans('view.admin.public.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($area as $v)
                                    <tr>
                                        <td>{{ $v->name }}</td>
                                        <td>{{ $v->hasOneManager->display_name ? $v->hasOnemanager->display_name :'未填写'}}</td>
                                        <td>{{ $v->hasManyShops->count() }}</td>
                                        <td>{{ $v->created_at }}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                               href="{{ route('admin.area.edit', ['id' => $v->id]) }}"
                                               onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.edit') }}</a>
                                            <a class="btn btn-danger btn-sm" href="javascript:;"
                                               onclick="document.getElementById('delete-form').action = '{{ route('admin.area.index') . "/{$v->id}" }}'"
                                               data-toggle="modal"
                                               data-target="#deleteModal">{{ trans('view.admin.public.destroy') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$area->links()}}
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
                    <h4 class="modal-title">{{ trans('view.admin.public.destroy') . ' ' . trans('view.admin.area.title') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('view.admin.area.sure_to_delete') }}</p>
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