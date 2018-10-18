@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.line.title') }}
                    </div>
                    <div class="panel-body">
                        <div class="panel-default">
                            <form method="post"
                                  action="{{ route('admin.line.search')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>请选择查询开始时间</label>
                                    <input type="date" name="start_datetime" class="form-control"
                                           placeholder="{{ trans('view.admin.line.start_time') }}"
                                           value="{{ isset($start_date) ? $start_date:'' }}" required>
                                    <label>请选择查询结束时间</label>
                                    <input type="date" name="end_datetime" class="form-control"
                                           placeholder="{{ trans('view.admin.line.end_time') }}"
                                           value="{{ isset($end_date) ? $end_date:'' }}" required>
                                    <input type="text" name="shop" value="{{$users[0]->shops_id}}" style="display: none;">
                                    <button type="submit" class="btn btn-sm btn-success"
                                            style="float: right;margin-top: 10px;">查询
                                    </button>
                                </div>
                            </form>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('view.admin.line.shop') }}</th>
                                <th>{{ trans('view.admin.line.staff_name') }}</th>
                                <th>{{ trans('view.admin.line.role') }}</th>
                                <th>{{ trans('view.admin.line.order_num') }}</th>
                                <th>{{ trans('view.admin.line.efficiency') }}</th>
                                <th>{{ trans('view.admin.line.profit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $v)
                                <tr>
                                    <td>{{ $v->belongsToShop? $v->belongsToShop->name:'-' }}</td>
                                    <td>{{ $v->display_name }}</td>
                                    <td>{{$v->role_id == '4' ? '店长':'员工'}}</td>
                                    <td>{{ $v->totalOrders }}</td>
                                    <td>{{ $v->efficiency }}</td>
                                    <td>{{ $v->totalProfit /100 }}元</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$users->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection