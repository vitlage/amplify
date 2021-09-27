@extends('layouts.backend')

@section('title', trans('messages.system_logs'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection



@section('content')
      <h2 class="text-semibold text-teal-800 mt-0">{{ trans('messages.last_300_logs') }}</h2>
			<textarea class="system_logs">{{ $error_logs }}</textarea>
@endsection
