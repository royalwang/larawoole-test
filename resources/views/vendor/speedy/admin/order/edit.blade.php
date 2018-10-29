@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.order.title') }}
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('view.admin.order.num') }}</th>
                                <th>{{ trans('view.admin.order.get_type') }}</th>
                                <th>{{ trans('view.admin.order.pay_type') }}</th>
                                <th>{{ trans('view.admin.order.create_time') }}</th>
                                <th>{{ trans('view.admin.order.pay_time') }}</th>
                                <th>{{ trans('view.admin.order.wait_num') }}</th>
                                <th>{{ trans('view.admin.order.status') }}</th>
                                <th>{{ trans('view.admin.order.shop') }}</th>
                                <th>{{ trans('view.admin.order.price') }}</th>
                                <th>{{ trans('view.admin.order.sex') }}</th>
                                <th>{{ trans('view.admin.order.age') }}</th>
                                <th>{{ trans('view.admin.order.if_get') }}</th>
                                <th>{{ trans('view.admin.order.get_time') }}</th>
                                <th>{{ trans('view.admin.order.user') }}</th>
                                <th>{{ trans('view.admin.order.finish') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $order->order_num }}</td>
                                <td>{{ $order->get_type === '0'? trans('view.admin.order.wechat'):trans('view.admin.order.alipay') }}</td>
                                <td>{{ $order->pay_type === '0'? trans('view.admin.order.noget'):trans('view.admin.order.hasget')}}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ $order->pay_time ?  $order->pay_time : '-'}}</td>
                                <td>{{ $order->wait_num ? $order->wait_num : '-'}}</td>
                                <td>{{ $order->order_status ? $order->order_status:'-' }}</td>
                                <td>{{ $order->belongsToShop ? $order->belongsToShop->name:'-' }}</td>
                                <td>{{ $order->price /100}}元</td>
                                <td>{{ $order->sex ?  $order->sex : '-' }}</td>
                                <td>{{ $order->age? $order->age : '-' }}</td>
                                <td>{{ $order->if_get === '0' ? '未取票':'已取票' }}</td>
                                <td>{{ $order->get_time ? $order->get_time:'-'}}</td>
                                <td>{{ $order->belongsToUser? $order->belongsToUser->display_name:'-'}}</td>
                                @switch($order->status)
                                    @case('0')
                                    <td>{{ trans('view.admin.order.undo') }}</td>
                                    @break
                                    @case('1')
                                    <td>{{ trans('view.admin.order.todo') }}</td>
                                    @break
                                    @case('2')
                                    <td>{{ trans('view.admin.order.done') }}</td>
                                    @break
                                @endswitch
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection