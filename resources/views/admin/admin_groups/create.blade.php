@extends('layouts.backend')

@section('title', trans('messages.create_admin_group'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')

	<form action="{{ action('Admin\AdminGroupController@store') }}" method="POST" class="form-validate-jqueryz">
		{{ csrf_field() }}

		@include("admin.admin_groups._form")
		<hr />
		<div class="text-left">
			<button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
			<a href="{{ action('Admin\AdminGroupController@index') }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
		</div>
	<form>

@endsection
