@extends('layouts.backend')

@section('title', $group->name)
	
@section('page_script')    
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')
                
                <form action="{{ action('Admin\CustomerGroupController@update', $group->id) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include("admin.customer_groups._form")				
					<hr />
					<div class="text-left">
						<button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
						<a href="{{ action('Admin\CustomerGroupController@index') }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
					</div>
					
				<form>
					
				
@endsection
