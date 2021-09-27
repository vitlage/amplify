@extends('layouts.frontend')

@section('title', trans('messages.create_sending_server'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	<p>{{ trans('messages.sending_server.wording') }}</p>

    <form action="{{ action('SendingServerController@store', ["type" => request()->type]) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}

        @include('sending_servers._form')
    <form>

@endsection
