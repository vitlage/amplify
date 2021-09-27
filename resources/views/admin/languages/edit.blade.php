@extends('layouts.backend')

@section('title', $language->name)
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box1">
	
				<form enctype="multipart/form-data" action="{{ action('Admin\LanguageController@update', $language->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.languages._form')
					
				<form>
</div>
@endsection