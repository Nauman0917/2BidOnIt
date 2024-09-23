@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default register-form">
                    <div class="panel-heading">@lang('app.register')</div>
                    <div class="panel-body">

                        @include('admin.flash_msg')
                        @include('auth.social_login')


                        <form class="form-horizontal" role="form" method="POST" action="{{ route('submit_auction_house') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">@lang('app.name')</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                        value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('phone_code') ? ' has-error' : '' }}">
                                <label for="phone_code" class="col-md-4 control-label">@lang('app.phone_code')</label>

                                <div class="col-md-6">
                                    <select class="form-control select2" name="phone_code" required>
                                        <option value="">@lang('app.phone_code')</option>
                                        @foreach ($countries as $name => $code)
                                            <option value="{{ $code }}">{{ $name }} ({{ $code }})
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('phone_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label for="phone" class="col-md-4 control-label">@lang('app.phone')</label>
                                
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone"
                                        value="{{ old('phone') }}" required>

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">@lang('app.email_address')</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">@lang('app.password')</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">@lang('app.confirm_password')</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            @if (get_option('enable_recaptcha_registration') == 1)
                                <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="g-recaptcha" data-sitekey="{{ get_option('recaptcha_site_key') }}">
                                        </div>
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-user-plus"></i> @lang('app.register')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (get_option('enable_recaptcha_registration') == 1)
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endif
