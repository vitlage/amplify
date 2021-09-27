@extends('layouts.backend')

@section('title', $currency->name)
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	<div class="custom-box">
	    
				<form enctype="multipart/form-data" action="{{ action('Admin\CurrencyController@update', $currency->uid) }}" method="POST" class="form-validate-jqueryx">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.currencies._form')
					
				<form>
	</div>
	
@endsection