@extends('layouts.backend')

@section('title', trans('messages.subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection



@section('content')
    <div class="custom-box mb-4">
        <p>{{ trans('messages.subscription.wording') }}</p>
    </div>

    <form class="listing-form custom-box"
        sort-url="{{ action('Admin\SubscriptionController@sort') }}"
        data-url="{{ action('Admin\SubscriptionController@listing') }}"
        per-page="15"
    >
        <div class="row top-list-controls">
            <div class="col-md-10">
                @if ($subscriptions->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <!--<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>-->
                            <select class="select" name="sort-order">
                                <option value="subscriptions.updated_at">{{ trans('messages.updated_at') }}</option>
                                <option value="subscriptions.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="subscriptions.ends_at">{{ trans('messages.ends_at') }}</option>
                            </select>
                            <button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
                                <i class="icon-sort-amount-desc"></i>
                            </button>
                        </span>
                        <span class="mr-10 input-medium">
                            <select placeholder="{{ trans('messages.customer') }}"
                                class="select2-ajax"
                                name="customer_uid"
                                data-url="{{ action('Admin\CustomerController@select2') }}">
                            </select>
                        </span>
                        <span class="mr-10 input-medium">
                            <select placeholder="{{ trans('messages.plan') }}"
                                class="select2-ajax"
                                name="plan_uid"
                                data-url="{{ action('Admin\PlanController@select2') }}">
                                    @if ($plan)
                                        <option value="{{ $plan->uid }}">{{ $plan->name }}</option>
                                    @endif
                            </select>
                        </span>
                    </div>
                @endif
            </div>
            @if (\Auth::user()->admin->can('create', new Acelle\Model\Subscription()))
                <div class="col-md-2 text-right">
                    <a href="{{ action("Admin\SubscriptionController@create") }}" type="button"
                        class="btn bg-info-800 modal-action new-subscription"
                    >
                        <i class="icon icon-plus2"></i> {{ trans('messages.subscription.new') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="pml-table-container">
        </div>
    </form>

    <script>
        var newSubscription = new Popup(); 
		$('.new-subscription').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			
			newSubscription.load(url);
		});

        // reject subscription modal
		var rejectPendingSub = new Popup();
    </script>
@endsection
