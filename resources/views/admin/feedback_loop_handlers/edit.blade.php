@extends('layouts.backend')

@section('title', $server->name)

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box">
    
				<form enctype="multipart/form-data" action="{{ action('Admin\FeedbackLoopHandlerController@update', $server->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">

					@include('admin.feedback_loop_handlers._form')

				<form>
</div>

@endsection
