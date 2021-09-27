@extends('layouts.frontend')

@section('title', trans('messages.create_sending_domain'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
    
    @include('senders._menu')
    
    <h2>
        <span class="text-semibold"><i class="icon-plus-circle2"></i> {{ trans('messages.create_sending_domain') }}</span>
    </h1>

    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.sending_domain.wording') !!}</p>
        </div>
    </div>

    <form action="{{ action('SendingDomainController@store') }}" method="POST" class="form-validate-jqueryz">
        @include('sending_domains._form')
	</form>

@endsection
