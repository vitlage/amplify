@extends('layouts.backend')

@section('title', trans('messages.create_feedback_loop_handler'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box1">

                <form action="{{ action('Admin\FeedbackLoopHandlerController@store') }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}

					@include('admin.feedback_loop_handlers._form')
				<form>
</form>
@endsection
