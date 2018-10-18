@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.shop.title') }}
                        <a href="{{ route('admin.shop.create') }}" style="float: right;" class="btn btn-info btn-sm" onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.create') .' '. trans('view.admin.shop.title') }}</a>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('view.admin.shop.name') }}</th>
                                    <th>{{ trans('view.admin.shop.manager') }}</th>
                                    <th>{{ trans('view.admin.shop.area') }}</th>
                                    <th>{{ trans('view.admin.shop.address') }}</th>
                                    <th>{{ trans('view.admin.shop.discount') }}</th>
                                    <th>{{ trans('view.admin.public.created_at') }}</th>
                                    <th>{{ trans('view.admin.public.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shops as $shop)
                                <tr>
                                    <td>{{ $shop->name }}</td>
                                    <td>{{ $shop->hasOneManager ? $shop->hasOnemanager->display_name :'-'}}</td>
                                    <td>{{ $shop->belongsToArea ? $shop->belongsToArea->name : '-' }}</td>
                                    <td>{{ $shop->address }}</td>
                                    <td>{{ $shop->discount? ($shop->discount/100).'元' :'-' }}</td>
                                    <td>{{ $shop->created_at }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm" href="{{ route('admin.shop.edit', ['id' => $shop->id]) }}" onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.edit') }}</a>
                                        <a class="btn btn-danger btn-sm" href="javascript:;" onclick="document.getElementById('delete-form').action = '{{ route('admin.shop.index') . "/{$shop->id}" }}'" data-toggle="modal" data-target="#deleteModal">{{ trans('view.admin.public.destroy') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$shops->links()}}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('view.admin.public.destroy') . ' ' . trans('view.admin.shop.title') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('view.admin.shop.sure_to_delete') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('view.admin.public.close') }}</button>
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