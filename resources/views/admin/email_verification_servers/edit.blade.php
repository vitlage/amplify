@extends('layouts.backend')

@section('title', $server->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box1">

    <form enctype="multipart/form-data" action="{{ action('Admin\EmailVerificationServerController@update', $server->uid) }}" method="POST" class="form-validate-jqueryz email-verification-server-form">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">
        @include('admin.email_verification_servers._form')
    <form>
</div>
@endsection
