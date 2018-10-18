@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.public.' . (isset($equip) ? 'edit' : 'create')) . ' ' . trans('view.admin.equip.title') }}</div>
                    <form method="post"
                          action="{{ isset($equip) ? route('admin.equip.update', ['id' => $equip->id]) :  route('admin.equip.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ isset($equip) ? method_field('PUT') : '' }}
                            <div class="form-group">
                                <label>{{ trans('view.admin.equip.name') }}</label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="{{ trans('view.admin.equip.name') }}"
                                       value="{{ isset($equip) ? $equip->name : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.equip.uuid') }}</label>
                                <input type="text" name="verify_code" class="form-control"
                                       placeholder="{{ trans('view.admin.equip.uuid') }}"
                                       value="{{ isset($equip) ? $equip->verify_code : '' }}"
                                        {{ isset($equip) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.equip.type') }}</label>
                                <select name="type" class="form-control" {{ isset($equip) ? 'disabled="disabled"':''}}>
                                    <option value="1">{{ trans('view.admin.equip.money') }}</option>
                                    <option value="2">{{ trans('view.admin.equip.staff') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.equip.type') }}</label>
                                <select name="status"
                                        class="form-control" {{ isset($equip) ? '':'disabled="disabled"'}}>
                                    @if(isset($equip))
                                        @switch($equip->status)
                                            @case (2)
                                            <option value="2" selected>{{ trans('view.admin.equip.logout') }}</option>
                                            <option value="4">{{ trans('view.admin.equip.holdon') }}</option>
                                            <option value="5">{{ trans('view.admin.equip.error') }}</option>
                                            @break
                                            @case (4)
                                            <option value="2">{{ trans('view.admin.equip.logout') }}</option>
                                            <option value="4" selected>{{ trans('view.admin.equip.holdon') }}</option>
                                            <option value="5">{{ trans('view.admin.equip.error') }}</option>
                                            @break
                                            @case (5)
                                            <option value="2">{{ trans('view.admin.equip.logout') }}</option>
                                            <option value="4">{{ trans('view.admin.equip.holdon') }}</option>
                                            <option value="5" selected>{{ trans('view.admin.equip.error') }}</option>
                                            @break
                                            @default
                                            <option value="2">{{ trans('view.admin.equip.logout') }}</option>
                                            <option value="4">{{ trans('view.admin.equip.holdon') }}</option>
                                            <option value="5">{{ trans('view.admin.equip.error') }}</option>
                                            @break
                                        @endswitch
                                        @else
                                        <option value="2" selected>{{ trans('view.admin.equip.logout') }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.equip.shop') }}</label>
                                <select name="shops_id" class="form-control">
                                    <option value="">{{ trans('view.admin.public.none') }}</option>
                                    @foreach($shop as $shop)
                                        <option value="{{ $shop->id }}" {{ isset($equip) ? ($shop->id === $equip->shops_id ? 'selected' : '') : '' }}>{{ $shop->name }}</option>
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