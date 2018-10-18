@extends('vendor.speedy.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('vendor.speedy.partials.alert')
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('view.admin.public.' . (isset($area) ? 'edit' : 'create')) . ' ' . trans('view.admin.area.title') }}</div>
                    <form method="post" action="{{ isset($area) ? route('admin.area.update', ['id' => $area->id]) :  route('admin.area.store') }}">
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ isset($area) ? method_field('PUT') : '' }}
                            <div class="form-group">
                                <label>{{ trans('view.admin.area.name') }}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ trans('view.admin.area.name') }}" value="{{ isset($area) ? $area->name : '' }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.area.user_id') }}</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">{{ trans('view.admin.public.none') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ isset($area) ? ($user->id === $area->user_id ? 'selected' : '') : '' }}>{{ $user->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('view.admin.area.shop') }}</label>
                                <div class="checkbox">
                                    <?php if(isset($area)){$shops_id = $area->hasManyShops->pluck('id')->toArray();}?>
                                    @foreach($shop as $shop)
                                        <label class="btn btn-outline-dark btn-sm">
                                            <input name="shop_id[]" value="{{ $shop->id }}" type="checkbox" {{ isset($shops_id) && in_array($shop->id, $shops_id) ? 'checked' : '' }}>{{ $shop->name }}
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