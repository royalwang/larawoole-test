@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">{{ trans('view.admin.work.title') }}
                    </div>
                    <table class="table table-bordered table-hover" style="text-align: center">
                        <thead>
                        <tr class="active" style="text-align: center">
                            <th style="text-align: center">{{ trans('view.admin.work.shop') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.work.manager') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.work.staff_num') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.work.today_staff') }}
                            <th style="text-align: center">{{ trans('view.admin.work.today_order_num') }}
                            <th style="text-align: center">{{ trans('view.admin.work.profit') }}
                            <th style="text-align: center">{{ trans('view.admin.work.waiting') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.work.hint') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shops as $shop)
                            <tr>
                                <td>{{ $shop->name }}</td>
                                <td>{{ $shop->hasOneManager ? $shop->hasOneManager->display_name:'-' }}</td>
                                <td>{{ $shop->hasManyUsers->count() }}</td>
                                <td>{{ $shop->onWork }}</td>
                                <td>{{ $shop->doneOrders }}</td>
                                <td>{{ $shop->todayProfit/100 }}</td>
                                <td>{{ $shop->waitingOrders }}</td>
                                @if($shop->waitingOrders<3)
                                    <td>
                                        <button class="btn btn-success btn-sm"
                                                disabled>{{ trans('view.admin.work.green') }}</button>
                                    </td>
                                @elseif($shop->waitingOrders<= 5)
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                                disabled>{{ trans('view.admin.work.yellow') }}</button>
                                    </td>
                                @else
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                                disabled>{{ trans('view.admin.work.red') }}</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="panel-footer">{{ $shops->links() }}</div>
                </div>
            </div>
        </div>
    </div>


@endsection