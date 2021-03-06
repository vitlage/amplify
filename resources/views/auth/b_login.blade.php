@extends('layouts.clean')

@section('title', trans('messages.login'))

@section('content')
<!-- Advanced login -->
<form class="" role="form" method="POST" action="{{ url('/login') }}">
    {{ csrf_field() }}

    <div class="panel panel-body">

        <h4 class="text-semibold mt-0">{{ trans('messages.login') }}</h4>

        <div class="form-group has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="form-control" name="email" placeholder="{{ trans("messages.email") }}"
	 value="{{ old('email') ? old('email') : (isset(\Acelle\Model\User::getAuthenticateFromFile()['email']) ? \Acelle\Model\User::getAuthenticateFromFile()['email'] : "") }}"
            >
            <div class="form-control-feedback icon-setting">
	<i class="icon-envelop5 text-muted"></i>
            </div>
            @if ($errors->has('email'))
	<span class="help-block">
	    <strong>{{ $errors->first('email') }}</strong>
	</span>
            @endif
        </div>

        <div class="form-group has-feedback has-feedback-left{{ $errors->has('password') ? ' has-error' : '' }}">
            <input id="password" type="password" class="form-control" name="password" placeholder="{{ trans("messages.password") }}"
	value="{{ isset(\Acelle\Model\User::getAuthenticateFromFile()['password']) ? \Acelle\Model\User::getAuthenticateFromFile()['password'] : "" }}"
            >
            <div class="form-control-feedback">
	<i class="icon-lock2 text-muted"></i>
            </div>
            @if ($errors->has('password'))
	<span class="help-block">
	    <strong>{{ $errors->first('password') }}</strong>
	</span>
            @endif
        </div>

        <div class="form-group login-options">
            <div class="row">
	<div class="col-sm-6">
	    <label class="checkbox-inline">
	        <input type="checkbox" class="styled" checked="checked" name="remember">
	        {{ trans("messages.stay_logged_in") }}
	    </label>
	</div>

	<div class="col-sm-6 text-right text-semibold">
	    <a href="{{ url('/password/reset') }}">{{ trans("messages.forgot_password") }}</a>
	</div>
            </div>
        </div>

        @if (\Acelle\Model\Setting::get('login_recaptcha') == 'yes')
            {!! \Acelle\Library\Tool::showReCaptcha($errors) !!}
        @endif

        <button type="submit" class="btn btn-lg bg-teal btn-block login-button">{{ trans("messages.login") }} <i class="icon-circle-right2 position-right"></i></button>
    </div>

    @if (\Acelle\Model\Setting::get('enable_user_registration') == 'yes')
        <div class="text-center" style="font-size: 16px;">
            {!! trans('messages.need_a_account_create_an_one', [
	'link' => action('UserController@register')
            ]) !!}
        </div>
    @endif
</form>
<!-- /advanced login -->

<script>
    function addButtonLoadingEffect(button) {
        button.addClass('button-loading');
        button.append('<div class="loader"></div>');
    }

    function removeButtonLoadingEffect(button) {
        button.removeClass('button-loading');
        button.find('.loader').remove();
    }
    $('.login-button').on('click', function(e) {
        e.preventDefault();

        $(this).html('{{ trans('messages.login.please_wait') }}');

        $(this).closest('form').addClass('loading');

        addButtonLoadingEffect($(this));

        $(this).closest('form').submit();
    });
</script>

@endsection
