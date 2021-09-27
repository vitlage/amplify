<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

	@include('layouts._favicon')

	@include('layouts._head')

	@include('layouts._css')

	@include('layouts._js')

	<!-- Custom langue -->
	<script>
		var LANG_CODE = 'en-US';
	</script>
	@if (Auth::user()->customer->getLanguageCodeFull())
		<script type="text/javascript" src="{{ URL::asset('assets/datepicker/i18n/datepicker.' . Auth::user()->customer->getLanguageCodeFull() . '.js') }}"></script>
		<script>
			LANG_CODE = '{{ Auth::user()->customer->getLanguageCodeFull() }}';
		</script>
	@endif

	<script>
		$.cookie('last_language_code', '{{ Auth::user()->customer->getLanguageCode() }}');
	</script>
	
	<link rel="stylesheet" href="{{ asset('graindashboard/css/graindashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
</head>

<body class="has-sidebar has-fixed-sidebar-and-header navbar-top">

    <!-- Header -->
<header class="top-header">

	<nav class="navbar flex-nowrap p-0">
		
		<div class="header-content col px-md-3">
			<div class="d-flex align-items-center">
				<!-- Side Nav Toggle -->
				<a  class="js-side-nav header-invoker d-flex mr-md-2" href="#"
					data-close-invoker="#sidebarClose"
					data-target="#sidebar"
					data-target-wrapper="body">
					<i class="gd-align-left fa-2x"></i>
				</a>
				<!-- End Side Nav Toggle -->
				<div class="navbar-brand-wrapper d-flex align-items-center col-auto">
					<!-- Logo For Mobile View -->
					<a class="navbar-brand navbar-brand-mobile" href="/">
						{{-- <img class="img-fluid w-100" src="public/img/logo-mini.png" alt="Graindashboard"> --}}
					</a>
					<!-- End Logo For Mobile View -->
		
					<!-- Logo For Desktop View -->
					<a class="navbar-brand navbar-brand-desktop" href="{{ action('Admin\HomeController@index') }}">
						@if (\Acelle\Model\Setting::get('site_logo_small'))
						<img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" width="150px" alt="">
						@else
						<img src="{{ URL::asset('images/logo-dark.svg') }}" width="150px" style="" alt="">
						@endif
					</a>
					<!-- End Logo For Desktop View -->
				</div>

				<!-- User Notifications -->
				@php			
				$iconDir = "";
					$iconDir = "dark/";
				
				@endphp
				<div class="dropdown ml-auto" style="margin-right: 10px;">
					<a id="notificationsInvoker" class="header-invoker" href="#" aria-controls="notifications" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#notifications" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
						<span class="visible-xs-inline-block position-right">{{ trans('messages.activity_log') }}</span>
						<i class="acelle-icon"><img src="{{ url('images/icons/'.$iconDir.'SVG/history.svg') }}" /></i>
						{{-- <i class="lnr lnr-alarm top-notification-icon"><img src="{{ url('images/icons/'.$iconDir.'SVG/history.svg') }}" /></i>  --}}
					</a>

					<div id="notifications" class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem unfold-css-animation unfold-hidden" aria-labelledby="notificationsInvoker" style="animation-duration: 300ms;">
						<div class="card">
							<div class="card-header d-flex align-items-center border-bottom py-3">
								{{ trans('messages.activity_log') }}
							</div>

							<div class="card-body p-0">
								<ul class="media-list dropdown-content-body top-history top-notifications">
									@if (Auth::user()->customer->logs()->count() == 0)
										<li class="text-center text-muted2">
											<span href="#">
												<i class="icon-history lnr lnr-bubble"></i> {{ trans('messages.no_notifications') }}
											</span>
										</li>
									@endif
									@foreach (Auth::user()->customer->logs()->take(20)->get() as $log)
										<li class="media">
											<div class="media-left">
												<img src="{{ action('CustomerController@avatar', $log->customer->uid) }}" class="img-circle img-sm" alt="">
											</div>
						
											<div class="media-body">
												<a href="#" class="media-heading">
													<span class="text-semibold">{{ $log->customer->displayName() }}</span>
													<span class="media-annotation pull-right">{{ $log->created_at->diffForHumans() }}</span>
												</a>
						
												<span class="text-muted desc text-muted" title='{!! $log->message() !!}'>{!! $log->message() !!}</span>
											</div>
										</li>
									@endforeach
									
								</ul>
								<div class="dropdown-content-footer">
									<a href="{{ action("AccountController@logs") }}" data-popup="tooltip" title="{{ trans('messages.all_logs') }}"><i class="icon-menu display-block"></i></a>
								</div>
							</div>
						</div>
					</div>

				   
				</div>
				<!-- End User Notifications -->
				<!-- User Avatar -->
				
			<li style="list-style: none;" class="dropdown dropdown-user">
				<a class="dropdown-toggle" data-toggle="dropdown">
					<img class="avatar rounded-circle mr-md-2" src="{{ action('CustomerController@avatar', Auth::user()->customer->uid) }}" alt="">
					<span>{{ Auth::user()->customer->displayName() }}</span>
					<i class="caret"></i>

					@if (Auth::user()->customer->hasSubscriptionNotice())
						<i class="material-icons customer-warning-icon text-danger">info</i>
					@endif
				</a>

				<ul class="dropdown-menu dropdown-menu-right">
					@can("admin_access", Auth::user())
						<li><a href="{{ action("Admin\HomeController@index") }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/changeview.svg') }}" />
						</i>{{ trans('messages.admin_view') }}</a></li>
						<li class="divider"></li>
					@endif
					@if (request()->user()->customer->activeSubscription())
						<li class="dropdown">
							<a href="#" class="top-quota-button d-flex align-items-center" data-url="{{ action("AccountController@quotaLog") }}">
							<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/sreport.svg') }}" />
						</i>
								<span class="">{{ trans('messages.used_quota') }}</span>
							</a>
						</li>
					@endif
					<li rel0="AccountSubscriptionController\index">
						<a href="{{ action('AccountSubscriptionController@index') }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/subscription.svg') }}" />
						</i>{{ trans('messages.subscriptions') }}
							@if (Auth::user()->customer->hasSubscriptionNotice())
								<i class="material-icons-outlined subscription-warning-icon text-danger">info</i>
							@endif
						</a>
					</li>
					<li><a href="{{ action("AccountController@billing") }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/billing.svg') }}" />
						</i>{{ trans('messages.billing') }}
					
					</a></li>
					<li><a href="{{ action("AccountController@profile") }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
						</i>{{ trans('messages.account') }}
					
					</a></li>
					@if (Auth::user()->customer->canUseApi())
						<li rel0="AccountController/api">
							<a href="{{ action("AccountController@api") }}" class="level-1 d-flex align-items-center">
							<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/api.svg') }}" />
						</i>{{ trans('messages.api') }}
							</a>
						</li>
					@endif
					<li><a href="{{ url("/logout") }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/logout.svg') }}" />
						</i>{{ trans('messages.logout') }}
					</a></li>
				</ul>
			</li>
			<!-- End User Avatar -->
			</div>
		</div>
	</nav>
