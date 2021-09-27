@extends('layouts.backend')

@section('title', trans('messages.create_language'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box1">

                <form action="{{ action('Admin\LanguageController@store') }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					@include('admin.languages._form')
				<form>
	</div>				
@endsection
