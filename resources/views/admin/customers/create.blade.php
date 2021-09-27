@extends('layouts.backend')

@section('title', trans('messages.create_customer'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
          <form enctype="multipart/form-data" action="{{ action('Admin\CustomerController@store') }}" method="POST" class="form-validate-jqueryz custom-box">
					{{ csrf_field() }}
					
					@include('admin.customers._form')			
					
				<form>
				
@endsection
