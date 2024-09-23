@extends('layouts.app')
@section('title')
    @if (!empty($title))
        {{ $title }} |
    @endif @parent
@endsection

@section('page-css')
    <style>
        .modal-content {
            background-color: #f8f9fa;
            /* Custom color for the modal content */
        }

        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.5);
            /* Custom color for the backdrop */
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div id="wrapper">

            @include('admin.sidebar_menu')

            <div id="page-wrapper">
                @if (!empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }} </h1>
                        </div>
                    </div>
                @endif
                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-xs-12">
                        <div class="alert alert-info">
                            <p>Double Click on the images to edit them.</p>
                        </div>
                        <div style="display: flex;justify-content: space-between;">
                            <div class="import-export" style="display: flex; align-items: center; width:100%;">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                                    @lang('app.import_items')
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle exportButton" type="button"
                                        id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        @lang('app.export_items')
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li><a href="#" class="exportDataInCSV">Bid Sprit Format</a></li>
                                        <li><a href="#" class="exportDataInCSVLocal">Local Format</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div style="margin-bottom: 1rem">
                                <a href="{{ route('add_item') }}">
                                    <button class="btn btn-primary">
                                        @lang('app.add_item')
                                    </button>
                                </a>
                            </div>
                        </div>
                        @if ($items->total() > 0)
                            <table class="table table-bordered table-striped table-responsive">

                                @foreach ($items as $item)
                                    <tr>
                                        <td width="100">
                                            @foreach ($item->images as $img)
                                                @if ($img->image)
                                                    <img src="{{ asset($img->image) }}"
                                                        data-image-path="{{ $img->path }}"
                                                        data-image-model="LotItemImage" data-parent-id="{{ $item->id }}"
                                                        data-image-id="{{ $img->id }}" class="thumb-listing-table"
                                                        alt="No Image" style="width: 50px; height: 50px; margin: 3px;">
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('edit_item', [$item->id]) }}">
                                                {{ $item->item_name }}
                                            </a>
                                            <h5>Weight: {{ $item->item_weight }}</h5>
                                            <p class="text-muted">
                                                {!! \Illuminate\Support\Str::limit($item->detailed_description, 40, '...') !!}
                                            </p>
                                        </td>
                                        <td>
                                            <h5>Total Stones: <span>{{ count($item->stones) }}</span></h5>
                                            @foreach ($item->stones as $key => $stone)
                                                <h5>Stone {{ $key + 1 }} Type: {{ $stone->stone_type }}</h5>
                                            @endforeach
                                        </td>

                                        <td width="100">
                                            @if ($item->bar_code_image)
                                                <div class="d-flex justify-content-center">
                                                    <img src="{{ asset($item->bar_code_image) }}"
                                                        class="thumb-listing-table" alt="No Image"
                                                        style="width: 70px; height: 40px; margin: 3px;">
                                                    <p style="font-size: small">{{ $item->serial_number }}</p>
                                                </div>
                                            @else
                                                <p style="font-size: small">Not Available</p>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('edit_item', $item->id) }}" class="btn btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="{{ route('view_item', $item->id) }}" class="btn btn-success"
                                                target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if (auth()->user()->user_type != 'auction_editor')
                                                <a href="javascript:;" class="btn btn-danger deleteItem"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @else
                            <h2>@lang('app.no_items_are_added_yet')</h2>
                        @endif

                        {!! $items->links() !!}

                    </div>
                </div>

            </div> <!-- /#page-wrapper -->

        </div> <!-- /#wrapper -->


    </div> <!-- /#container -->
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">@lang('app.import_items')</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('import_items') }}" class="form-horizontal" id="importForm" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('import_items') ? 'has-error' : '' }}">
                            <label for="import_items" class="col-sm-4 control-label">@lang('app.import_items')</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="import_items" id="import_items"
                                    placeholder="@lang('app.import_items')">

                                {!! $errors->has('import_items')
                                    ? '<p class="help-block" id="import_items_error">' . $errors->first('import_items') . '</p>'
                                    : '' !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="import_csv_format" class="col-sm-4 control-label">@lang('app.import_csv_format')</label>
                            <div class="col-sm-3">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="import_csv_format" value="bidSpirit">
                                        @lang('app.bidSpirit')
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="import_csv_format" value="local">
                                        @lang('app.local')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('delete_old_items') ? 'has-error' : '' }}">
                            <div class="col-sm-8 col-sm-offset-4" style="padding-top: 2rem;">
                                <input type="checkbox" name="delete_old_items" placeholder="@lang('app.delete_old_items')"
                                    id="delete_old_items" value="1">
                                <label for="delete_old_items" class="control-label" for="delete_old_items">
                                    @lang('app.delete_old_items')
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                    @lang('app.save')</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('image-editor')
    @include('layouts.imageEditor')
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            $('.deleteItem').on('click', function() {
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var selector = $(this);
                var id = selector.data('id');
                $.ajax({
                    url: '{{ route('delete_item') }}',
                    type: "POST",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success == 1) {
                            selector.closest('tr').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });

            $('.exportDataInCSV').on('click', function(event) {
                event.preventDefault();

                let exportButton = $('.exportButton');
                exportButton.text("Loading...").attr("disabled", true);

                $.ajax({
                    url: '{{ route('export_items') }}',
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'items.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        toastr.success('@lang('app.success')', toastr_options);
                        exportButton.text("@lang('app.export_items')").attr("disabled", false);
                    },
                    error: function(error) {
                        toastr.error('@lang('app.error_exporting')', toastr_options);
                    }
                });
            });

            $('.exportDataInCSVLocal').on('click', function(event) {
                event.preventDefault();

                $(this).text = "Loading";
                $.ajax({
                    url: '{{ route('export_items_local_format') }}',
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'items.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        toastr.success('@lang('app.success')', toastr_options);
                    },
                    error: function(error) {
                        toastr.error('@lang('app.error_exporting')', toastr_options);
                    }
                });
            });

            $('#importForm').on('submit', function(e) {
                e.preventDefault();

                if (document.getElementById('import_items').files.length == 0) {
                    alert('File input not found.');
                    return;
                }

                var deleteOldItemsChecked = $('#delete_old_items').is(':checked');
                if (deleteOldItemsChecked) {
                    if (!confirm(
                            'Are you sure you want to delete all old items? This action cannot be undone.'
                        )) {
                        return;
                    }
                }

                var importFormat = $('input[name="import_csv_format"]:checked').val();

                var $submitButton = $(this).find(':submit');
                var originalButtonText = $submitButton.text();
                $submitButton.text('Loading...').prop('disabled', true);

                let formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');

                if (importFormat == "bidSpirit") {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $submitButton.text(originalButtonText).prop('disabled', false);
                            $('#myModal').modal('hide');
                            toastr.success('@lang('app.success')', toastr_options);

                            location.reload();
                        },
                        error: function(err) {
                            $submitButton.text(originalButtonText).prop('disabled', false);
                            toastr.error(err.responseJSON.error);
                        }
                    });
                } else if (importFormat == "local") {
                    $.ajax({
                        url: '{{ route('import_items_local_format') }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $submitButton.text(originalButtonText).prop('disabled', false);
                            $('#myModal').modal('hide');
                            toastr.success('@lang('app.success')', toastr_options);

                            location.reload();
                        },
                        error: function(err) {
                            $submitButton.text(originalButtonText).prop('disabled', false);
                            toastr.error(err.responseJSON.error);
                        }
                    });
                } else {
                    alert('Please select the import CSV file format');
                }
            });
        });
    </script>

    <script>
        @if (session('success'))
            toastr.success('{{ session('success') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
        @if (session('error'))
            toastr.error('{{ session('error') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
    </script>
@endsection
