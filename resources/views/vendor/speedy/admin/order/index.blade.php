@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">{{ trans('view.admin.order.title') }}
                    </div>
                    @if($orders == null || $orders->count() == 0)
                        @include('vendor.speedy.layouts.null_page')
                    @else
                        <table class="table table-bordered table-hover" style="text-align: center">
                            <thead>
                            <tr class="active" style="text-align: center">
                                <th style="text-align: center">{{ trans('view.admin.order.num') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.get_type') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.pay_type') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.create_time') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.pay_time') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.price') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.shop') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.status') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.wait_num') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.order.goto') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $v)
                                <tr>
                                    <td>{{ $v->order_num }}</td>
                                    <td>{{ $v->get_type === '0' ? '现场取票':'网上预定' }}</td>
                                    <td>{{ $v->pay_type === '1' ? '支付宝':'微信' }}</td>
                                    <td>{{ $v->created_at}}</td>
                                    <td>{{ $v->pay_time}}</td>
                                    <td>{{ $v->price /100}}元</td>
                                    <td>{{ $v->belongsToShop ? $v->belongsToShop->name : '-'}}</td>
                                    <td>{{ $v->order_status ? $v->order_status : '-'}}</td>
                                    <td>{{ $v->wait_num ? $v->wait_num : '-'}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                           href="{{ route('admin.order.edit', ['id' => $v->id]) }}"
                                           onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.public.detail') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="panel-footer">{{ $orders->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection