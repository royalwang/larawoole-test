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
                                           value="{{ $start_date ? $start_date:\Illuminate\Support\Carbon::today()->toDateString() }}"
                                           required>
                                    <label>请选择查询结束时间</label>
                                    <input type="date" name="end_datetime" class="form-control"
                                           placeholder="{{ trans('view.admin.line.end_time') }}"
                                           value="{{ $end_date ? $end_date:\Illuminate\Support\Carbon::tomorrow()->toDateString() }}"
                                           required>
                                    <input type="text" name="view" value="index" style="display: none;">
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
                                <th>{{ trans('view.admin.line.manager') }}</th>
                                <th>{{ trans('view.admin.line.staff') }}</th>
                                <th>{{ trans('view.admin.line.order_num') }}</th>
                                <th>{{ trans('view.admin.line.profit') }}</th>
                                <th>{{ trans('view.admin.public.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shops as $shop)
                                <tr>
                                    <td>{{ $shop->name }}</td>
                                    <td>{{ $shop->hasOneManager ? $shop->hasOneManager->display_name:'-' }}</td>
                                    <td>{{ $shop->hasManyUsers->count() }}</td>
                                    <td>{{ $shop->doneOrders }}</td>
                                    <td>{{ $shop->totalProfit /100 }}元</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                           href="{{ route('admin.line.detail', [
                                           'id' => $shop->id,
                                           'start_date' =>$start_date ? $start_date:\Illuminate\Support\Carbon::today()->toDateTimeString() ,
                                           'end_date' =>$end_date ? $end_date:\Illuminate\Support\Carbon::tomorrow()->toDateTimeString(),
                                           ]) }}"
                                           onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.line.goto') }}</a>
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

@endsection