@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-info">
                    <div class="panel-heading">{{ trans('view.admin.public.' . (isset($shop) ? 'edit' : 'create')) . ' ' . trans('view.admin.shop.title') }}</div>
                    <form method="post"
                          action="{{ isset($shop) ? route('admin.shop.update', ['id' => $shop->id]) :  route('admin.shop.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ isset($shop) ? method_field('PUT') : '' }}
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.shop.name') }}</label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="{{ trans('view.admin.shop.name') }}"
                                       value="{{ isset($shop) ? $shop->name : '' }}">
                            </div>
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.shop.address') }}
                                </label>
                                <input type="text" name="address" class="form-control"
                                       placeholder="{{ trans('view.admin.shop.address') }}"
                                       value="{{ isset($shop) ? $shop->address : '' }}">
                            </div>
                            @if(isset($shop))
                                <div class="form-group">
                                    <label><span style="color: red;"> * </span>{{ trans('view.admin.shop.city') }}
                                    </label>
                                    <input type="text" name="city" class="form-control"
                                           placeholder="{{ trans('view.admin.shop.city') }}"
                                           value="{{ isset($shop) ? $shop->hasOneCity->city : '' }}" readonly>
                                </div>
                            @else
                                <div class="form-group">
                                    <label><span style="color: red;"> * </span>{{ trans('view.admin.shop.city') }}
                                    </label>
                                    <select name="city" class="form-control">
                                        <option value="">{{ trans('view.admin.public.none') }}</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" }}>{{ $city->city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <label><span style="color: red;"> * </span>{{ trans('view.admin.shop.discount') }}(单位:元)</label>
                                <input type="text" name="discount" class="form-control"
                                       placeholder="{{ trans('view.admin.shop.discount') }}"
                                       value="{{ isset($shop) ? substr(sprintf("%.2f",($shop->discount)/100),0,-1) : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.shop.establish_time') }}</label>
                                <input type="date" name="establish_time" class="form-control"
                                       placeholder="{{ trans('view.admin.shop.establish_time') }}"
                                       value="{{ isset($shop) ? $shop->establish_time : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.shop.manager_id') }}</label>
                                <select name="manager_id" class="form-control">
                                    <option value="">{{ trans('view.admin.public.none') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ isset($shop) ? ($user->id === $shop->manager_id ? 'selected' : '') : '' }}>{{ $user->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-info">{{ trans('view.admin.public.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection