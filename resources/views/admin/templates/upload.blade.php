@extends('layouts.backend')

@section('title', trans('messages.upload_template'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection


@section('content')
    
    <div class="row">
        <div class="col-md-8 box-style1">
        
            <div class="alert alert-info">
                {!! trans('messages.template_upload_guide', ["link" => 'https://s3.amazonaws.com/acellemail/newsletter-template-green.zip']) !!}
            </div>
        
            <form enctype="multipart/form-data" action="{{ action('Admin\TemplateController@upload') }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}
                
                @include('helpers.form_control', ['required' => true, 'type' => 'text', 'label' => trans('messages.template_name'), 'name' => 'name', 'value' => old('name'), 'rules' => ['name' => 'required']])
                
                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file'])
                    
                <div class="text-right">
                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.upload') }}</button>
                    <a href="{{ action('Admin\TemplateController@index') }}" class="btn bg-grey-801"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                </div>
                
            </form>  
            
        </div>
    </div>

@endsection
