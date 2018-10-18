@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-heading ">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('view.admin.teacher.display_name') }}</th>
                                <th>{{ trans('view.admin.teacher.under_sum') }}</th>
                                <th>{{ trans('view.admin.public.created_at') }}</th>
                                <th>{{ trans('view.admin.public.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->belongsToUser->display_name }}</td>
                                    <td>{{ $teacher->under_sum }}个</td>
                                    <td>{{ $teacher->created_at }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm" href="{{ route('admin.teacher.edit', ['id' => $teacher->id]) }}" onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.teacher.edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$teachers->links()}}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection