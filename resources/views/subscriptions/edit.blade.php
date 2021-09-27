@extends('layouts.frontend')

@section('title', trans('messages.update_subscription'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection



@section('content')

	<form enctype="multipart/form-data" action="{{ action('SubscriptionController@update', $subscription->uid) }}" method="POST" class="subscription-form">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PATCH">

		<div class="row">
			<div class="col-md-4">

			</div>
			<div class="col-md-6">
				@include('subscriptions._subscription')
			</div>
		</div>
	</form>

@endsection
