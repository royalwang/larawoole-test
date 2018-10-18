@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.work.title') }}
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('view.admin.work.shop') }}</th>
                                <th>{{ trans('view.admin.work.staff_name') }}</th>
                                <th>{{ trans('view.admin.work.status') }}</th>
                                <th>{{ trans('view.admin.work.doing_order_num') }}</th>
                                <th>{{ trans('view.admin.work.today_order_num') }}</th>
                                <th>{{ trans('view.admin.work.month_order_num') }}</th>
                                <th>{{ trans('view.admin.work.day') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->belongsToShop? $user->belongsToShop->name :'-'}}</td>
                                    <td>{{ $user->display_name }}</td>
                                    @if($user->status == '0')
                                        <td>{{trans('view.admin.work.logout') }}</td>
                                    @elseif($user->status == '1')
                                        <td>{{trans('view.admin.work.onwork') }}</td>
                                    @else
                                        <td>{{trans('view.admin.work.hangon') }}</td>
                                    @endif
                                    <td>{{ $user->hasOneDoingOrder ? $user->hasOneDoingOrder->order_num:'-' }}</td>
                                    <td>{{ $user->todayOrders }}</td>
                                    <td>{{ $user->monthOrders }}</td>
                                    <td>{{ $user->workday? $user->workday:'-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection