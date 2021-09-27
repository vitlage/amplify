@extends('layouts.backend')

@section('title', $customer->displayName())
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	@include('admin.customers._tabs')

	<form enctype="multipart/form-data" action="{{ action('Admin\CustomerController@update', $customer->uid) }}" method="POST" class="form-validate-jquery custom-box">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">
		
		@include('admin.customers._form')
		
	<form>
@endsection