@extends('layouts.app')
@section('title')
    @if (!empty($title))
        {{ $title }} |
    @endif @parent
@endsection

@section('page-css')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker-1.6.4/css/bootstrap-datepicker3.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div id="wrapper">

            @include('admin.sidebar_menu')

            <div id="page-wrapper">
                <div id="post-new-ad">
                    <div class="row">
                        <div class="col-md-10 col-xs-12">

                            @if (!\Auth::check())
                                <div class="alert alert-info no-login-info">
                                    <p> <i class="fa fa-info-circle"></i> @lang('app.ad_add_items')</p>
                                </div>
                            @endif
                            <div class="alert alert-info">
                                <p>Double Click on the images to edit them.</p>
                            </div>

                            @include('admin.flash_msg')

                            <form action="{{ route('update_item', $item->id) }}" id="adsPostForm" class="form-horizontal"
                                method="post" enctype="multipart/form-data"> @csrf

                                <legend> <span class="ad_text"> @lang('app.item') </span> @lang('app.info')</legend>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group {{ $errors->has('item_name') ? 'has-error' : '' }}">
                                    <label for="item_name" class="col-sm-4 control-label">
                                        <span class="ad_text">
                                            @lang('app.item')
                                        </span>
                                        @lang('app.name')
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="item_name"
                                            value="{{ old('item_name', $item->item_name) }}" name="item_name"
                                            placeholder="@lang('app.item_name')" maxlength="50" required>
                                        {!! $errors->has('item_name') ? '<p class="help-block">' . $errors->first('item_name') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('detailed_description') ? 'has-error' : '' }}">
                                    <label class="col-sm-4 control-label"><span class="ad_text"> @lang('app.item')
                                        </span>
                                        @lang('app.description')</label>
                                    <div class="col-sm-8">
                                        <textarea name="detailed_description" class="form-control" id="content_editor" rows="8">{{ old('detailed_description', $item->detailed_description) }}</textarea>
                                        {!! $errors->has('detailed_description')
                                            ? '<p class="help-block">' . $errors->first('detailed_description') . '</p>'
                                            : '' !!}
                                        <p class="text-info"> @lang('app.ad_description_info_text')</p>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('internalCatalogNumber') ? 'has-error' : '' }}">
                                    <label for="internalCatalogNumber" class="col-sm-4 control-label">
                                        <span class="ad_text">
                                            @lang('app.internalCatalogNumber')
                                        </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="internalCatalogNumber"
                                            name="internalCatalogNumber" placeholder="@lang('app.internalCatalogNumber')"
                                            value="{{ old('internalCatalogNumber', $item->internalCatalogNumber) }}"
                                            required readonly>
                                        {!! $errors->has('internalCatalogNumber')
                                            ? '<p class="help-block">' . $errors->first('internalCatalogNumber') . '</p>'
                                            : '' !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('item_type') ? 'has-error' : '' }}">
                                    <label for="item_type" class="col-sm-4 control-label">@lang('app.item')
                                        @lang('app.type')</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" name="item_type" required>
                                            <option value="">@lang('app.select_item_type')</option>
                                            <option value="A"
                                                {{ old('item_type', $item->item_type) == 'A' ? 'selected' : '' }}>A
                                            </option>
                                            <option value="B"
                                                {{ old('item_type', $item->item_type) == 'B' ? 'selected' : '' }}>B
                                            </option>
                                        </select>
                                        {!! $errors->has('item_type') ? '<p class="help-block">' . $errors->first('item_type') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('item_size') ? 'has-error' : '' }}">
                                    <label for="item_size" class="col-sm-4 control-label">
                                        <span class="ad_text">
                                            @lang('app.item')
                                        </span>
                                        @lang('app.size')
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="item_size"
                                            value="{{ old('item_size', $item->item_size) }}" name="item_size"
                                            placeholder="@lang('app.item_size')" required>
                                        {!! $errors->has('item_size') ? '<p class="help-block">' . $errors->first('item_size') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('item_weight') ? 'has-error' : '' }}">
                                    <label for="item_weight" class="col-md-4 control-label"> <span
                                            class="price_text">@lang('app.item_weight')</span> </label>
                                    <div class="col-md-4">
                                        <input type="number" placeholder="@lang('app.item_weight')" class="form-control"
                                            name="item_weight" id="item_weight"
                                            value="{{ old('item_weight', $item->item_weight) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="weight_unit" required>
                                            <option value="">@lang('app.select_weight_unit')</option>
                                            <option value="A"
                                                {{ old('weight_unit', $item->weight_unit) == 'A' ? 'selected' : '' }}>A
                                            </option>
                                            <option value="B"
                                                {{ old('weight_unit', $item->weight_unit) == 'B' ? 'selected' : '' }}>B
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-sm-8 col-md-offset-4">
                                        {!! $errors->has('item_weight') ? '<p class="help-block">' . $errors->first('item_weight') . '</p>' : '' !!}
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->has('weight_unit') ? '<p class="help-block">' . $errors->first('weight_unit') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('metal_type') ? 'has-error' : '' }}">
                                    <label for="metal_type" class="col-sm-4 control-label">@lang('app.metal')
                                        @lang('app.type')</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" name="metal_type" required>
                                            <option value="">@lang('app.select_metal_type')</option>
                                            <option value="A"
                                                {{ old('metal_type', $item->metal_type) == 'A' ? 'selected' : '' }}>A
                                            </option>
                                            <option value="B"
                                                {{ old('metal_type', $item->metal_type) == 'B' ? 'selected' : '' }}>B
                                            </option>
                                        </select>
                                        {!! $errors->has('metal_type') ? '<p class="help-block">' . $errors->first('metal_type') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('metal_color') ? 'has-error' : '' }}">
                                    <label for="metal_color" class="col-sm-4 control-label">@lang('app.metal_color')</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="metal_color"
                                            value="{{ old('metal_color', $item->metal_color) }}" name="metal_color"
                                            placeholder="@lang('app.metal_color')" required>
                                        {!! $errors->has('metal_color') ? '<p class="help-block">' . $errors->first('metal_color') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('total_gem_weight') ? 'has-error' : '' }}">
                                    <label for="total_gem_weight"
                                        class="col-sm-4 control-label">@lang('app.total_gem_weight')</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="total_gem_weight"
                                            value="{{ old('total_gem_weight', $item->total_gem_weight) }}"
                                            name="total_gem_weight" placeholder="@lang('app.total_gem_weight')" required>
                                        {!! $errors->has('total_gem_weight')
                                            ? '<p class="help-block">' . $errors->first('total_gem_weight') . '</p>'
                                            : '' !!}
                                    </div>
                                </div>

                                <legend>@lang('app.stone') @lang('app.info') (@lang('app.optional'))</legend>

                                <div id="more-stones-fields">
                                    <input type="hidden" class="total_stones_count" value="{{ $stone_count }}">
                                    @if ($item->stones && $item->stones->count() > 0)
                                        @foreach ($item->stones as $key => $stone)
                                            <div style="width: 100%; display:flex; justify-content:end">
                                                <button class="btn btn-warning delete_item_stone"
                                                    style="margin-bottom: 1rem;" type="button"
                                                    data-stone_id="{{ $stone->id }}">Delete Stone</button>
                                            </div>
                                            <div class="form-group  {{ $errors->has('stone_type') ? 'has-error' : '' }}">
                                                <input type="hidden" name="stone_id[]" value="{{ $stone->id }}">
                                                <label for="stone_type" class="col-sm-4 control-label">@lang('app.stone')
                                                    @lang('app.type')</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2" name="stone_type[]">
                                                        <option value="">@lang('app.select_stone_type')</option>
                                                        <option value="A"
                                                            {{ old('metal_type' . $key, $stone->stone_type) == 'A' ? 'selected' : '' }}>
                                                            A
                                                        </option>
                                                        <option value="B"
                                                            {{ old('metal_type' . $key, $stone->stone_type) == 'B' ? 'selected' : '' }}>
                                                            B
                                                        </option>
                                                    </select>
                                                    {!! $errors->has('stone_type') ? '<p class="help-block">' . $errors->first('stone_type') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div class="form-group {{ $errors->has('stone_shape') ? 'has-error' : '' }}">
                                                <label for="stone_shape" class="col-sm-4 control-label"> <span
                                                        class="ad_text">
                                                        @lang('app.stone')
                                                    </span> @lang('app.shape')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="stone_shape"
                                                        value="{{ old('stone_shape' . $key, $stone->stone_shape) }}"
                                                        name="stone_shape[]" placeholder="@lang('app.stone_shape')">
                                                    {!! $errors->has('stone_shape') ? '<p class="help-block">' . $errors->first('stone_shape') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group {{ $errors->has('stone_weight_exact') ? 'has-error' : '' }}">
                                                <label for="stone_weight_exact"
                                                    class="col-sm-4 control-label">@lang('app.stone_weight_exact')</label>
                                                <div class="col-sm-3">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"
                                                                name="stone_weight_exact[{{ $key }}]"
                                                                value="1"
                                                                {{ old('stone_weight_exact.' . $key, $stone->stone_weight_exact) == '1' ? 'checked' : '' }}>
                                                            @lang('app.exact')
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"
                                                                name="stone_weight_exact[{{ $key }}]"
                                                                value="0"
                                                                {{ old('stone_weight_exact.' . $key, $stone->stone_weight_exact) == '0' ? 'checked' : '' }}>
                                                            @lang('app.approximate')
                                                        </label>
                                                    </div>
                                                    {!! $errors->has('stone_weight_exact.' . $key)
                                                        ? '<p class="help-block">' . $errors->first('stone_weight_exact.' . $key) . '</p>'
                                                        : '' !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group  {{ $errors->has('stone_weight') ? 'has-error' : '' }}">
                                                <label for="stone_weight" class="col-md-4 control-label"> <span
                                                        class="price_text">@lang('app.stone') @lang('app.weight')</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="number" placeholder="@lang('app.stone_weight')"
                                                            class="form-control" name="stone_weight[]" id="stone_weight"
                                                            value="{{ old('stone_weight' . $key, $stone->stone_weight) }}"
                                                            step="0.01" min="0" max="99.99">
                                                        <span class="input-group-addon">CTS</span>
                                                    </div>
                                                    {!! $errors->has('stone_weight') ? '<p class="help-block">' . $errors->first('stone_weight') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div class="form-group {{ $errors->has('stone_color') ? 'has-error' : '' }}">
                                                <label for="stone_color" class="col-sm-4 control-label">@lang('app.stone')
                                                    @lang('app.color')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="stone_color"
                                                        value="{{ old('stone_color' . $key, $stone->stone_color) }}"
                                                        name="stone_color[]" placeholder="@lang('app.stone_color')">
                                                    {!! $errors->has('stone_color') ? '<p class="help-block">' . $errors->first('stone_color') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group {{ $errors->has('stones_quantity') ? 'has-error' : '' }}">
                                                <label for="stones_quantity"
                                                    class="col-sm-4 control-label">@lang('app.stones_quantity')</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" id="stones_quantity"
                                                        value="{{ old('stones_quantity' . $key, $stone->stones_quantity) }}"
                                                        name="stones_quantity[]" placeholder="@lang('app.stones_quantity')">
                                                    {!! $errors->has('stones_quantity') ? '<p class="help-block">' . $errors->first('stones_quantity') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group {{ $errors->has('stone_clarity') ? 'has-error' : '' }}">
                                                <label for="stone_clarity"
                                                    class="col-sm-4 control-label">@lang('app.stone_clarity')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="stone_clarity"
                                                        value="{{ old('stone_clarity' . $key, $stone->stone_clarity) }}"
                                                        name="stone_clarity[]" placeholder="@lang('app.stone_clarity')">
                                                    {!! $errors->has('stone_clarity') ? '<p class="help-block">' . $errors->first('stone_clarity') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group {{ $errors->has('stone_certified') ? 'has-error' : '' }}">
                                                <label for="stone_certified"
                                                    class="col-sm-4 control-label">@lang('app.stone_certified')</label>
                                                <div class="col-sm-3">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"
                                                                name="stone_certified[{{ $key }}]"
                                                                value="1"
                                                                {{ old('stone_certified.' . $key, $stone->stone_certified) == '1' ? 'checked' : '' }}>
                                                            @lang('app.yes')
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"
                                                                name="stone_certified[{{ $key }}]"
                                                                value="0"
                                                                {{ old('stone_certified.' . $key, $stone->stone_certified) == '0' ? 'checked' : '' }}>
                                                            @lang('app.no')
                                                        </label>
                                                    </div>
                                                    {!! $errors->has('stone_certified.' . $key)
                                                        ? '<p class="help-block">' . $errors->first('stone_certified.' . $key) . '</p>'
                                                        : '' !!}
                                                </div>
                                            </div>

                                            <div id="certification-fields-{{ $key }}"
                                                style="display: {{ old('stone_certified.' . $key, $stone->stone_certified) == 1 ? 'block' : 'none' }};">
                                                <div
                                                    class="form-group {{ $errors->has('certification_number') ? 'has-error' : '' }}">
                                                    <label for="certification_number"
                                                        class="col-sm-4 control-label">@lang('app.certification_number')</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control"
                                                            name="certification_number[]"
                                                            value="{{ old('certification_number' . $key, $stone->certification_number) }}"
                                                            placeholder="@lang('app.certification_number')">
                                                        {!! $errors->has('certification_number')
                                                            ? '<p class="help-block">' . $errors->first('certification_number') . '</p>'
                                                            : '' !!}
                                                    </div>
                                                </div>

                                                <div
                                                    class="form-group {{ $errors->has('certified_by') ? 'has-error' : '' }}">
                                                    <label for="certified_by"
                                                        class="col-sm-4 control-label">@lang('app.certified_by')</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ old('certified_by' . $key, $stone->certified_by) }}"
                                                            name="certified_by[]" placeholder="@lang('app.certified_by')">
                                                        {!! $errors->has('certified_by') ? '<p class="help-block">' . $errors->first('certified_by') . '</p>' : '' !!}
                                                    </div>
                                                </div>

                                                <div
                                                    class="form-group {{ $errors->has('certification_picture') ? 'has-error' : '' }}">
                                                    <label for="certification_picture"
                                                        class="col-sm-4 control-label">@lang('app.certification_picture')</label>
                                                    <div class="col-sm-8">
                                                        <input type="file" class="form-control"
                                                            value="{{ old('certification_picture' . $key, $stone->certification_picture) }}"
                                                            name="certification_picture[]">
                                                        {!! $errors->has('certification_picture')
                                                            ? '<p class="help-block">' . $errors->first('certification_picture') . '</p>'
                                                            : '' !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                        @endforeach
                                    @else
                                        <div class="form-group  {{ $errors->has('stone_type') ? 'has-error' : '' }}">
                                            <label for="stone_type" class="col-sm-4 control-label">@lang('app.stone')
                                                @lang('app.type')</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" name="stone_type[0]">
                                                    <option value="">@lang('app.select_stone_type')</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                </select>
                                                {!! $errors->has('stone_type') ? '<p class="help-block">' . $errors->first('stone_type') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('stone_shape') ? 'has-error' : '' }}">
                                            <label for="stone_shape" class="col-sm-4 control-label"> <span
                                                    class="ad_text">
                                                    @lang('app.stone')
                                                </span> @lang('app.shape')</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="stone_shape"
                                                    value="{{ old('stone_shape.0', '') }}" name="stone_shape[0]"
                                                    placeholder="@lang('app.stone_shape')">
                                                {!! $errors->has('stone_shape') ? '<p class="help-block">' . $errors->first('stone_shape') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div
                                            class="form-group {{ $errors->has('stone_weight_exact') ? 'has-error' : '' }}">
                                            <label for="stone_weight_exact"
                                                class="col-sm-4 control-label">@lang('app.stone_weight_exact')</label>
                                            <div class="col-sm-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="stone_weight_exact[0]" value="1"
                                                            {{ old('stone_weight_exact.0') == '1' ? 'checked' : '' }}>
                                                        @lang('app.exact')
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="stone_weight_exact[0]" value="0"
                                                            {{ old('stone_weight_exact.0') == '0' ? 'checked' : '' }}>
                                                        @lang('app.approximate')
                                                    </label>
                                                </div>
                                                {!! $errors->has('stone_weight_exact')
                                                    ? '<p class="help-block">' . $errors->first('stone_weight_exact') . '</p>'
                                                    : '' !!}
                                            </div>
                                        </div>

                                        <div class="form-group  {{ $errors->has('stone_weight') ? 'has-error' : '' }}">
                                            <label for="stone_weight" class="col-md-4 control-label"> <span
                                                    class="price_text">@lang('app.stone') @lang('app.weight')</span>
                                            </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="number" placeholder="@lang('app.stone_weight')"
                                                        class="form-control" name="stone_weight[0]" id="stone_weight"
                                                        value="{{ old('stone_weight.0', '') }}" step="0.01"
                                                        min="0" max="99.99"
                                                        oninput="validateStoneWeight(this, 0)">
                                                    <span class="input-group-addon">CTS</span>
                                                </div>
                                                <p class="help-block" id="stone-weight-error-0"
                                                    style="display:none; color:red;">
                                                    Please enter a valid weight (e.g., 12.34)
                                                </p>
                                                {!! $errors->has('stone_weight') ? '<p class="help-block">' . $errors->first('stone_weight') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('stone_color') ? 'has-error' : '' }}">
                                            <label for="stone_color" class="col-sm-4 control-label">@lang('app.stone')
                                                @lang('app.color')</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="stone_color"
                                                    value="{{ old('stone_color.0', '') }}" name="stone_color[0]"
                                                    placeholder="@lang('app.stone_color')">
                                                {!! $errors->has('stone_color') ? '<p class="help-block">' . $errors->first('stone_color') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('stones_quantity') ? 'has-error' : '' }}">
                                            <label for="stones_quantity"
                                                class="col-sm-4 control-label">@lang('app.stones_quantity')</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control" id="stones_quantity"
                                                    value="{{ old('stones_quantity.0', '') }}" name="stones_quantity[0]"
                                                    placeholder="@lang('app.stones_quantity')">
                                                {!! $errors->has('stones_quantity') ? '<p class="help-block">' . $errors->first('stones_quantity') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('stone_clarity') ? 'has-error' : '' }}">
                                            <label for="stone_clarity"
                                                class="col-sm-4 control-label">@lang('app.stone_clarity')</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="stone_clarity"
                                                    value="{{ old('stone_clarity.0', '') }}" name="stone_clarity[0]"
                                                    placeholder="@lang('app.stone_clarity')">
                                                {!! $errors->has('stone_clarity') ? '<p class="help-block">' . $errors->first('stone_clarity') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('stone_certified') ? 'has-error' : '' }}">
                                            <label for="stone_certified"
                                                class="col-sm-4 control-label">@lang('app.stone_certified')</label>
                                            <div class="col-sm-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="stone_certified[0]" value="1"
                                                            {{ old('stone_certified.0') == '1' ? 'checked' : '' }}>
                                                        @lang('app.yes')
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="stone_certified[0]" value="0"
                                                            {{ old('stone_certified.0') == '0' ? 'checked' : '' }}>
                                                        @lang('app.no')
                                                    </label>
                                                </div>
                                                {!! $errors->has('stone_certified') ? '<p class="help-block">' . $errors->first('stone_certified') . '</p>' : '' !!}
                                            </div>
                                        </div>

                                        <div id="certification-fields-0" style="display: none;">
                                            <div
                                                class="form-group {{ $errors->has('certification_number') ? 'has-error' : '' }}">
                                                <label for="certification_number"
                                                    class="col-sm-4 control-label">@lang('app.certification_number')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="certification_number"
                                                        name="certification_number[0]" placeholder="@lang('app.certification_number')">
                                                    {!! $errors->has('certification_number')
                                                        ? '<p class="help-block">' . $errors->first('certification_number') . '</p>'
                                                        : '' !!}
                                                </div>
                                            </div>

                                            <div class="form-group {{ $errors->has('certified_by') ? 'has-error' : '' }}">
                                                <label for="certified_by"
                                                    class="col-sm-4 control-label">@lang('app.certified_by')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="certified_by"
                                                        name="certified_by[0]" placeholder="@lang('app.certified_by')">
                                                    {!! $errors->has('certified_by') ? '<p class="help-block">' . $errors->first('certified_by') . '</p>' : '' !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group {{ $errors->has('certification_picture') ? 'has-error' : '' }}">
                                                <label for="certification_picture"
                                                    class="col-sm-4 control-label">@lang('app.certification_picture')</label>
                                                <div class="col-sm-8">
                                                    <input type="file" class="form-control" id="certification_picture"
                                                        name="certification_picture[0]">
                                                    {!! $errors->has('certification_picture')
                                                        ? '<p class="help-block">' . $errors->first('certification_picture') . '</p>'
                                                        : '' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <div class="image-ad-more-wrap">
                                            <a href="javascript:;" id="add-more-stones">
                                                <i class="fa fa-plus-circle"></i>
                                                @lang('app.add_more')
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <legend>@lang('app.item_images') (@lang('app.optional'))</legend>

                                <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
                                    <div class="col-sm-12">
                                        <div id="uploaded-ads-image-wrap">
                                            @if ($item->images->count() > 0)
                                                @foreach ($item->images as $img)
                                                    <div class="creating-ads-img-wrap">
                                                        <img 
                                                            src="{{ asset($img->image) }}"
                                                            class="img-responsive"
                                                            data-image-path="{{ $img->path }}"
                                                            data-image-model="LotItemImage"
                                                            data-parent-id="{{$item->id}}"
                                                            data-image-id="{{$img->id}}"
                                                        />
                                                        <div class="img-action-wrap" id="{{ $img->id }}"
                                                            data-item-id="{{ $item->id }}">
                                                            <a href="javascript:;" class="imgDeleteBtn"><i
                                                                    class="fa fa-trash-o"></i> </a>
                                                        </div>
                                                        <!-- Spinner overlay -->
                                                        <div class="spinner-overlay"
                                                            style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index: 2;">
                                                            <div class="spinner"
                                                                style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
                                                                <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <div class="w-100" style="display: flex">
                                                <input type="hidden" value="{{ count($item->images) }}"
                                                    class="images-fields-count">
                                            </div>
                                            <div class="upload-images-input-wrap">
                                                <div class="input-group image-input-group">
                                                    <input type="file" name="images[]" class="form-control" /> <span
                                                        class="input-group-btn">
                                                        <button class="btn btn-danger remove-image" type="button"
                                                            style="margin-top: 10px;">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="image-ad-more-wrap">
                                                <a href="javascript:;" class="image-add-more"><i
                                                        class="fa fa-plus-circle"></i>
                                                    @lang('app.add_more')</a>
                                            </div>
                                        </div>
                                        {!! $errors->has('images') ? '<p class="help-block">' . $errors->first('images') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <legend>@lang('app.price') @lang('app.info')</legend>

                                <div class="form-group {{ $errors->has('startPrice') ? 'has-error' : '' }}">
                                    <label for="startPrice" class="col-sm-4 control-label">@lang('app.startPrice')</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="number" class="form-control" id="startPrice"
                                                value="{{ old('startPrice', $item->startPrice) }}" name="startPrice"
                                                placeholder="@lang('app.startPrice')" required>
                                        </div>
                                        {!! $errors->has('startPrice') ? '<p class="help-block">' . $errors->first('startPrice') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('reserve_price') ? 'has-error' : '' }}">
                                    <label for="reserve_price" class="col-sm-4 control-label">@lang('app.reserve_price')</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="number" class="form-control" id="reserve_price"
                                                value="{{ old('reserve_price', $item->reserve_price) }}"
                                                name="reserve_price" placeholder="@lang('app.reserve_price')" required>
                                        </div>
                                        {!! $errors->has('reserve_price') ? '<p class="help-block">' . $errors->first('reserve_price') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('minEstimate') ? 'has-error' : '' }}">
                                    <label for="minEstimate" class="col-sm-4 control-label">@lang('app.minEstimate')</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="number" class="form-control" id="minEstimate"
                                                value="{{ old('minEstimate', $item->minEstimate) }}" name="minEstimate"
                                                placeholder="@lang('app.minEstimate')" required>
                                        </div>
                                        {!! $errors->has('minEstimate') ? '<p class="help-block">' . $errors->first('minEstimate') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('maxEstimate') ? 'has-error' : '' }}">
                                    <label for="maxEstimate" class="col-sm-4 control-label">@lang('app.maxEstimate')</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="number" class="form-control" id="maxEstimate"
                                                value="{{ old('maxEstimate', $item->maxEstimate) }}" name="maxEstimate"
                                                placeholder="@lang('app.maxEstimate')" required>
                                        </div>
                                        {!! $errors->has('maxEstimate') ? '<p class="help-block">' . $errors->first('maxEstimate') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('postSalePrice') ? 'has-error' : '' }}">
                                    <label for="postSalePrice" class="col-sm-4 control-label">@lang('app.postSalePrice')</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="number" class="form-control" id="postSalePrice"
                                                value="{{ old('postSalePrice', $item->postSalePrice) }}"
                                                name="postSalePrice" placeholder="@lang('app.postSalePrice')" required>
                                        </div>
                                        {!! $errors->has('postSalePrice') ? '<p class="help-block">' . $errors->first('postSalePrice') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('appraised_value') ? 'has-error' : '' }}">
                                    <label for="appraised_value" class="col-sm-4 control-label">
                                        @lang('app.appraised_value')</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="appraised_value"
                                            value="{{ old('appraised_value', $item->appraised_value) }}"
                                            name="appraised_value" placeholder="@lang('app.appraised_value')" required>
                                        {!! $errors->has('appraised_value') ? '<p class="help-block">' . $errors->first('appraised_value') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8" style="display: flex; justify-content: end;">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                            @lang('app.update')</button>
                                    </div>
                                </div>
                            </form>

                            <legend>@lang('app.serial_number')</legend>
                            <div class="form-group {{ $errors->has('appraised_value') ? 'has-error' : '' }}">
                                <label for="serial_number" class="col-sm-4 control-label">
                                    @lang('app.serial_number')</label>
                                <div class="col-sm-8">
                                    @if ($item->bar_code_image)
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset($item->bar_code_image) }}" class="thumb-listing-table"
                                                alt="No Image" style="width: 100%; height: 50px; margin: 3px;">
                                            <p>{{ $item->serial_number }}</p>
                                        </div>
                                    @else
                                        <p>@lang('app.not_available')</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('image-editor')
    @include('layouts.imageEditor')
