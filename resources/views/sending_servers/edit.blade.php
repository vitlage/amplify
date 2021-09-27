@extends('layouts.frontend')

@section('title', trans('messages.' . $server->type))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
    <p>{{ trans('messages.sending_server.wording') }}</p>

    <form enctype="multipart/form-data" action="{{ action('SendingServerController@update', ["uid" => $server->uid, "type" => request()->type]) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">

        @include('sending_servers._form')
    <form>
@endsection
