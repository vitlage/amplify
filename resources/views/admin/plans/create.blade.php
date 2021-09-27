@extends('layouts.backend')

@section('title', trans('messages.create_plan'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection



@section('content')
	<div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
			<p>{{ trans('messages.plan_create_message') }}</p>
			<form enctype="multipart/form-data" action="{{ action('Admin\PlanController@store') }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				@include('admin.plans._form')
			<form>
		</div>
	</div>
@endsection
