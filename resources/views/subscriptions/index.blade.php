@extends('layouts.frontend')

@section('title', trans('messages.your_subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection



@section('content')

	<form class="listing-form"
		sort-url="{{ action('SubscriptionController@sort') }}"
		data-url="{{ action('SubscriptionController@listing') }}"
		per-page="{{ Acelle\Model\Subscription::$itemsPerPage }}"
	>
		<div class="pml-table-container">



		</div>
	</form>

@endsection
