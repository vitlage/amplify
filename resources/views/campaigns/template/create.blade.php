@extends('layouts.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>	
@endsection



@section('content')
    <div class="row">
        <div class="col-md-6">
            <h2 class="mt-0">{{ trans('messages.campaign.content_management') }}</h2>
            <h3>{{ trans('messages.campaign.email_content') }}</h3>                
            <p>{{ trans('messages.campaign.email_content.intro') }}</p>
                
            <ul class="hover-list">
                <li class="template-start" data-url="{{ action('CampaignController@templateLayout', $campaign->uid) }}">
                    <i class="lnr lnr-file-add"></i>
                    <div class="list-body">
                        <h4>{{ trans('messages.campaign.template.from_layout') }}</h4>
                        <p>{{ trans('messages.campaign.template.from_layout.intro') }}</p>
                    </div>
                    <div class="list-action">
                        <button
							class="btn btn-primary bg-grey-800"
						>
                            {{ trans('messages.campaign.template.start') }}
                        </button>
                    </div>
                </li>
                <li class="template-start" data-url="{{ action('CampaignController@templateTheme', $campaign->uid) }}">
                    <i class="lnr lnr-license"></i>
                    <div class="list-body">
                        <h4>{{ trans('messages.campaign.template.from_theme') }}</h4>
                        <p>{{ trans('messages.campaign.template.from_theme.intro') }}</p>
                    </div>
                    <div class="list-action">
                        <button
							class="btn btn-primary bg-grey-800"
						>
                            {{ trans('messages.campaign.template.start') }}
                        </button>
                    </div>
                </li>
                <li class="template-start" data-url="{{ action('CampaignController@templateUpload', $campaign->uid) }}">
                    <i class="lnr lnr-upload"></i>
                    <div class="list-body">
                        <h4>{{ trans('messages.campaign.template.upload') }}</h4>
                        <p>{{ trans('messages.campaign.template.upload.intro') }}</p>
                    </div>
                    <div class="list-action">
                        <button
							class="btn btn-primary bg-grey-800"
						>
                            {{ trans('messages.campaign.template.start') }}
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
        
    <script>
		var templatePopup = new Popup();
    
        $(document).ready(function() {
        
            $('.template-start').click(function() {
				var url = $(this).attr('data-url');
				
                templatePopup.load(url);
            });
        
        });
    </script>

@endsection
