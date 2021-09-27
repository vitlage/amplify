@extends('layouts.backend')

@section('title', trans('messages.create_currency'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box3">
    
          <form enctype="multipart/form-data" action="{{ action('Admin\CurrencyController@store') }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					@include('admin.currencies._form')			
					
				<form>
</div>
				
@endsection