</header>
<!-- End Header -->
<!-- Sidebar Nav -->	
<aside id="sidebar" class="js-custom-scroll side-nav">
    <ul id="sideNav" class="navbar-nav side-nav-menu side-nav-menu-top-level mb-0">
        
        <!-- Title -->
        <!-- End Title -->
        @php			
            $iconDir = "";
			$iconDir = "dark/";
            
        @endphp
        <!-- Dashboard -->
        <li class="" rel0="HomeController" style="">
            <a class="side-nav-menu-link align-items-center {{ (request()->is('/')) ? 'active-nav' : '' }}" href="{{ action('HomeController@index') }}">
                    <span class="lnr lnr-home custom-icon-size1 mr-3">
                    </span>
                {{ trans('messages.dashboard') }}
                <span class="{{ (request()->is('/')) ? 'active-nav-pilles5' : '' }} test33">|</span>
            </a>
        </li>
        <!-- End Dashboard -->

		<!-- Campaign -->
        <li class="" rel0="CampaignController" style="">
            <a class="side-nav-menu-link align-items-center {{ (request()->is('campaigns')) ? 'active-nav' : '' }}" href="{{ action('CampaignController@index') }}">
                    <i class="lnr lnr-location custom-icon-size1 mr-3">
                    </i>
                {{ trans('messages.campaigns') }}
                <span class="{{ (request()->is('campaigns')) ? 'active-nav-pilles5' : '' }} test33">|</span>
            </a>
        </li>
        <!-- End Campaign -->

		<!-- Automation2 -->
		<li class="" rel0="Automation2Controller" style="">
			<a class="side-nav-menu-link align-items-center {{ (request()->is('automation2')) ? 'active-nav' : '' }}" href="{{ action('Automation2Controller@index') }}">
					<i class="lnr lnr-clock custom-icon-size1 mr-3"></i>
				{{ trans('messages.Automations') }}
				<span class="{{ (request()->is('automation2')) ? 'active-nav-pilles6' : '' }} test33">|</span>
			</a>
		</li>
		<!-- End Automation2 -->
    

        <!-- MailList -->
        <li style="" class="side-nav-menu-item side-nav-has-menu"
        	rel0="MailListController"
			rel1="FieldController"
			rel2="SubscriberController"
			rel3="SegmentController"
			>
			<a class="side-nav-menu-link align-items-center {{ (request()->is('lists')) ? 'active-nav' : '' }}" href="{{ action('MailListController@index') }}">
                    <span class="lnr lnr-list custom-icon-size1 mr-4">
                    </span>
                </i>{{ trans('messages.lists') }}
                <span class="{{ (request()->is('lists')) ? 'active-nav-pilles7' : '' }} test33">|</span>
            </a>
        </li>                                                            
        <!-- End MailList -->

		<!-- Template -->
        <li style="" class="side-nav-menu-item side-nav-has-menu" rel0="TemplateController">
			<a class="side-nav-menu-link align-items-center {{ (request()->is('templates')) ? 'active-nav' : '' }}" href="{{ action('TemplateController@index') }}">
                    <i class="lnr lnr-license custom-icon-size1 mr-3">
                    </i>
                </i>{{ trans('messages.templates') }}
                <span class="{{ (request()->is('templates')) ? 'active-nav-pilles8' : '' }} test33">|</span>
            </a>
        </li>                                                            
        <!-- End MailList -->

        <!-- Admin -->
        @if (
            Auth::user()->customer->can("read", new Acelle\Model\SendingServer()) ||					
			Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()) ||
			Auth::user()->customer->can("read", new Acelle\Model\Blacklist()) ||
			true
		)
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" 
			rel0="SendingServerController"
			rel1="SendingDomainController"
			rel2="SenderController"
			rel3="EmailVerificationServerController"
			rel4="BlacklistController"
			rel5="TrackingDomainController""
        >
            <a class="side-nav-menu-link media align-items-center" href="#" data-target="#sendingser">
                    <i class="lnr lnr-exit custom-icon-size1 mr-4">
                    </i>
                    {{ trans('messages.sending') }}
                <span class="caret"></span>
            </a>
        
        <!-- Users: subUsers -->
		<ul id="sendingser" class="nav side-nav-menu side-nav-menu-second-level mb-0">
			@if (Auth::user()->customer->can("read", new Acelle\Model\SendingServer()))
			<li class="" rel0="SendingServerController">
				<a class="side-nav-menu-link align-items-center {{ (request()->is('sending_servers')) ? 'active-nav' : '' }}" 
				href="{{ action('SendingServerController@index') }}">
						<i class="lnr lnr-exit custom-icon-size mr-3">
						</i>
					{{ trans('messages.sending_severs') }}
					<span class="{{ (request()->is('sending_servers')) ? 'active-nav-pilles3' : '' }} test33">|</div>
				</a>
			</li>
			@endif
			<li style="" class="" 
				rel0="SendingDomainController">
				<a  class="side-nav-menu-link align-items-center {{ (request()->is('sending_domains')) ? 'active-nav' : '' }}" 
					href="{{ action('SendingDomainController@index') }}">
						<i class="lnr lnr-earth custom-icon-size mr-3">
						</i>
						{{ trans('messages.sending_domains') }}
						<span class="{{ (request()->is('sending_domains')) ? 'active-nav-pilles3' : '' }} test33">|</span>
				</a>
			</li>
			
			<li style="" class="" 
				rel0="SenderController">
				<a class="side-nav-menu-link align-items-center {{ (request()->is('senders')) ? 'active-nav' : '' }}" 
					href="{{ action('SenderController@index') }}">
						<i class="lnr lnr-license custom-icon-size mr-3">
						</i>
						{{ trans('messages.verified_senders')  }}
						<span class="{{ (request()->is('senders')) ? 'active-nav-pilles3' : '' }} test33">|</span>
				</a>
			</li>
			<li style="" class="" rel0="TrackingDomainController">
				<a  class="side-nav-menu-link align-items-center {{ (request()->is('tracking_domains')) ? 'active-nav' : '' }}" 
					href="{{ action('TrackingDomainController@index') }}">
						<i class="fa fa-globe-europe custom-icon-size mr-3">
						</i>
						{{ trans('messages.tracking_domains') }}
						<span class="{{ (request()->is('tracking_domains')) ? 'active-nav-pilles3' : '' }} test33">|</span>
				</a>
			</li>
			@if (Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()))
			<li style="" class="" 
				rel0="EmailVerificationServerController">
				<a  class="side-nav-menu-link align-items-center {{ (request()->is('email_verification_servers')) ? 'active-nav' : '' }}" 
					href="{{ action('EmailVerificationServerController@index') }}">
						<i class="fa fa-server custom-icon-size mr-3">
						</i>
						{{ trans('messages.email_verification_servers') }}
						<span class="{{ (request()->is('email_verification_servers')) ? 'active-nav-pilles' : '' }} test33">|</span>
				</a>
			</li>
			@endif
			@if (Auth::user()->customer->can("read", new Acelle\Model\Blacklist()))
			<li style="" class="" 
				rel0="BlacklistController">
				<a  class="side-nav-menu-link align-items-center {{ (request()->is('blacklists')) ? 'active-nav' : '' }}" 
					href="{{ action('BlacklistController@index') }}">
						<i class="fa fa-user-slash custom-icon-size mr-3">
						</i>
						{{ trans('messages.blacklist') }}
						<div class="{{ (request()->is('blacklists')) ? 'active-nav-pilles3' : '' }} test33">|</div>
				</a>
			</li>
			@endif
		</ul>
            <!-- End Users: subUsers -->
        </li>                                                            
        @endif
        <!-- End Admin -->

        <!-- Title -->
        <li class="sidebar-heading h6"></li>
        <li class="sidebar-heading h6"></li>
        <!-- End Title -->

    </ul>
</aside>
<!-- End Sidebar Nav -->
<main class="main">
    <!-- Page header -->
	<div class="page-header hide-header {{ (request()->is('senders')) ? 'show-header' : '' }}">
		<div class="page-header-content hide-header {{ (request()->is('senders')) ? 'show-header' : '' }}">

			@yield('page_header')

		</div>
	</div>
	<!-- /page header -->

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- display flash message -->
				@include('common.errors')

				<!-- main inner content -->
				@yield('content')

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

		<!-- Footer -->
		<!--<div class="footer text-muted">-->
		<!--	{!! trans('messages.copy_right') !!}-->
		<!--</div>-->
		<!-- /footer -->

	</div>
</main>
	@include("layouts._modals")

        {!! \Acelle\Model\Setting::get('custom_script') !!}

    <script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
	<script src="{{ asset('graindashboard/js/graindashboard.js') }}"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="{{ asset('graindashboard/js/graindashboard.vendor.js') }}"></script>
</body>
</html>
