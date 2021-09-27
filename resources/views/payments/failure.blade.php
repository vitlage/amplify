@extends('layouts.frontend')

@section('title', trans('messages.subscription'))

@section('page_script')
@endsection



@section('content')
	@include("account._menu")

	<div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">

            <div class="sub-section">

				{!! trans('messages.subscription_paid_finish_messages_failure', ['plan' => $subscription->plan_name]) !!}

				<div class="text-left">
					<a href="{{ action('HomeController@index') }}" class="btn bg-teal"><i class="icon-home"></i> {{ trans('messages.go_to_dashboard') }}</a>
					<a href="{{ action('AccountController@subscription') }}" class="btn bg-grey">{{ trans('messages.check_your_subscriptions') }}</a>
				</div>
			</div>
		</div>
	</div>
@endsection