@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('content_editor');
    </script>

    <script>
        let stoneCount = parseInt($('.total_stones_count').val(), 10) || 0;
        let imagesFieldsCount = 0;

        function validateStoneWeight(input, index) {
            const value = input.value;
            const regex = /^\d{1,2}(\.\d{0,2})?$/;

            if (!regex.test(value)) {
                document.getElementById(`stone-weight-error-${index}`).style.display = 'block';
                $('#formSubmitButton').prop('disabled', true);
            } else {
                document.getElementById(`stone-weight-error-${index}`).style.display = 'none';
                $('#formSubmitButton').prop('disabled', false);
            }
        }

        $(document).ready(function() {
            const count = $('.images-fields-count').val();

            if (count >= 5) {
                const addmageFields = $('.image-add-more').hide();
            } else {
                imagesFieldsCount = count;
            }


            $('#add-more-stones').click(function(event) {
                event.preventDefault();
                stoneCount++;

                // Use backticks to create a multi-line string
                let moreStoneFields = `
        <div class="stone-section">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8" style="display:flex; justify-content:end;">
                    <button type="button" class="btn btn-danger remove-stone-section">
                        @lang('app.remove_stone')
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label for="stone_type" class="col-sm-4 control-label">
                    @lang('app.stone') @lang('app.type')
                </label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="stone_type[${stoneCount}]">
                        <option value="">@lang('app.select_stone_type')</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="stone_shape" class="col-sm-4 control-label"> <span class="ad_text">
                    @lang('app.stone')
                    </span> @lang('app.shape')
                </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="stone_shape[${stoneCount}]"
                        placeholder="@lang('app.stone_shape')">
                </div>
            </div>

            <div class="form-group">
                <label for="stone_weight_exact" class="col-sm-4 control-label">@lang('app.stone_weight_exact')</label>
                <div class="col-sm-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="stone_weight_exact[${stoneCount}]" value="1">
                            @lang('app.exact')
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="stone_weight_exact[${stoneCount}]" value="0">
                            @lang('app.approximate')
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="stone_weight" class="col-md-4 control-label"> 
                    <span class="price_text">@lang('app.stone') @lang('app.weight')</span>
                </label>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="number" placeholder="@lang('app.stone_weight')" class="form-control" name="stone_weight[${stoneCount}]" step="0.01" min="0" max="99.99" oninput="validateStoneWeight(this, ${stoneCount})">
                        <span class="input-group-addon">CTS</span>
                    </div>
                    <p class="help-block" id="stone-weight-error-${stoneCount}" style="display:none; color:red;">
                        Please enter a valid weight (e.g., 12.34)
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">@lang('app.stone_color')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="stone_color[${stoneCount}]" placeholder="@lang('app.stone_color')">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">@lang('app.stones_quantity')</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="stones_quantity[${stoneCount}]"
                        placeholder="@lang('app.stones_quantity')">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">@lang('app.stone_clarity')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="stone_clarity[${stoneCount}]" placeholder="@lang('app.stone_clarity')">
                </div>
            </div>

            <div class="form-group">
                <label for="stone_certified" class="col-sm-4 control-label">@lang('app.stone_certified')</label>
                <div class="col-sm-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="stone_certified[${stoneCount}]" value="1">
                            @lang('app.yes')
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="stone_certified[${stoneCount}]" value="0">
                            @lang('app.no')
                        </label>
                    </div>
                </div>
            </div>
            <!-- Certification Fields -->
                <div id="certification-fields-${stoneCount}" style="display: none;">
                    <div class="form-group">
                        <label for="certification_number" class="col-sm-4 control-label">@lang('app.certification_number')</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="certification_number[${stoneCount}]" placeholder="@lang('app.certification_number')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="certified_by" class="col-sm-4 control-label">@lang('app.certified_by')</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="certified_by[${stoneCount}]" placeholder="@lang('app.certified_by')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="certification_picture" class="col-sm-4 control-label">@lang('app.certification_picture')</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" name="certification_picture[${stoneCount}]">
                        </div>
                    </div>
                </div>
                <!-- End Certification Fields -->
            <hr/>
        </div>
        `;

                $('#more-stones-fields').append(moreStoneFields);
                toggleCertificationFields(stoneCount);
            });

            $(document).on('click', '.remove-stone-section', function() {
                $(this).closest('.stone-section').remove();
            });

            $(document).on('click', '.image-add-more', function(e) {
                e.preventDefault();
                imagesFieldsCount++;

                if (imagesFieldsCount <= 5) {
                    $('.upload-images-input-wrap').append(
                        '<div class="input-group image-input-group">' +
                        '<input type="file" name="images[]" class="form-control" />' +
                        '<span class="input-group-btn">' +
                        '<button class="btn btn-danger remove-image" type="button" style="margin-top: 10px;"><i class="fa fa-times"></i></button>' +
                        '</span>' +
                        '</div>'
                    );
                }
                if (imagesFieldsCount == 4) {
                    $('.image-add-more').hide();
                }
            });

            $(document).on('click', '.remove-image', function(e) {
                e.preventDefault();
                $(this).closest('.image-input-group').remove();

                // Show the "Add More" button again if the input count drops below 5
                let fileInputs = $('.upload-images-input-wrap .image-input-group');
                if (fileInputs.length < 5) {
                    $('.image-add-more').show();
                }

                imagesFieldsCount -= 1;
            });

            $('body').on('click', '.imgDeleteBtn', function() {
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var current_selector = $(this);
                var img_id = $(this).closest('.img-action-wrap').attr('id');
                var item_id = $(this).closest('.img-action-wrap').attr('data-item-id');

                var spinner_overlay = current_selector.closest('.creating-ads-img-wrap').find(
                    '.spinner-overlay');
                spinner_overlay.show();

                $.ajax({
                    url: '{{ route('delete_item_image') }}',
                    type: "POST",
                    data: {
                        img_id: img_id,
                        item_id: item_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success == 1) {
                            current_selector.closest('.creating-ads-img-wrap').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                            imagesFieldsCount -= 1;
                        }
                    },
                    error: function(error) {
                        toastr.error(error.msg, '@lang('app.error')', toastr_options);
                    },
                    complete: function() {
                        // Hide the spinner overlay regardless of success or failure
                        spinner_overlay.hide();
                    }
                });
            });

            $('#item_name').on('input', function() {
                var itemName = $(this).val();
                var capitalizedItemName = itemName.toUpperCase();
                const actualValue = $('#internalCatalogNumber').val();

                if (itemName.length >= 2) {
                    var prefix = capitalizedItemName.substring(0, 2).toUpperCase();

                    var internalCatalogNumber = prefix + actualValue;
                    $('#internalCatalogNumber').val(internalCatalogNumber);
                } else {
                    $('#internalCatalogNumber').val('');
                }
            });

            function toggleCertificationFields(index) {
                let isCertified = $(`input[name="stone_certified[${index}]"]:checked`).val() === '1';
                if (isCertified) {
                    $(`#certification-fields-${index}`).show();
                } else {
                    $(`#certification-fields-${index}`).hide();
                }

                $(`input[name="stone_certified[${index}]"]`).on('change', function() {
                    let isCertified = $(this).val() === '1';
                    if (isCertified) {
                        $(`#certification-fields-${index}`).show();
                    } else {
                        $(`#certification-fields-${index}`).hide();
                    }
                });
            }

            $('input[name^="stone_certified"]').each(function() {
                let index = $(this).attr('name').match(/\d+/)[0];
                toggleCertificationFields(index);
            });

            $('body').on('click', '.delete_item_stone', function(event) {
                event.preventDefault();

                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var current_selector = $(this);
                const stone_id = $(this).data('stone_id');

                $.ajax({
                    url: '{{ route('delete_item_stone') }}',
                    type: "POST",
                    data: {
                        stone_id: stone_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success == 1) {
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                            location.reload();
                        }
                    },
                    error: function(error) {
                        toastr.error(error.msg, '@lang('app.error')', toastr_options);
                    }
                });
            });
        });
    </script>

    <script>
        @if (session('success'))
            toastr.success('{{ session('success') }}', '<?php echo trans('app.success'); ?>', toastr_options);
        @endif
    </script>

    @if (get_option('enable_recaptcha_post_ad') == 1)
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
@endsection
