<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        @if (!empty($title))
            {{ strip_tags($title) }} |
        @endif
        {{ config('app.name') }}
    </title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .page-header h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        .btn-breadcrumb {
            margin-top: 10px;
            display: inline-block;
        }

        .btn-breadcrumb a {
            background-color: #ffc107;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
            font-size: 14px;
        }

        .single-auction-wrap {
            margin-bottom: 30px;
        }

        .ads-detail h4 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .ads-detail p {
            margin: 5px 0;
            font-size: 14px;
        }

        .ads-gallery {
            margin-top: 20px;
        }

        .ads-gallery img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .sidebar-widget {
            margin-top: 20px;
            text-align: center;
        }

        .sidebar-widget img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
        }

        .footer-features {
            background-color: #f5f5f5;
            padding: 30px 20px;
            text-align: center;
            margin-top: 40px;
        }

        .footer-features h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .footer-features p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        .icon-text-feature {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 16px;
            color: #333;
        }

        .icon-text-feature i {
            font-size: 20px;
            color: #28a745;
            margin-right: 10px;
        }

        .btn-lg {
            font-size: 16px;
            padding: 10px 20px;
            color: #fff;
            background-color: #ffc107;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
            display: inline-block;
        }

        /* Fotorama Styles */
        .fotorama {
            width: 100%;
            margin: 0 auto;
        }

        .fotorama__img {
            width: 100%;
            height: auto;
        }

        .glyphicon-home:before {
            content: "\f015";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="single-auction-wrap">
            <div>
                <div class="ads-detail">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                        <div style="width: 100%; margin-bottom: 10px;">
                            <h2>{{ $item->item_name }}</h2>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Item Weight</h4>
                            <p>{{ $item->item_weight }} {{ $item->weight_unit }}</p>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Item Type</h4>
                            <p>{{ $item->item_type }}</p>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Metal Type</h4>
                            <p>{{ $item->metal_type }}</p>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Metal Color</h4>
                            <p>{{ $item->metal_color }}</p>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Total Gem Weight</h4>
                            <p>{{ $item->total_gem_weight }}</p>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Item Size</h4>
                            <p>{{ $item->item_size }}</p>
                        </div>
                        <div style="width: 48%; margin-bottom: 10px;">
                            <h4>Start Price</h4>
                            <p>{{ $item->startPrice }}</p>
                        </div>
                        <div style="clear: both;">

                        </div>
                    </div>
                    <h4>Item Description</h4>
                    <p>{!! nl2br(safe_output($item->detailed_description)) !!}</p>
                    <hr>
                </div>

                @if (!empty($item->images))
                    <div class="ads-gallery">
                        <h4>@lang('app.item_images')</h4>
                        @foreach ($item->images as $img)
                            {{-- @php
                                $imagePath = $img->image;

                                if (Storage::disk('s3')->exists($imagePath)) {
                                    $imageContents = Storage::disk('s3')->get($imagePath);
                                    $imageData = base64_encode($imageContents);
                                    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                                    $base64Image = 'data:image/' . $imageType . ';base64,' . $imageData;
                                } else {
                                    $base64Image = null;
                                }
                            @endphp --}}
                            {{-- @if ($base64Image) --}}
                            <img src="{{ $img->image }}" alt="{{ $item->item_name }}"
                                style="width: 100%; height: auto;">
                            {{-- @endif --}}
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="sidebar-widget">
                <div class="widget">
                    <h4>@lang('app.bar_code')</h4>
                    {{-- @php
                        $bar_code_imagePath = $item->bar_code_image;

                        if (Storage::disk('s3')->exists($bar_code_imagePath)) {
                            $bar_code_imageContents = Storage::disk('s3')->get($bar_code_imagePath);
                            $bar_code_imageData = base64_encode($bar_code_imageContents);
                            $bar_code_imageType = pathinfo($bar_code_imagePath, PATHINFO_EXTENSION);
                            $bar_code_base64Image =
                                'data:image/' . $bar_code_imageType . ';base64,' . $bar_code_imageData;
                        } else {
                            $bar_code_base64Image = null;
                        }
                    @endphp --}}
                    {{-- @if ($bar_code_base64Image) --}}
                    <img src="{{ $item->bar_code_image }}" alt="{{ $item->item_name }}"
                        style="width: 100%; height: auto;">
                    {{-- @endif --}}
                    <p>{{ $item->serial_number }}</p>
                </div>
            </div>
        </div>

        <div>
            <h2>@lang('app.stone') @lang('app.info')</h2>
            @if ($item->stones->count())
                <table>
                    <thead>
                        <tr>
                            <th>@lang('app.stone_type')</th>
                            <th>@lang('app.stone_weight')</th>
                            <th>@lang('app.stone_shape')</th>
                            <th>@lang('app.stone_color')</th>
                            <th>@lang('app.stones_quantity')</th>
                            <th>@lang('app.stone_clarity')</th>
                            <th>@lang('app.stone_certified')</th>
                        </tr>
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            @else
                <p>@lang('app.there_is_no_bids')</p>
            @endif
        </div>
    </div>

</body>

</html>
