@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.sp.title') }}
                    </div>
                    <div class="panel-body">
                        @if($shops == null || $shops->count() == 0)m
                        @include('vendor.speedy.layouts.null_page')
                        @else
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ trans('view.admin.sp.shop') }}</th>
                                    <th>{{ trans('view.admin.sp.num') }}</th>
                                    <th>{{ trans('view.admin.sp.updated_at') }}</th>
                                    <th>{{ trans('view.admin.public.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shops as $shop)
                                    <tr>
                                        <td>{{ $shop->name }}</td>
                                        <td>{{ $shop->hasManySp->where('status','0')->count().'/'. $shop->hasManySp->count()}}</td>
                                        @if($shop->hasManySp->count())
                                            @foreach($shop->hasManySp as $sp)
                                                <td>{{$sp->updated_at}}</td>
                                                @break
                                            @endforeach
                                        @else
                                            <td>无</td>
                                        @endif
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                               href="{{ route('admin.sp.detail', ['sid' => $shop->id]) }}"
                                               onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.sp.goto') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$shops->links()}}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection