@extends('layouts.frontend')

@section('title', trans('messages.logs'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection



@section('content')
	
				@include("account._menu")	
	
				<form class="listing-form custom-box"
					data-url="{{ action('AccountController@logsListing') }}"
					per-page="{{ Acelle\Model\Log::$itemsPerPage }}"				
				>				
					<div class="row top-list-controls">
						<div class="col-md-10">
							@if ($logs->count() >= 0)					
								<div class="filter-box">
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort-order">
											<option value="created_at">{{ trans('messages.created_at') }}</option>
										</select>										
										<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
											<i class="icon-sort-amount-desc"></i>
										</button>
									</span>
                                    <span class="filter-group ml-10">
                                        <span class="title text-semibold text-muted">{{ trans('messages.type') }}</span>
										<select class="select" name="type">
											<option value="">{{ trans('messages.all') }}</option>
											<option value="list">{{ trans('messages.list') }}</option>
                                            <option value="segment">{{ trans('messages.segment') }}</option>
                                            <option value="page">{{ trans('messages.page') }}</option>
                                            <option value="subscriber">{{ trans('messages.subscriber') }}</option>
											<option value="campaign">{{ trans('messages.campaign') }}</option>
										</select>										
									</span>
									<!--<span class="text-nowrap">
										<input name="search_keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
										<i class="icon-search4 keyword_search_button"></i>
									</span>-->
								</div>
							@endif
						</div>
					</div>
					
					<div class="pml-table-container">
						
						
						
					</div>
				</form>
	
@endsection