@extends('layouts.frontend')

@section('title', trans('messages.create_subscription'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')

    <form enctype="multipart/form-data" action="{{ action('SubscriptionController@store') }}" method="POST" class="form-validate-jqueryz subscription-form">
        {{ csrf_field() }}

        @include('subscriptions._form')
    <form>

@endsection
