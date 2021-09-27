@extends('layouts.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.schedule'))
    
@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        

        @include('campaigns._steps', ['current' => 4])
    </div>

@endsection

@section('content')
<div class="custom-box1">
    <form action="{{ action('CampaignController@schedule', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-6 list_select_box" target-box="segments-select-box" segments-url="{{ action('SegmentController@selectBox') }}">
                @include('helpers.form_control', ['type' => 'date',
                                                    'class' => '_from_now',
                                                    'name' => 'delivery_date',
                                                    'label' => trans('messages.delivery_date'),
                                                    'value' => $delivery_date,
                                                    'rules' => $rules,
                                                    'help_class' => 'campaign'
                                                ])
            </div>
            <div class="col-md-6 segments-select-box">
                @include('helpers.form_control', ['type' => 'time',
                                                    'name' => 'delivery_time',
                                                    'label' => trans('messages.delivery_time'),
                                                    'value' => $delivery_time,
                                                    'rules' => $rules,
                                                    'help_class' => 'campaign'
                                                ])
            </div>
        </div>
        
        <hr>
        <div class="text-right">
            <button class="btn bg-teal-800">{{ trans('messages.save_and_next') }} <i class="icon-arrow-right7"></i> </button>
        </div>
        
    <form>
        </div>
    <script>
        $(document).ready(function() {
            // Pick a day from now
            // Date limits
            $('.pickadate_from_now').pickadate({
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
