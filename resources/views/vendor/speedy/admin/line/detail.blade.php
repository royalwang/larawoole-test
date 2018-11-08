@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">{{ trans('view.admin.line.title') }}
                    </div>
                    <div class="panel-body">
                        <form method="post"
                              action="{{ route('admin.line.usersearch')}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-group">
                                <div style="width: 60px;height: 30px;line-height: 30px;">开始时间</div>
                                <div style="width: 200px;">
                                    <input type="date" name="start_datetime" class="form-control "
                                           placeholder="{{ trans('view.admin.line.start_time') }}"
                                           value="{{ isset($start_date) ? $start_date:'' }}" required>
                                </div>
                                <div style="width: 60px;height: 30px;line-height: 30px;">结束时间</div>
                                <div style="width: 200px;">
                                    <input type="date" name="end_datetime" class="form-control "
                                           placeholder="{{ trans('view.admin.line.end_time') }}"
                                           value="{{ isset($end_date) ? $end_date:'' }}" required>
                                </div>
                                <div style="display: none;">
                                    <input type="text" name="shop_id"
                                           placeholder=""
                                           value="{{ $users[0]->shops_id }}">
                                </div>
                                <div style="margin-top: 10px;">
                                    <button type="submit" class="btn btn-info" style="background-color: #00a0e8;">查询
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered table-hover" style="text-align: center">
                        <thead>
                        <tr class="active" style="text-align: center">
                            <th style="text-align: center">{{ trans('view.admin.line.shop') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.line.staff_name') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.line.role') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.line.order_num') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.line.efficiency') }}</th>
                            <th style="text-align: center">{{ trans('view.admin.line.profit') }}</th>
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
                    <div class="panel-footer">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection