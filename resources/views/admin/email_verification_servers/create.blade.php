@extends('layouts.backend')

@section('title', trans('messages.create_email_verification_server'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box1">

	<form action="{{ action('Admin\EmailVerificationServerController@store', ["type" => request()->type]) }}" method="POST" class="form-validate-jqueryz email-verification-server-form">
		{{ csrf_field() }}

		@include('admin.email_verification_servers._form')
	<form>
</div>
@endsection
