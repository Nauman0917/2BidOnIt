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

                            @include('admin.flash_msg')

                            <form action="{{ route('add_item_store') }}" id="adsPostForm" class="form-horizontal"
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
                                        @lang('app.size')
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="item_name"
                                            value="{{ old('item_name', '') }}" name="item_name"
                                            placeholder="@lang('app.item_name')" min="3" maxlength="50" required>
                                        {!! $errors->has('item_name') ? '<p class="help-block">' . $errors->first('item_name') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <input type="hidden" id="new_catalog_number" name="new_catalog_number"
                                    value="{{ $new_catalog_number }}">

                                <div class="form-group {{ $errors->has('detailed_description') ? 'has-error' : '' }}">
                                    <label class="col-sm-4 control-label"><span class="ad_text"> @lang('app.item')
                                        </span>
                                        @lang('app.description')</label>
                                    <div class="col-sm-8">
                                        <textarea name="detailed_description" class="form-control" id="content_editor" rows="8">{{ old('detailed_description') }}</textarea>
                                        {!! $errors->has('detailed_description')
                                            ? '<p class="help-block">' . $errors->first('detailed_description') . '</p>'
                                            : '' !!}
                                        <p class="text-info"> @lang('app.ad_description_info_text')</p>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('internalCatalogNumber') ? 'has-error' : '' }}">
                                    <label for="internalCatalogNumber" class="col-sm-4 control-label">
                                        <span class="ad_text">
                                            @lang('app.item')
                                        </span>
                                        @lang('app.size')
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="internalCatalogNumber"
                                            name="internalCatalogNumber" placeholder="@lang('app.internalCatalogNumber')" required readonly>
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
                                            <option value="A">A</option>
                                            <option value="B">B</option>
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
                                            value="{{ old('item_size', '') }}" name="item_size"
                                            placeholder="@lang('app.item_size')" required>
                                        {!! $errors->has('item_size') ? '<p class="help-block">' . $errors->first('item_size') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('item_weight') ? 'has-error' : '' }}">
                                    <label for="item_weight" class="col-md-4 control-label"> <span
                                            class="price_text">@lang('app.item_weight')</span> </label>
                                    <div class="col-md-4">
                                        <input type="number" placeholder="@lang('app.item_weight')" class="form-control"
                                            name="item_weight" id="item_weight" value="{{ old('item_weight', '') }}"
                                            required>
                                    </div>

                                    <div class="col-md-4">
                                        <select class="form-control select2" name="weight_unit" required>
                                            <option value="">@lang('app.select_weight_unit')</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 col-md-offset-4">
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
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                        </select>
                                        {!! $errors->has('metal_type') ? '<p class="help-block">' . $errors->first('metal_type') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('metal_color') ? 'has-error' : '' }}">
                                    <label for="metal_color" class="col-sm-4 control-label">@lang('app.metal_color')</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="metal_color"
                                            value="{{ old('metal_color', '') }}" name="metal_color"
                                            placeholder="@lang('app.metal_color')" required>
                                        {!! $errors->has('metal_color') ? '<p class="help-block">' . $errors->first('metal_color') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('total_gem_weight') ? 'has-error' : '' }}">
                                    <label for="total_gem_weight"
                                        class="col-sm-4 control-label">@lang('app.total_gem_weight')</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="total_gem_weight"
                                            value="{{ old('total_gem_weight', '') }}" name="total_gem_weight"
                                            placeholder="@lang('app.total_gem_weight')" required>
                                        {!! $errors->has('total_gem_weight')
                                            ? '<p class="help-block">' . $errors->first('total_gem_weight') . '</p>'
                                            : '' !!}
                                    </div>
                                </div>

                                <legend>@lang('app.stone') @lang('app.info') (@lang('app.optional'))</legend>

                                <div id="more-stones-fields">
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
                                        <label for="stone_shape" class="col-sm-4 control-label"> <span class="ad_text">
                                                @lang('app.stone')
                                            </span> @lang('app.shape')</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="stone_shape"
                                                value="{{ old('stone_shape.0', '') }}" name="stone_shape[0]"
                                                placeholder="@lang('app.stone_shape')">
                                            {!! $errors->has('stone_shape') ? '<p class="help-block">' . $errors->first('stone_shape') . '</p>' : '' !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('stone_weight_exact') ? 'has-error' : '' }}">
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
                                                    min="0" max="99.99" oninput="validateStoneWeight(this, 0)">
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
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <div class="image-ad-more-wrap">
                                            <a href="javascript:;" id="add-more-stones">
                                                <i class="fa fa-plus-circle"></i>
                                                @lang('app.add_stone')
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <legend>@lang('app.item_images') (@lang('app.optional'))</legend>

                                <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
                                    <div class="col-sm-12">
                                        <div class="col-sm-8 col-sm-offset-4">

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
                                                value="{{ old('startPrice', '') }}" name="startPrice"
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
                                                value="{{ old('reserve_price', '') }}" name="reserve_price"
                                                placeholder="@lang('app.reserve_price')" required>
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
                                                value="{{ old('minEstimate', '') }}" name="minEstimate"
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
                                                value="{{ old('maxEstimate', '') }}" name="maxEstimate"
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
                                                value="{{ old('postSalePrice', '') }}" name="postSalePrice"
                                                placeholder="@lang('app.postSalePrice')" required>
                                        </div>
                                        {!! $errors->has('postSalePrice') ? '<p class="help-block">' . $errors->first('postSalePrice') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('appraised_value') ? 'has-error' : '' }}">
                                    <label for="appraised_value" class="col-sm-4 control-label">
                                        @lang('app.appraised_value')</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="appraised_value"
                                            value="{{ old('appraised_value', '') }}" name="appraised_value"
                                            placeholder="@lang('app.appraised_value')" required>
                                        {!! $errors->has('appraised_value') ? '<p class="help-block">' . $errors->first('appraised_value') . '</p>' : '' !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="submit" id="formSubmitButton" class="btn btn-primary">
                                            <i class="fa fa-save"></i>
                                            @lang('app.save_new_item')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div> <!-- #row -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('content_editor');
    </script>

    <script>
        let stoneCount = 0;
        $(document).ready(function() {

            $('#add-more-stones').click(function(event) {
                event.preventDefault();
                stoneCount++;

                let moreStoneFields = `
                <div class="stone-section">
                <hr/>
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
                            <input type="radio" name="stone_weight_exact[${stoneCount}]" value="1"
                                {{ old('stone_weight_exact.${stoneCount}') == '1' ? 'checked' : '' }}>
                            @lang('app.exact')
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="stone_weight_exact[${stoneCount}]" value="0"
                                {{ old('stone_weight_exact.${stoneCount}') == '0' ? 'checked' : '' }}>
                            @lang('app.approximate')
                        </label>
                    </div>
                </div>
                </div>

                <div class="form-group">
                <label for="stone_weight" class="col-md-4 control-label"> <span
                    class="price_text">@lang('app.stone') @lang('app.weight')</span>
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
                            <input type="radio" name="stone_certified[${stoneCount}]" value="1"
                                {{ old('stone_certified.${stoneCount}') == '1' ? 'checked' : '' }}>
                            @lang('app.yes')
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="radio">
                        <label>
                            <input type="radio" name="stone_certified[${stoneCount}]" value="0"
                                {{ old('stone_certified.${stoneCount}') == '0' ? 'checked' : '' }}>
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
                let fileInputs = $('.upload-images-input-wrap input[type="file"]');

                if (fileInputs.length < 5) {
                    $('.upload-images-input-wrap').append(
                        '<div class="input-group image-input-group">' +
                        '<input type="file" name="images[]" class="form-control" />' +
                        '<span class="input-group-btn">' +
                        '<button class="btn btn-danger remove-image" type="button" style="margin-top: 10px;"><i class="fa fa-times"></i></button>' +
                        '</span>' +
                        '</div>'
                    );

                    if (fileInputs.length == 4) {
                        $('.image-add-more').hide();
                    }
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
            });

            $('#item_name').on('input', function() {
                var itemName = $(this).val();

                if (itemName.length > 30) {
                    itemName = itemName.substring(0, 30);
                    $(this).val(itemName);
                }

                var capitalizedItemName = itemName.toUpperCase();
                const newSequence = $('#new_catalog_number').val();

                if (itemName.length >= 2) {
                    var prefix = capitalizedItemName.substring(0, 3).toUpperCase();

                    var internalCatalogNumber = prefix + newSequence;
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
        });

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
