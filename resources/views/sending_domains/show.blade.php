@extends('layouts.frontend')

@section('title', $server->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection



@section('content')
    
    @include('senders._menu')

    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <h1>
                <span class="text-semibold"><i class="icon-earth"></i> {{ $server->name }}</span>
            </h1>
            <p>{!! trans('messages.sending_domain.wording') !!}</p>

            <h3>{{ trans('messages.sending_domain.dkim_title') }}</h3>
            <p>{!! trans('messages.sending_domain.dkim_wording') !!}</p>
            <p>{!! trans('messages.sending_domain.spf_wording') !!}</p>
        </div>

        <div class="col-sm-12 col-md-12 mt-20">
            <div class="scrollbar-boxx dim-box">
                <div class="listing-form"
					data-url="{{ action('SendingDomainController@records', $server->uid) }}"
					per-page="1">
                    <div class="pml-table-container">                        
                    </div>
                </div>                
            </div>
        </div>
    </div>
        
    <hr >
    <div class="text-left">
        <a callback="" data-method="POST" href="{{ action('SendingDomainController@verify', $server->uid) }}" class="btn btn-primary bg-teal ajax_link">{{ trans('messages.sending_domain.verify') }}</a>
    </div>

@endsection
