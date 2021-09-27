@extends('layouts.backend')

@section('title', trans('messages.create_payment_method'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	
	<form enctype="multipart/form-data" action="{{ action('Admin\PaymentMethodController@store') }}" method="POST" class="form-validate-jqueryz payment-method-form">
		{{ csrf_field() }}
		
		@include('admin.payment_methods._form')			
		
	<form>
	
@endsection
