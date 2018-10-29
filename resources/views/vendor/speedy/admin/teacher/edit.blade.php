@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.public.edit') . ' ' . trans('view.admin.teacher.title') }}</div>
                    <form method="post" action="{{ isset($teacher) ? route('admin.teacher.update', ['id' => $teacher->id]) :  route('admin.teacher.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ isset($teacher) ? method_field('PUT') : '' }}
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.teacher.manager') }} <span style="font-size: 12px;color: red;">(最多5人)</span></label>
                                <div class="checkbox">
                                    <?php if(isset($teacher)){$users_id = $teacher->hasManyShop->pluck('manager_id')->toArray();}?>
                                    @foreach($users as $user)
                                        <label class="btn btn-outline-dark btn-sm">
                                            <input name="user_id[]" value="{{ $user->id }}" type="checkbox" {{ isset($users_id) && in_array($user->id, $users_id) ? 'checked' : '' }}>
                                            {{$user->display_name.' --'}}
                                            {{$user->belongsToShop ? $user->belongsToShop->name :'无门店'}}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer"><button type="submit" class="btn btn-success">{{ trans('view.admin.public.submit') }}</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection