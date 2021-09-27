@extends('layouts.backend')

@section('title', $admin->displayName())
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\AdminController@update', $admin->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.admins._form')
					
				<form>
	
@endsection