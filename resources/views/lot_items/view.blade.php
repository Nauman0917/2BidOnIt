@extends('layouts.app')
@section('title')
    @if (!empty($title))
        {{ strip_tags($title) }} |
    @endif @parent
@endsection

@section('social-meta')
    <meta property="og:title" content="{{ safe_output($item->title) }}">
    <meta property="og:description"
        content="{{ substr(trim(preg_replace('/\s\s+/', ' ', strip_tags($item->description))), 0, 160) }}">
    <meta property="og:url" content="{{ route('view_item', [$item->id]) }}">
    <meta name="twitter:card" content="summary_large_image">
    <!--  Non-Essential, But Recommended -->
    <meta name="og:site_name" content="{{ get_option('site_name') }}">
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/fotorama-4.6.4/fotorama.css') }}">
@endsection

@section('content')

    <div class="page-header search-page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{ safe_output($item->item_name) }}</h2>
                    <div class="btn-group btn-breadcrumb">
                        <a href="{{ route('home') }}" class="btn btn-warning"><i class="glyphicon glyphicon-home"></i></a>

                        <a href="{{ route('view_item', [$item->id]) }}"
                            class="btn btn-warning">{{ safe_output($item->item_name) }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single-auction-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-xs-12">

                    @include('admin.flash_msg')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="ads-detail">
                        <div class="row">
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.item_weight')</h4>
                                {{ $item->item_weight }} {{ $item->weight_unit }}
                            </div>
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.item_type')</h4>
                                {{ $item->item_type }}
                            </div>
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.metal_type')</h4>
                                {{ $item->metal_type }}
                            </div>
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.metal_color')</h4>
                                {{ $item->metal_color }}
                            </div>
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.total_gem_weight')</h4>
                                {{ $item->total_gem_weight }}
                            </div>
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.item_size')</h4>
                                {{ $item->item_size }}
                            </div>
                            <div class="col-md-6"
                                style="display: flex; justify-content: space-between; align-items: center">
                                <h4>@lang('app.startPrice')</h4>
                                {{ $item->startPrice }}
                            </div>
                        </div>
                        <h4>@lang('app.description')</h4>
                        {!! nl2br(safe_output($item->detailed_description)) !!}
                    </div>

                    <div class="auction-img-video-wrap">
                        @if (!empty($item->images))
                            <legend>@lang('app.item_images')</legend>
                            <div class="ads-gallery">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="fotorama" data-nav="thumbs" data-allowfullscreen="true"
                                            data-width="100%">
                                            @foreach ($item->images as $img)
                                                <img src="{{ $img->image }}" alt="Not Available">
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div style="display: flex; justify-content: end; margin-bottom: 2rem;">
                        <a href="{{ route('generate_pdf', $item->id) }}">
                            <button class="btn btn-success">Download as PDF</button>
                        </a>
                    </div>
                    <div class="sidebar-widget">
                        <div class="widget">
                            <img src="{{ $item->bar_code_image }}" alt="{{ $item->item_name }}"
                                style="width: 100%; height: 100px;">
                            <p>{{ $item->serial_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr />
                    <div id="bid_history">
                        <h2>@lang('app.stone') @lang('app.info')</h2>

                        @if ($item->stones->count())
                            <table class="table table-striped">
                                <tr>
                                    <th>@lang('app.stone_type')</th>
                                    <th>@lang('app.stone_weight')</th>
                                    <th>@lang('app.stone_shape')</th>
                                    <th>@lang('app.stone_color')</th>
                                    <th>@lang('app.stones_quantity')</th>
                                    <th>@lang('app.stone_clarity')</th>
                                    <th>@lang('app.stone_certified')</th>
                                </tr>
                                @foreach ($item->stones as $stone)
                                    <tr>
                                        <td>{{ $stone->stone_type }}</td>
                                        <td>{{ $stone->stone_weight }} {{ $stone->stone_weight_exact }}</td>
                                        <td>{{ $stone->stone_shape }}</td>
                                        <td>{{ $stone->stone_color }}</td>
                                        <td>{{ $stone->stones_quantity }}</td>
                                        <td>{{ $stone->stone_clarity }}</td>
                                        <td>{{ $stone->stone_certified }}</td>
                                    </tr>
                                @endforeach

                            </table>
                        @else
                            <p>@lang('app.there_is_no_bids')</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="footer-features">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>@lang('app.sell_your_items_through')</h2>
                    <p>@lang('app.thousands_of_people_selling')</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.trusted_buyers')
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.swift_and_secure')
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.spam_free')
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.sell_your_items_quickly')
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <a href="{{ route('category') }}" class="btn btn-warning btn-lg"><i class="fa fa-search"></i>
                        @lang('app.browse_ads')</a>
                    <a href="{{ route('create_ad') }}" class="btn btn-warning btn-lg"><i class="fa fa-save"></i>
                        @lang('app.post_an_ad')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/fotorama-4.6.4/fotorama.js') }}"></script>
    <script src="{{ asset('assets/plugins/SocialShare/SocialShare.js') }}"></script>
    <script src="{{ asset('assets/plugins/form-validator/form-validator.min.js') }}"></script>
@endsection
