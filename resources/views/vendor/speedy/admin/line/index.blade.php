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
                              action="{{ route('admin.line.search')}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-group">
                                <div style="width: 60px;height: 30px;line-height: 30px;">开始时间</div>
                                <div style="width: 200px;">
                                    <input type="date" name="start_datetime" class="form-control "
                                           placeholder="{{ trans('view.admin.line.start_time') }}"
                                           value="{{ $start_date ? $start_date:\Illuminate\Support\Carbon::today()->toDateString() }}" required>
                                </div>
                                <div style="width: 60px;height: 30px;line-height: 30px;">结束时间</div>
                                <div style="width: 200px;">
                                    <input type="date" name="end_datetime" class="form-control "
                                           placeholder="{{ trans('view.admin.line.end_time') }}"
                                           value="{{ $end_date ? $end_date:\Illuminate\Support\Carbon::tomorrow()->toDateString() }}" required>
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
                                <th style="text-align: center">{{ trans('view.admin.line.manager') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.line.staff') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.line.order_num') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.line.profit') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.public.action') }}</th>
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
                                        <a class="btn btn-info btn-sm"
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
                    <div class="panel-footer">{{ $shops->links() }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection