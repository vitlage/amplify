@extends('layouts.frontend')

@section('title', trans('messages.select_campaign_type'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="row">
    <div class="col-md-10 ml-3 custom-box">
        <ul class="modern-listing big-icon no-top-border-list mt-0">
            @foreach (Acelle\Model\Campaign::types() as $key => $type)

                <li>
                    <a href="{{ action("CampaignController@create", ["type" => $key]) }}" class="btn btn-info bg-info-800">{{ trans('messages.choose') }}</a>
                    <a href="{{ action("CampaignController@create", ["type" => $key]) }}">
                        <span class="">
                            <i class="{{ $type['icon'] }} text-grey-800"></i>
                        </span>
                    </a>
                    <h4><a href="{{ action("CampaignController@create", ["type" => $key]) }}">{{ trans('messages.' . $key) }}</a></h4>
                    <p>
                        {{ trans('messages.campaign_intro_' . $key) }}
                    </p>
                </li>

            @endforeach

        </ul>
        <div class="">
            <a href="{{ action('CampaignController@index') }}" type="button" class="btn bg-grey">
                <i class="icon-cross2"></i> {{ trans('messages.cancel') }}
            </a>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
@endsection
