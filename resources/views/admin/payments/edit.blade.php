@extends('layouts.backend')

@section('title', trans('messages.payments.' . $gateway['name']))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection



@section('content')
		<div class="row">
			<div class="col-md-6">
				<p>
					{!! trans('messages.payment.' . $gateway['name'] . '.wording') !!}
				</p>
			</div>
		</div>
			
		@include('admin.payments._' . $gateway['name']) 

@endsection