@extends('layouts.frontend')

@section('title', trans('messages.tracking_domain.create'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.tracking_domain.wording') !!}</p>
        </div>
    </div>

    <form action="{{ action('TrackingDomainController@store') }}" method="POST" class="form-validate-jqueryz custom-box">
        @include('tracking_domains._form')
	</form>

@endsection
