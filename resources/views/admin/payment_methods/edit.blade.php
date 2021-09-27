@extends('layouts.backend')

@section('title', $payment_method->name)
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\PaymentMethodController@update', $payment_method->uid) }}" method="POST" class="form-validate-jqueryx payment-method-form">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.payment_methods._form')
					
				<form>
	
@endsection