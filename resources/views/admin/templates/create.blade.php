@extends('layouts.backend')

@section('title', trans('messages.create_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection



@section('content')
    
                <div class="row">
                    <div class="col-md-12 custom-box">
                        <form action="{{ action('Admin\TemplateController@store') }}" method="POST" class="ajax_upload_form form-validate-jquery">
                            {{ csrf_field() }}
                            
                            @include('templates._form')
							
							<div class="text-right">
                                <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                                <a href="{{ action('Admin\TemplateController@index') }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                            </div>
                            
                        </form>  
                        
                    </div>
                </div>
@endsection
