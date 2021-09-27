@extends('layouts.backend')

@section('title', trans('messages.click_log'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection



@section('content')
<div class="custom-box">
				
				<form class="listing-form"
					data-url="{{ action('Admin\ClickLogController@listing') }}"
					per-page="{{ Acelle\Model\ClickLog::$itemsPerPage }}"					
				>				
					<div class="row top-list-controls">
						<div class="col-md-9">
							@if ($items->count() >= 0)					
								<div class="filter-box">
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort-order">
                                            <option value="click_logs.created_at">{{ trans('messages.created_at') }}</option>
										</select>										
										<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
											<i class="icon-sort-amount-desc"></i>
										</button>
									</span>
									<span class="ml-10">
										<span class="title text-semibold text-muted">{{ trans('messages.campaign') }}</span>
										<select placeholder="{{ trans('messages.all') }}" class="select2-ajax" name="campaign_uid" data-url="{{ action('CampaignController@select2') }}">
											
										</select>	
									</span>
									<span class="text-nowrap">
										<input name="search_keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
										<i class="icon-search4 keyword_search_button"></i>
									</span>
								</div>
							@endif
						</div>
					</div>
					
					<div class="pml-table-container">
						
						
						
					</div>
				</form>				
</div>
				@endsection
