@extends('layouts.backend')

@section('title', trans('messages.create_sending_server'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')

    @include('admin.sending_servers.form.' . $server->type)
    
@endsection
