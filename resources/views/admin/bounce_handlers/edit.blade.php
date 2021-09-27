@extends('layouts.backend')

@section('title', $server->name)
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\BounceHandlerController@update', $server->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.bounce_handlers._form')
					
				<form>
	
@endsection