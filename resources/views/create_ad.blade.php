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

    <div id="post-new-ad">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    @if (!\Auth::check())
                        <div class="alert alert-info no-login-info">
                            <p> <i class="fa fa-info-circle"></i> @lang('app.no_login_info')</p>
                        </div>
                    @endif

                    @include('admin.flash_msg')

                    <form action="" id="adsPostForm" class="form-horizontal" method="post"
                        enctype="multipart/form-data"> @csrf

                        <legend> <span class="ad_text"> @lang('app.ad') </span> @lang('app.info')</legend>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group  {{ $errors->has('category') ? 'has-error' : '' }}">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.category')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="category">
                                    <option value="">@lang('app.select_a_category')</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}
                                            data-category-type="{{ $category->category_type }}">
                                            {{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('category') ? '<p class="help-block">' . $errors->first('category') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ad_title') ? 'has-error' : '' }}">
                            <label for="ad_title" class="col-sm-4 control-label"> <span class="ad_text"> @lang('app.ad')
                                </span> @lang('app.title')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ad_title" value="{{ old('ad_title') }}"
                                    name="ad_title" placeholder="@lang('app.ad_title')">
                                {!! $errors->has('ad_title') ? '<p class="help-block">' . $errors->first('ad_title') . '</p>' : '' !!}
                                <p class="text-info"> @lang('app.great_title_info')</p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ad_description') ? 'has-error' : '' }}">
                            <label class="col-sm-4 control-label"><span class="ad_text"> @lang('app.ad') </span>
                                @lang('app.description')</label>
                            <div class="col-sm-8">
                                <textarea name="ad_description" class="form-control" id="content_editor" rows="8">{{ old('ad_description') }}</textarea>
                                {!! $errors->has('ad_description') ? '<p class="help-block">' . $errors->first('ad_description') . '</p>' : '' !!}
                                <p class="text-info"> @lang('app.ad_description_info_text')</p>
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('price') ? 'has-error' : '' }}">
                            <label for="price" class="col-md-4 control-label"> <span
                                    class="price_text">@lang('app.starting_price')</span> </label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                    <input type="text" placeholder="@lang('app.ex_price')" class="form-control"
                                        name="price" id="price" value="{{ old('price') }}">
                                </div>
                            </div>

                            <div class="col-sm-8 col-md-offset-4">
                                {!! $errors->has('price') ? '<p class="help-block">' . $errors->first('price') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('auction_no') ? 'has-error' : '' }}">
                            <label for="auction_no" class="col-sm-4 control-label">@lang('app.auction_no')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="auction_no" value="{{ old('auction_no') }}"
                                    name="auction_no" placeholder="@lang('app.auction_no')">
                                {!! $errors->has('auction_no') ? '<p class="help-block">' . $errors->first('auction_no') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('catalog_no') ? 'has-error' : '' }}">
                            <label for="catalog_no" class="col-sm-4 control-label">@lang('app.catalog_no')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="catalog_no" value="{{ old('catalog_no') }}"
                                    name="catalog_no" placeholder="@lang('app.catalog_no')">
                                {!! $errors->has('catalog_no') ? '<p class="help-block">' . $errors->first('catalog_no') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('seller_no') ? 'has-error' : '' }}">
                            <label for="seller_no" class="col-sm-4 control-label">@lang('app.seller_no')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="seller_no" value="{{ old('seller_no') }}"
                                    name="seller_no" placeholder="@lang('app.seller_no')">
                                {!! $errors->has('seller_no') ? '<p class="help-block">' . $errors->first('seller_no') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('min_est') ? 'has-error' : '' }}">
                            <label for="min_est" class="col-sm-4 control-label">@lang('app.min_est')</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="min_est" value="{{ old('min_est') }}"
                                    name="min_est" placeholder="@lang('app.min_est')">
                                {!! $errors->has('min_est') ? '<p class="help-block">' . $errors->first('min_est') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('max_est') ? 'has-error' : '' }}">
                            <label for="number" class="col-sm-4 control-label">@lang('app.max_est')</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="max_est" value="{{ old('max_est') }}"
                                    name="max_est" placeholder="@lang('app.max_est')">
                                {!! $errors->has('max_est') ? '<p class="help-block">' . $errors->first('max_est') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div id="dynamic-fields">
                            <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                                <label class="col-sm-4 control-label">@lang('app.detailed_description_per_form')</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="text_title[]" placeholder="Title">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="text_key[]" placeholder="Key">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="button" class="btn btn-default" id="add-more-fields"><i
                                        class="fa fa-plus"></i> Add More</button>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('reserve_price') ? 'has-error' : '' }}">
                            <label for="reserve_price" class="col-sm-4 control-label">@lang('app.reserve_price')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="reserve_price"
                                    value="{{ old('reserve_price') }}" name="reserve_price"
                                    placeholder="@lang('app.reserve_price')">
                                {!! $errors->has('reserve_price') ? '<p class="help-block">' . $errors->first('reserve_price') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('bid_deadline') ? 'has-error' : '' }}">
                            <label for="bid_deadline" class="col-sm-4 control-label"> @lang('app.bid_deadline')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="bid_deadline"
                                    value="{{ old('bid_deadline') }}" name="bid_deadline"
                                    placeholder="@lang('app.bid_deadline')">
                                {!! $errors->has('bid_deadline') ? '<p class="help-block">' . $errors->first('bid_deadline') . '</p>' : '' !!}
                            </div>
                        </div>

                        <legend>@lang('app.image')</legend>

                        <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <div class="upload-images-input-wrap">
                                        <input type="file" name="images[]" class="form-control" />
                                        <input type="file" name="images[]" class="form-control" />
                                    </div>

                                    <div class="image-ad-more-wrap">
                                        <a href="javascript:;" class="image-add-more"><i class="fa fa-plus-circle"></i>
                                            @lang('app.add_more')</a>
                                    </div>
                                </div>
                                {!! $errors->has('images') ? '<p class="help-block">' . $errors->first('images') . '</p>' : '' !!}
                            </div>
                        </div>

                        <legend>@lang('app.ad_add_items')</legend>
                        @if ($items->filter(fn($item) => !in_array($item->id, $auctionedItemIds))->isNotEmpty())
                            <div class="table-responsive items-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all-checkbox"></th>
                                            <th>Item Type</th>
                                            <th>Item Name</th>
                                            <th>Start Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="available-items">
                                        @if (count($items) > 0)
                                            @foreach ($items as $item)
                                                @if (!in_array($item->id, $auctionedItemIds))
                                                    <tr data-item-id="{{ $item->id }}"
                                                        class="{{ in_array($item->id, $auctionedItemIds) ? 'selected-item' : '' }}">
                                                        <td>
                                                            <input type="checkbox" class="item-checkbox" name="itemIds[]"
                                                                value="{{ $item->id }}">
                                                        </td>
                                                        <td>{{ $item->item_type }}</td>
                                                        <td>{{ $item->item_name }}</td>
                                                        <td>{{ $item->startPrice }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">No item available to add.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <legend>@lang('app.video')</legend>

                        <div class="form-group {{ $errors->has('video_url') ? 'has-error' : '' }}">
                            <label for="video_url" class="col-sm-4 control-label">@lang('app.video_url')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="video_url"
                                    value="{{ old('video_url') }}" name="video_url" placeholder="@lang('app.video_url')">
                                {!! $errors->has('video_url') ? '<p class="help-block">' . $errors->first('video_url') . '</p>' : '' !!}
                                <p class="help-block">@lang('app.video_url_help')</p>
                                <p class="text-info">@lang('app.video_url_help_for_modern_theme')</p>
                            </div>
                        </div>

                        <legend>@lang('app.location_info')</legend>

                        <div class="form-group  {{ $errors->has('country') ? 'has-error' : '' }}">
                            <label class="col-sm-4 control-label">@lang('app.country')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="country">
                                    <option value="">@lang('app.select_a_country')</option>

                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country') == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country') ? '<p class="help-block">' . $errors->first('country') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('state') ? 'has-error' : '' }}">
                            <label for="state_select" class="col-sm-4 control-label">@lang('app.state')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="state_select" name="state">
                                    @if ($previous_states->count() > 0)
                                        @foreach ($previous_states as $state)
                                            <option value="{{ $state->id }}"
                                                {{ old('state') == $state->id ? 'selected' : '' }}>
                                                {{ $state->state_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-info">
                                    <span id="state_loader" style="display: none;"><i class="fa fa-spin fa-spinner"></i>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('city') ? 'has-error' : '' }}">
                            <label for="city_select" class="col-sm-4 control-label">@lang('app.city')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="city_select" name="city">
                                    @if ($previous_cities->count() > 0)
                                        @foreach ($previous_cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-info">
                                    <span id="city_loader" style="display: none;"><i class="fa fa-spin fa-spinner"></i>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <legend><span class="seller_text"> @lang('app.seller') </span> @lang('app.info')</legend>

                        <div class="form-group {{ $errors->has('seller_name') ? 'has-error' : '' }}">
                            <label for="seller_name" class="col-sm-4 control-label"> <span class="seller_text">
                                    @lang('app.seller') </span> @lang('app.name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="seller_name"
                                    value="{{ old('seller_name') ? old('seller_name') : (Auth::check() ? $lUser->name : '') }}"
                                    name="seller_name" placeholder="@lang('app.name')">
                                {!! $errors->has('seller_name') ? '<p class="help-block">' . $errors->first('seller_name') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('seller_email') ? 'has-error' : '' }}">
                            <label for="seller_email" class="col-sm-4 control-label"> <span class="seller_text">
                                    @lang('app.seller') </span> @lang('app.email')</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="seller_email"
                                    value="{{ old('seller_email') ? old('seller_email') : (Auth::check() ? $lUser->email : '') }}"
                                    name="seller_email" placeholder="@lang('app.email')">
                                {!! $errors->has('seller_email') ? '<p class="help-block">' . $errors->first('seller_email') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('seller_phone') ? 'has-error' : '' }}">
                            <label for="seller_phone" class="col-sm-4 control-label"> <span class="seller_text">
                                    @lang('app.seller') </span> @lang('app.phone')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="seller_phone"
                                    value="{{ old('seller_phone') ? old('seller_phone') : (Auth::check() ? $lUser->phone : '') }}"
                                    name="seller_phone" placeholder="@lang('app.phone')">
                                {!! $errors->has('seller_phone') ? '<p class="help-block">' . $errors->first('seller_phone') . '</p>' : '' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                            <label for="address" class="col-sm-4 control-label">@lang('app.address')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="address"
                                    value="{{ old('address') ? old('address') : (Auth::check() ? $lUser->address : '') }}"
                                    name="address" placeholder="@lang('app.address')">
                                {!! $errors->has('address') ? '<p class="help-block">' . $errors->first('address') . '</p>' : '' !!}
                                <p class="text-info">@lang('app.address_line_help_text')</p>
                            </div>
                        </div>

                        @if (get_option('enable_recaptcha_post_ad') == 1)
                            <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="g-recaptcha" data-sitekey="{{ get_option('recaptcha_site_key') }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                    @lang('app.save_new_ad')</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div> <!-- #row -->

        </div> <!-- /#container -->
    </div>

@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('content_editor');
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker-1.6.4/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript">
        $('#application_deadline, #bid_deadline').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            startDate: new Date(),
            autoclose: true
        });
        $('#build_year').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#add-more-fields').click(function() {
                var newField = `
                    <div class="form-group">
                        <label class="col-sm-4 control-label">@lang('app.detailed_description_per_form')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="text_title[]" placeholder="Title">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="text_key[]" placeholder="Key">
                        </div>
                    </div>
                `;
                $('#dynamic-fields').append(newField);
            });

            $('#adsPostForm').on('submit', function() {
                var textFields = [];
                $('input[name="text_title[]"]').each(function(index) {
                    var title = $(this).val();
                    var key = $('input[name="text_key[]"]').eq(index).val();
                    if (title && key) {
                        textFields.push({
                            title: title,
                            key: key
                        });
                    }
                });

                var serializedText = JSON.stringify(textFields);
                $('<input>').attr({
                    type: 'hidden',
                    name: 'text',
                    value: serializedText
                }).appendTo('#adsPostForm');
            });

        });
    </script>

    <script>
        function generate_option_from_json(jsonData, fromLoad) {
            //Load Category Json Data To Brand Select
            if (fromLoad === 'country_to_state') {
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="0" selected> @lang('app.select_state') </option>';
                    for (i in jsonData) {
                        option += '<option value="' + jsonData[i].id + '"> ' + jsonData[i].state_name + ' </option>';
                    }
                    $('#state_select').html(option);
                    $('#state_select').select2();
                } else {
                    $('#state_select').html('');
                    $('#state_select').select2();
                }
                $('#state_loader').hide('slow');

            } else if (fromLoad === 'state_to_city') {
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="0" selected> @lang('app.select_city') </option>';
                    for (i in jsonData) {
                        option += '<option value="' + jsonData[i].id + '"> ' + jsonData[i].city_name + ' </option>';
                    }
                    $('#city_select').html(option);
                    $('#city_select').select2();
                } else {
                    $('#city_select').html('');
                    $('#city_select').select2();
                }
                $('#city_loader').hide('slow');
            }
        }

        $(document).ready(function() {

            $('[name="country"]').change(function() {
                var country_id = $(this).val();
                $('#state_loader').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('get_state_by_country') }}',
                    data: {
                        country_id: country_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        generate_option_from_json(data, 'country_to_state');
                    }
                });
            });

            $('[name="state"]').change(function() {
                var state_id = $(this).val();
                $('#city_loader').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('get_city_by_state') }}',
                    data: {
                        state_id: state_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        generate_option_from_json(data, 'state_to_city');
                    }
                });
            });

            $('body').on('click', '.imgDeleteBtn', function() {
                //Get confirm from user
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }

                var current_selector = $(this);
                var img_id = $(this).closest('.img-action-wrap').attr('id');
                $.ajax({
                    url: '{{ route('delete_media') }}',
                    type: "POST",
                    data: {
                        media_id: img_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success == 1) {
                            current_selector.closest('.creating-ads-img-wrap').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
            /**
             * Change ads price by urgent or premium
             */

            $(document).on('change', '.price_input_group', function() {
                var price = 0;
                var checkedValues = $('.price_input_group input:checked').map(function() {
                    return $(this).data('price');
                }).get();

                for (var i = 0; i < checkedValues.length; i++) {
                    price += parseInt(checkedValues[i]); //don't forget to add the base
                }

                $('#payable_amount').text(price);
                $('#price_summery').show('slow');
            });

            $(document).on('click', '.image-add-more', function(e) {
                e.preventDefault();
                $('.upload-images-input-wrap').append(
                    '<input type="file" name="images[]" class="form-control" />');
            });

            // Select all available items
            $('#select-all-checkbox').change(function() {
                const isChecked = $(this).is(':checked');
                $('#available-items .item-checkbox').prop('checked', isChecked);
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
