@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.public.' . (isset($user) ? 'edit' : 'create')) . ' ' . trans('view.admin.user.title') }}</div>
                    <form method="post"
                          action="{{ isset($user) ? route('admin.user.update', ['id' => $user->id]) :  route('admin.user.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ isset($user) ? method_field('PUT') : '' }}
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.user.name') }}</label>
                                @if(isset($user))
                                    <input type="text" name="name" class="form-control"
                                           placeholder="{{ trans('view.admin.user.name') }}"
                                           value="{{ isset($user) ? $user->name : '' }}" readonly>
                                @else
                                    <input type="text" name="name" class="form-control"
                                           placeholder="{{ trans('view.admin.user.name') }}"
                                           value="{{ isset($user) ? $user->name : '' }}">
                                @endif
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.user.display_name') }}
                                </label>
                                <input type="text" name="display_name" class="form-control"
                                       placeholder="{{ trans('view.admin.user.display_name') }}"
                                       value="{{ isset($user) ? $user->display_name : '' }}">
                            </div>
                            @if(isset($user))
                                <div class="form-group">
                                    <label><span style="color: red;"> * </span>{{ trans('view.admin.user.work_id') }}
                                    </label>
                                    <input type="text" name="work_id" class="form-control"
                                           placeholder="{{ trans('view.admin.user.work_id') }}"
                                           value="{{ isset($user) ? $user->work_id : '' }}" readonly>
                                </div>
                            @endif
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.user.identity') }}
                                </label>
                                <input type="text" name="identity" class="form-control"
                                       placeholder="{{ trans('view.admin.user.identity') }}"
                                       value="{{ isset($user) ? $user->identity : '' }}">
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.user.sex') }}</label>
                                <select name="sex" class="form-control">
                                    @if(isset($user->sex ))
                                        @if($user->sex == '0')
                                            <option value="0" selected>男</option>
                                            <option value="1">女</option>
                                        @else
                                            <option value="0">男</option>
                                            <option value="1" selected>女</option>
                                        @endif
                                    @else
                                        <option value="0">男</option>
                                        <option value="1">女</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>请选择入职时间</label>
                                <input type="date" name="hire_date" class="form-control"
                                       placeholder="{{ trans('view.admin.user.hire_date') }}"
                                       value="{{ isset($user) ? $user->hire_date:'' }}" required>
                            </div>
                            <div class="form-group">
                                @if(isset($user))
                                    <label>{{ trans('view.admin.user.password') }}</label>
                                @else
                                    <label><span style="color: red;"> * </span>{{ trans('view.admin.user.password') }}
                                    </label>
                                @endif
                                <input maxlength="6" minlength="6" name="password" type="password"
                                       class="form-control"
                                       id="exampleInputPassword1"
                                       placeholder="{{ trans('view.admin.user.password') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.shop') }}</label>
                                <select name="shop_id" class="form-control">
                                    @if(isset($user->shops_id))
                                        @foreach($shops as $shop)
                                            @if($user->shops_id == $shop->id)
                                                <option value="{{ $shop->id }}" selected>{{$shop->name}}</option>
                                            @else
                                                <option value="{{ $shop->id }}">{{$shop->name}}</option>
                                            @endif
                                        @endforeach
                                        <option value="false">{{ trans('view.admin.public.none') }}</option>
                                    @else
                                        <option value="false">{{ trans('view.admin.public.none') }}</option>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id }}">{{$shop->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.email') }}</label>
                                <input type="email" name="email" class="form-control"
                                       placeholder="{{ trans('view.admin.user.email') }}"
                                       value="{{ isset($user) ? $user->email : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.user.role') }}</label>
                                <select name="role_id" class="form-control">
                                    <option value="">{{ trans('view.admin.public.none') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ isset($user) ? ($role->id === $user->role_id ? 'selected' : '') : '' }}>{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
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