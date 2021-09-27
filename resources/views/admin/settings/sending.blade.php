@extends('layouts.backend')

@section('title', trans('messages.settings'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection



@section('content')
			<form action="{{ action('Admin\SettingController@sending') }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				
				<div class="tabbable">
					
                    @include("admin.settings._tabs")
	
					<div class="tab-content">
						
						@include("admin.settings._sending")
						
					</div>
				</div>
					
				
			</form>
@endsection
