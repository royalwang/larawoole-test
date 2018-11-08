@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">{{ trans('view.admin.teacher.title') }}
                    </div>
                    @if($teachers->count() == 0)
                        @include('vendor.speedy.layouts.null_page')
                    @else
                        <table class="table table-bordered table-hover" style="text-align: center">
                            <thead>
                            <tr class="active" style="text-align: center">
                                <th style="text-align: center">{{ trans('view.admin.teacher.display_name') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.teacher.under_sum') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.public.created_at') }}</th>
                                <th style="text-align: center">{{ trans('view.admin.public.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->belongsToUser->display_name }}</td>
                                    <td>{{ $teacher->under_sum }}个</td>
                                    <td>{{ $teacher->created_at }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                           href="{{ route('admin.teacher.edit', ['id' => $teacher->id]) }}"
                                           onclick="$('.spinner').fadeIn(50);">{{ trans('view.admin.teacher.edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="panel-footer">{{ $teachers->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection