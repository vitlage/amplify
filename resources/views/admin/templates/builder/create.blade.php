@extends('layouts.backend')

@section('title', trans('messages.create_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection



@section('content')
    
    <div class="row">
        <div class="col-md-6">
            <form action="{{ action('Admin\TemplateController@builderCreate') }}" method="POST" class="template-form form-validate-jquery">
                {{ csrf_field() }}
                
                <input type="hidden" value="" name="layout" />
				<input type="hidden" value="" name="template" />
                
                <div class="sub_section">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'class' => '',
                        'name' => 'name',
                        'value' => $template->name,
                        'label' => 'Enter your template\'s name here',
                        'help_class' => 'template',
                        'rules' => ['name' => 'required']
                    ])
                </div>
            </form>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-12 custom-box1">
            <div class="d-flex align-items-bottom mt-4">
				<h3 class="text-semibold mr-auto mb-3 mt-0">{{ trans('messages.template.select_your_template') }}</h3>
				<div class="text-left">
					<button class="btn bg-teal mr-10 start-design"><i class="icon-check"></i> {{ trans('messages.template.create_and_design') }}</button>
				</div>
			</div>
				
			<ul class="nav nav-tabs mc-tabs">
				<li class="active"><a data-toggle="tab" href="#layout">{{ trans('messages.layouts') }}</a></li>
				<li><a data-toggle="tab" href="#gallery">{{ trans('messages.templates') }}</a></li>
			</ul>
			  
			<div class="tab-content">
				<div id="layout" class="tab-pane fade in active template-boxes" style="
					margin-left: -20px;
					margin-right: -20px;
				">
					@foreach(Acelle\Model\Template::templateStyles() as $name => $style)
						<div class="col-xxs-12 col-xs-6 col-sm-3 col-md-2">
							<a href="javascript:;" class="select-template-layout" data-layout="{{ $name }}">
								<div class="panel panel-flat panel-template-style">
									<div class="panel-body">
										<img src="{{ url('images/template_styles/'.$name.'.png') }}" />
										<h5 class="mb-20 text-center">{{ trans('messages.'.$name) }}</h5>
									</div>
								</div>
							</a>
						</div>
					@endforeach
				</div>
				<div id="gallery" class="tab-pane fade">
					<form class="listing-form"
						data-url="{{ action('Admin\TemplateController@builderTemplates') }}"
						per-page="{{ Acelle\Model\Template::$itemsPerPage }}"					
					>				
						<div class="row top-list-controls">
							<div class="col-md-9">
								@if ($templates->count() >= 0)					
									<div class="filter-box">										
										<span class="filter-group">
											<span class="title text-semibold text-muted">{{ trans('messages.from') }}</span>
											<select class="select" name="from">
												<option value="gallery" selected='selected'>{{ trans('messages.gallery') }}</option>
											</select>										
										</span>
										<span class="filter-group">
											<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
											<select class="select" name="sort-order">
												<option value="custom_order" class="active">{{ trans('messages.custom_order') }}</option>
												<option value="name">{{ trans('messages.name') }}</option>
												<option value="created_at">{{ trans('messages.created_at') }}</option>
											</select>										
											<button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
												<i class="icon-sort-amount-asc"></i>
											</button>
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
			</div>
        </div>
    </div>
        
    <script>	
        $(document).ready(function() {
            $(document).on('click', '.select-template-layout', function() {
                var layout = $(this).attr('data-layout');
				var template = $(this).attr('data-template');
                
                // unselect all layouts
                $('.select-template-layout').removeClass('selected');
                
                // select this
                $(this).addClass('selected');
				
				// unselect all
				$('[name=layout]').val('');
				$('[name=template]').val('');
                
                // update layout value
				if (typeof(layout) !== 'undefined') {
					$('[name=layout]').val(layout);
				}
				
				// update template value
				if (typeof(template) !== 'undefined') {
					$('[name=template]').val(template);
				}
            });
            
            $('.select-template-layout').eq(0).click();
            
            $(document).on('click', '.start-design', function() {
                var form = $('.template-form');
				
				if ($('.select-template-layout.selected').length == 0) {
					// Success alert
					swal({
						title: "{{ trans('messages.template.need_select_template') }}",
						text: "",
						confirmButtonColor: "#666",
						type: "error",
						allowOutsideClick: true,
						confirmButtonText: "{{ trans('messages.ok') }}",
						customClass: "swl-error",
						html:true
					});
					return;
				}
                
                if (form.valid()) {
                    form.submit();
                }
            });
        });
    </script>
    
@endsection
