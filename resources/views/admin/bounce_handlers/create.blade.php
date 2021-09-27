@extends('layouts.backend')

@section('title', trans('messages.create_bounce_handler'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
<div class="custom-box1">

                <form action="{{ action('Admin\BounceHandlerController@store') }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}

					@include('admin.bounce_handlers._form')
				<form>
</div>
@endsection
