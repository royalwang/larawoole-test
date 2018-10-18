@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.public.edit') . ' ' . trans('view.admin.user.title') }}</div>
                    <form method="post"
                          action="{{ isset($user) ? route('admin.personal.update', ['id' => $user->id]) :  route('admin.personal.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ isset($user) ? method_field('PUT') : '' }}
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.name') }}</label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="{{ trans('view.admin.user.name') }}"
                                       value="{{ isset($user) ? $user->name : '' }}" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.work_id') }}</label>
                                <input type="text" name="work_id" class="form-control"
                                       placeholder="{{ trans('view.admin.user.work_id') }}"
                                       value="{{ isset($user) ? $user->work_id : '' }}" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.display_name') }}</label>
                                <input type="text" name="display_name" class="form-control"
                                       placeholder="{{ trans('view.admin.user.display_name') }}"
                                       value="{{ isset($user) ? $user->display_name : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.email') }}</label>
                                <input type="email" name="email" class="form-control"
                                       placeholder="{{ trans('view.admin.user.email') }}"
                                       value="{{ isset($user) ? $user->email : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.password') }}</label>
                                <input maxlength="6" name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="{{ trans('view.admin.user.password') }}">
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit"
                                    class="btn btn-success">{{ trans('view.admin.public.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection