<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

	@include('layouts._favicon')

	@include('layouts._head')

	@include('layouts._css')

	@include('layouts._js')

	<script>
		$.cookie('last_language_code', '{{ Auth::user()->admin->getLanguageCode() }}');
	</script>

	<!-- Custom langue -->
	<script>
		var LANG_CODE = 'en-US';
	</script>
	@if (Auth::user()->admin->getLanguageCodeFull())
		<script type="text/javascript" src="{{ URL::asset('assets/datepicker/i18n/datepicker.' . Auth::user()->admin->getLanguageCodeFull() . '.js') }}"></script>
		<script>
			LANG_CODE = '{{ Auth::user()->admin->getLanguageCodeFull() }}';
		</script>
	@endif
	<link rel="stylesheet" href="{{ asset('graindashboard/css/graindashboard.css') }}">
	<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

</head>

<body class="has-sidebar has-fixed-sidebar-and-header navbar-top">
<!-- Header -->
	<header class="top-header">
		<nav class="navbar flex-nowrap p-0">
            <div class="header-content col px-md-3">
                <div class="d-flex align-items-center">
					<a  class="js-side-nav header-invoker d-flex mr-md-2" href="#"
						data-close-invoker="#sidebarClose"
						data-target="#sidebar"
						data-target-wrapper="body">
						<i class="gd-align-left fa-2x"></i>
					</a>
                    <!-- Side Nav Toggle -->
					<!-- End Side Nav Toggle -->
                    
					<!-- User Notifications -->
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
                    <div class="dropdown ml-auto" style="margin-right: 10px;">
						<a id="notificationsInvoker" class="header-invoker" href="#" aria-controls="notifications" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#notifications" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
							<span class="visible-xs-inline-block position-right">{{ trans('messages.activity_log') }}</span>
                            @if (Acelle\Model\Notification::count())
                            {{-- <span class="badge badge-danger top-notification-alert">!</span> --}}
                                {{-- <span class="indicator-top-right tabs-warning-icon"> --}}
                                    <i class="material-icons-outlined tabs-warning-icon text-danger top-notification-alert">info</i>
                                {{-- </span> --}}
							@endif
                            <i class="lnr lnr-alarm top-notification-icon"></i> 
						</a>
	
						<div id="notifications" class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem unfold-css-animation unfold-hidden" aria-labelledby="notificationsInvoker" style="animation-duration: 300ms;">
							<div class="card">
								<div class="card-header d-flex align-items-center border-bottom py-3">
									{{ trans('messages.activity_log') }}
								</div>
	
								<div class="card-body p-0">
                                    <ul class="media-list dropdown-content-body top-history top-notifications">
                                        @if (Auth::user()->admin->notifications()->count() == 0)
                                            <li class="text-center text-muted2">
                                                <span href="#">
                                                    <i class="lnr lnr-bubble"></i> {{ trans('messages.no_notifications') }}
                                                </span>
                                            </li>
                                        @endif
                                        @foreach (Auth::user()->admin->notifications()->take(20)->get() as $notification)
                                            <li class="media">
                                                <div class="media-left">
                                                    @if ($notification->level == \Acelle\Model\Notification::LEVEL_WARNING)
                                                        <i class="lnr lnr-warning bg-warning"></i>
                                                    @elseif ( false &&$notification->level == \Acelle\Model\Notification::LEVEL_ERROR)
                                                        <i class="lnr lnr-cross bg-danger"></i>
                                                    @else
                                                        <i class="lnr lnr-menu bg-info"></i>
                                                    @endif
                                                </div>
                            
                                                <div class="media-body">
                                                    <a href="#" class="media-heading">
                                                        <span class="text-semibold">{{ $notification->title }}</span>
                                                        <span class="media-annotation pull-right">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </a>
                            
                                                    <span class="text-muted desc text-muted" title='{!! $notification->message !!}'>{{ $notification->message }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                        
                                    </ul>
									<div class="dropdown-content-footer">
                                        <a href="{{ action("Admin\NotificationController@index") }}" data-popup="tooltip" title="{{ trans('messages.all_notifications') }}"><i class="icon-menu display-block"></i></a>
                                    </div>
								</div>
							</div>
						</div>

                       
                    </div>
					<!-- End User Notifications -->
					<!-- User Avatar -->
                    @php			
                        $iconDir = "";
                        if (Auth::user()->admin->getColorScheme() == 'white') {
                            $iconDir = "dark/";
                        }
                    @endphp
                <li style="list-style: none;" class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img class="avatar rounded-circle mr-md-2" src="{{ action('AdminController@avatar', Auth::user()->admin->uid) }}" alt="">
						<span>{{ Auth::user()->admin->displayName() }}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						@can("customer_access", Auth::user())
							<li><a href="{{ action("HomeController@index") }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/changeview.svg') }}" />
							</i>{{ trans('messages.customer_view') }}
							</a></li>
							<li class="divider"></li>
						@endif
						<li><a href="{{ action("Admin\AccountController@profile") }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
							</i>{{ trans('messages.account') }}
						</a></li>
						<li rel0="AccountController/api">
							<a href="{{ action("Admin\AccountController@api") }}" class="level-1 d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/api.svg') }}" />
							</i>{{ trans('messages.api') }}
							</a>
						</li>
						<li><a href="{{ url("/logout") }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/logout.svg') }}" />
							</i>{{ trans('messages.logout') }}</a></li>
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
            if (Auth::user()->admin->getColorScheme() == 'white') {
            }
        @endphp
        <!-- Dashboard -->
        <li class="" 
        style="width: 215px;">
            <a class="side-nav-menu-link media align-items-center {{ (request()->is('admin')) ? 'active-nav' : '' }}" href="{{ action('Admin\HomeController@index') }}">
                    <i class="mr-3 lnr lnr-home custom-icon-size1">
                        <!--<img src="{{ url('images/icons/'.$iconDir.'SVG/home.svg') }}" />   -->
                    </i>
                {{ trans('messages.dashboard') }}
                <div class="{{ (request()->is('admin')) ? 'active-nav-pilles5' : '' }} test33">|</div>
                <!--<span class="side-nav-menu-icon d-flex mr-3">-->
                <!--</span>-->
            </a>
        </li>
        <!-- End Dashboard -->
    
    
        <!-- Customer -->
        @if (Auth::user()->can("read", new Acelle\Model\Customer()))
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" rel0="SubscriptionController"
        rel1="CustomerController">
            <a class="side-nav-menu-link media align-items-center" href="#" data-target="#subUsers">
                    <i class="lnr lnr-user custom-icon-size1 mr-4"></i>
                {{ trans('messages.customer') }}
                <span class="caret"></span>
            </a>
        
        <!-- Users: subUsers -->
            <ul id="subUsers" class="nav side-nav-menu side-nav-menu-second-level mb-0">
                <li class="" rel0="CustomerController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/customers')) ? 'active-nav' : '' }}" href="{{ action('Admin\CustomerController@index') }}">
                        <i class="lnr lnr-user custom-icon-size mr-3"></i>
                        {{ trans('messages.customers') }}
                        <span class="{{ (request()->is('admin/customers')) ? 'active-nav-pilles6' : '' }} test33">|</span>
                    </a>
                </li>
                <li class="" rel0="SubscriptionController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/subscriptions')) ? 'active-nav' : '' }}" 
                    href="{{ action('Admin\SubscriptionController@index') }}">
                        <i class="lnr lnr-flag custom-icon-size mr-3"></i>
                        {{ trans('messages.subscriptions') }}
                        <span class="{{ (request()->is('admin/subscriptions')) ? 'active-nav-pilles' : '' }} test33">|</span>
                    </a>
                </li>
            </ul>
            <!-- End Users: subUsers -->
        </li>                                                            
        @endif
        <!-- End Customer -->

        <!-- Plan -->
        @if (
            Auth::user()->can("read", new Acelle\Model\Plan())
            || Auth::user()->can("read", new Acelle\Model\Currency())
        )
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" rel0="PlanController"
        rel1="CurrencyController">
            <a class="side-nav-menu-link media align-items-center" href="#" data-target="#plan">
                    <i class="lnr lnr-license custom-icon-size1 mr-4">
                    </i>
                </i>{{ trans('messages.plan') }}
                <span class="caret"></span>
            </a>
        
        <!-- Users: subUsers -->
            <ul id="plan" class="nav side-nav-menu side-nav-menu-second-level mb-0">
                @if (Auth::user()->can("read", new Acelle\Model\Plan()))
                <li class="" rel0="PlanController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/plans')) ? 'active-nav' : '' }}" href="{{ action('Admin\PlanController@index') }}">
                            <i class="lnr lnr-license custom-icon-size mr-3">
                            </i>
                            {{ trans('messages.plans') }}
                            <span class="{{ (request()->is('admin/plans')) ? 'active-nav-pilles9' : '' }} test33">|</span>
                    
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("read", new Acelle\Model\Currency()))
                <li class="" rel0="CurrencyController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/currencies')) ? 'active-nav' : '' }}" href="{{ action('Admin\CurrencyController@index') }}">
                            <i class="fa fa-dollar-sign custom-icon-size mr-3">
                            </i>
                            {{ trans('messages.currencies') }}
                            <span class="{{ (request()->is('admin/currencies')) ? 'active-nav-pilles6' : '' }} test33">|</span>
                    </a>
                </li>
                @endif
            </ul>
            <!-- End Users: subUsers -->
        </li>                                                            
        @endif
        <!-- End Plan -->

        <!-- Admin -->
        @if (
            Auth::user()->admin->getPermission("admin_read") != 'no'
            || Auth::user()->admin->getPermission("admin_group_read") != 'no'
        )
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" 
            rel0="AdminGroupController"
            rel1="AdminController"
        >
            <a class="side-nav-menu-link media align-items-center" href="#" data-target="#admin">
                    <i class="lnr lnr-users custom-icon-size1 mr-3">
                    </i>
                    {{ trans('messages.admin') }}
                <span class="caret"></span>
            </a>
        
        <!-- Users: subUsers -->
            <ul id="admin" class="nav side-nav-menu side-nav-menu-second-level mb-0">
                @if (Auth::user()->admin->getPermission("admin_read") != 'no')
                <li class="" rel0="AdminController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/admins')) ? 'active-nav' : '' }}" href="{{ action('Admin\AdminController@index') }}">
                            <i class="lnr lnr-user custom-icon-size mr-3">
                            </i>
                            {{ trans('messages.admins') }}	
                            <span class="{{ (request()->is('admin/admins')) ? 'active-nav-pilles8' : '' }} test33">|</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->admin->getPermission("admin_group_read") != 'no')
                <li class="" rel0="AdminGroupController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/admin_groups')) ? 'active-nav' : '' }}" href="{{ action('Admin\AdminGroupController@index') }}">
                            <i class="lnr lnr-users custom-icon-size mr-3"></i>
                            {{ trans('messages.admin_groups') }}
                            <span class="{{ (request()->is('admin/admin_groups')) ? 'active-nav-pilles2' : '' }} test33">|</span>
                        </span>
                    </a>
                </li>
                @endif
            </ul>
            <!-- End Users: subUsers -->
        </li>                                                            
        @endif
        <!-- End Admin -->
        <!--  -->
        @if (
            Auth::user()->admin->getPermission("sending_domain_read") != 'no'
            || Auth::user()->admin->getPermission("sending_server_read") != 'no'
            || Auth::user()->admin->getPermission("bounce_handler_read") != 'no'
            || Auth::user()->admin->getPermission("fbl_handler_read") != 'no'
            || Auth::user()->admin->getPermission("email_verification_server_read") != 'no'
            || Auth::user()->admin->can('read', new \Acelle\Model\SubAccount())
        )
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" 
            rel0="BounceHandlerController"
            rel1="FeedbackLoopHandlerController"
            rel2="SendingServerController"
            rel3="SendingDomainController"
            rel4="SubAccountController"
            rel5="EmailVerificationServerController"                                        
        >
            <a class="side-nav-menu-link media align-items-center" href="#" data-target="#subaccount">
                    <i class="lnr lnr-exit custom-icon-size1 mr-3">
                    </i>
                    {{ trans('messages.sending') }}
                <span class="caret"></span>
            </a>
        
        <!-- Users: subUsers -->
            <ul id="subaccount" class="nav side-nav-menu side-nav-menu-second-level mb-0" style="padding-left: 1.1rem;">
                @if (Auth::user()->admin->getPermission("sending_server_read") != 'no')
                <li class="" rel0="SendingServerController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/sending_servers')) ? 'active-nav' : '' }}" href="{{ action('Admin\SendingServerController@index') }}">
                        
                        <i class="lnr lnr-exit custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.sending_severs') }}
                        <span class="{{ (request()->is('admin/sending_servers')) ? 'active-nav-pilles2' : '' }} test33">|</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->admin->can('read', new \Acelle\Model\SubAccount()))
                <li class="" rel0="SubAccountController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/sub_accounts')) ? 'active-nav' : '' }}" href="{{ action('Admin\SubAccountController@index') }}">
                        <i class="fa fa-user-circle custom-icon-size mr-3"></i>
                        {{ trans('messages.sub_accounts') }}
                        <span class="{{ (request()->is('admin/sub_accounts')) ? 'active-nav-pilles' : '' }} test33">|</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->admin->getPermission("bounce_handler_read") != 'no')
                <li class="" rel0="BounceHandlerController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/bounce_handlers')) ? 'active-nav' : '' }}" href="{{ action('Admin\BounceHandlerController@index') }}">
                            <i class="lnr lnr-undo custom-icon-size mr-3">
                            </i>
                            {{ trans('messages.bounce_handlers')  }}
                            <span class="{{ (request()->is('admin/bounce_handlers')) ? 'active-nav-pilles2' : '' }} test33">|</span>
                        
                    </a>
                </li>
                @endif
                @if (Auth::user()->admin->getPermission("fbl_handler_read") != 'no')
                <li class="" rel0="FeedbackLoopHandlerController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/feedback_loop_handlers')) ? 'active-nav' : '' }}" href="{{ action('Admin\FeedbackLoopHandlerController@index') }}">
                            <i class="lnr lnr-sync custom-icon-size mr-3"></i>
                            {{ trans('messages.feedback_loop_handlers') }}
                            <span class="{{ (request()->is('admin/feedback_loop_handlers')) ? 'active-nav-pilles2' : '' }} test33">|</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->admin->getPermission("email_verification_server_read") != 'no')
                <li class="" rel0="EmailVerificationServerController">
                    <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/email_verification_servers')) ? 'active-nav' : '' }}" href="{{ action('Admin\EmailVerificationServerController@index') }}">
                            <i class="fa fa-user-check custom-icon-size mr-3"></i>
                            {{ trans('messages.email_verification_servers') }}
                            <span class="{{ (request()->is('admin/email_verification_servers')) ? 'active-nav-pilles2' : '' }} test33">|</span>
                    </a>
                </li>
                @endif
            </ul>
            <!-- End Users: subUsers -->
        </li>                                                            
        @endif
        
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" 
            rel0="TemplateController"
            rel1="LayoutController"
            rel2="LanguageController"
            rel3="SettingController"
            rel4="PaymentController"
            rel5="PluginController"                                    
        >
        <a class="side-nav-menu-link media align-items-center" href="#" data-target="#alluser">
                <i class="lnr lnr-cog custom-icon-size1 mr-4">
                </i>
            </i>{{ trans('messages.setting') }}
            <span class="caret"></span>
        </a>
    
        <!-- Users: subUsers -->
        <ul id="alluser" class="nav side-nav-menu side-nav-menu-second-level mb-0" style="padding-left: 1.1rem;">
            @if (
                    Auth::user()->admin->getPermission("setting_general") != 'no' ||
                    Auth::user()->admin->getPermission("setting_sending") != 'no' ||
                    Auth::user()->admin->getPermission("setting_system_urls") != 'no' ||
                    Auth::user()->admin->getPermission("setting_background_job") != 'no'
                )
            <li style="" class="" rel0="SettingController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/settings/general')) ? 'active-nav' : '' }}" href="{{ action('Admin\SettingController@index') }}">
                        <i class="lnr lnr-cog custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.all_settings') }}
                        <span class="{{ (request()->is('admin/settings/general')) ? 'active-nav-pilles5' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("template_read") != 'no')
            <li class="" rel0="TemplateController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/templates')) ? 'active-nav' : '' }}" href="{{ action('Admin\TemplateController@index') }}">
                        <i class="lnr lnr-license custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.template_gallery') }}
                        <span class="{{ (request()->is('admin/templates')) ? 'active-nav-pilles2A' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("layout_read") != 'no')
            <li class="" rel0="LayoutController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/layouts')) ? 'active-nav' : '' }}" href="{{ action('Admin\LayoutController@index') }}">
                        <i class="lnr lnr-envelope custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.page_form_layout') }}
                        <span class="{{ (request()->is('admin/layouts')) ? 'active-nav-pilles' : '' }} test33">|</span>
                    
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("language_read") != 'no')
            <li class="" rel0="LanguageController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/languages')) ? 'active-nav' : '' }}" href="{{ action('Admin\LanguageController@index') }}">
                        <i class="lnr lnr-text-format custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.language') }}
                        <span class="{{ (request()->is('admin/languages')) ? 'active-nav-pilles8a' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("payment_method_read") != 'no')
            <li class="" rel0="PaymentController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/payment/gateways/index')) ? 'active-nav' : '' }}" href="{{ action('Admin\PaymentController@index') }}">
                        <i class="fa fa-credit-card custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.payment_gateways') }}
                        <span class="{{ (request()->is('admin/payment/gateways/index')) ? 'active-nav-pilles' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            <li class="" rel0="PluginController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/plugins')) ? 'active-nav' : '' }}" href="{{ action('Admin\PluginController@index') }}">
                        <i class="fa fa-plug custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.plugins') }}
                        <span class="{{ (request()->is('admin/plugins')) ? 'active-nav-pilles1' : '' }} test33">|</span>
                    
                </a>
            </li>
        </ul>
        <!-- End Users: subUsers -->
        </li>                                                            
        <!-- End  -->

        @if (
        Auth::user()->admin->getPermission("report_blacklist") != 'no'
            || Auth::user()->admin->getPermission("report_tracking_log") != 'no'
            || Auth::user()->admin->getPermission("report_bounce_log") != 'no'
            || Auth::user()->admin->getPermission("report_feedback_log") != 'no'
            || Auth::user()->admin->getPermission("report_open_log") != 'no'
            || Auth::user()->admin->getPermission("report_click_log") != 'no'
            || Auth::user()->admin->getPermission("report_unsubscribe_log") != 'no'
        )
        <li style="width: 215px;" class="side-nav-menu-item side-nav-has-menu" 
        rel0="TrackingLogController"
        rel1="OpenLogController"
        rel2="ClickLogController"
        rel3="FeedbackLogController"
        rel4="BlacklistController"
        rel5="UnsubscribeLogController"
        rel6="BounceLogController"                                       
        >
        <a class="side-nav-menu-link media align-items-center" href="#" data-target="#report">
                <i class="lnr lnr-chart-bars custom-icon-size1 mr-4">
                </i>
                {{ trans('messages.report') }}
            <span class="caret"></span>
        </a>
    
        <!-- Users: subUsers -->
        <ul id="report" class="nav side-nav-menu side-nav-menu-second-level mb-0">
            @if (Auth::user()->admin->getPermission("report_blacklist") != 'no')
            <li class="" rel0="BlacklistController">
                <a class="side-nav-menu-link align-items-center {{ (request()->is('admin/blacklist')) ? 'active-nav' : '' }}" href="{{ action('Admin\BlacklistController@index') }}">
                        <i class="fa fa-user-slash custom-icon-size mr-3">
                        </i>
                    {{ trans('messages.blacklist') }}
                    <span class="{{ (request()->is('admin/blacklist')) ? 'active-nav-pilles5' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("report_tracking_log") != 'no')
            <li class="" rel0="TrackingLogController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/tracking_log')) ? 'active-nav' : '' }}" href="{{ action('Admin\TrackingLogController@index') }}">
                        <i class="lnr lnr-eye" custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.tracking_log') }}
                        <span class="{{ (request()->is('admin/tracking_log')) ? 'active-nav-pilles' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("report_feedback_log") != 'no')
            <li class="" rel0="FeedbackLogController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/feedback_log')) ? 'active-nav' : '' }}" href="{{ action('Admin\FeedbackLogController@index') }}">
                        <i class="lnr lnr-sync custom-icon-size mr-3">
                        </i>
                        {{ trans('messages.feedback_log')  }}
                        <span class="{{ (request()->is('admin/feedback_log')) ? 'active-nav-pilles2' : '' }} test33">|</span>
                    
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("report_open_log") != 'no')
            <li class="" rel0="OpenLogController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/open_log')) ? 'active-nav' : '' }}" href="{{ action('Admin\OpenLogController@index') }}">
                        <i class="lnr lnr-envelope custom-icon-size mr-3"></i>
                        {{ trans('messages.open_log') }}
                        <span class="{{ (request()->is('admin/open_log')) ? 'active-nav-pilles' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("report_click_log") != 'no')
            <li class="" rel0="ClickLogController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/click_log')) ? 'active-nav' : '' }}" href="{{ action('Admin\ClickLogController@index') }}">
                    <i class="lnr lnr-select custom-icon-size mr-3"></i>
                    {{ trans('messages.click_log') }}
                    <span class="{{ (request()->is('admin/click_log')) ? 'active-nav-pilles5' : '' }} test33">|</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->admin->getPermission("report_unsubscribe_log") != 'no')
            <li class="" rel0="UnsubscribeLogController">
                <a  class="side-nav-menu-link align-items-center {{ (request()->is('admin/unsubscribe_log')) ? 'active-nav' : '' }}" href="{{ action('Admin\UnsubscribeLogController@index') }}">
                        <i class="lnr lnr-cross custom-icon-size mr-3"></i>
                        {{ trans('messages.unsubscribe_log') }}
                        <span class="{{ (request()->is('admin/unsubscribe_log')) ? 'active-nav-pilles3' : '' }} test33">|</span>
                </a>
            </li>
            @endif
        </ul>
        <!-- End Users: subUsers -->
        </li>                                                            
        @endif

        <!-- Title -->
        <li class="sidebar-heading h6"></li>
        <li class="sidebar-heading h6"></li>
        <!-- End Title -->

    </ul>
</aside>
<!-- End Sidebar Nav -->
<main class="main main2">
    
    <!-- Page header -->
	<!--<div class="page-header">-->
	<!--	<div class="page-header-content">-->

	<!--		@yield('page_header')-->

	<!--	</div>-->
	<!--</div>-->
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


	<script src="{{ asset('graindashboard/js/graindashboard.js') }}"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="{{ asset('graindashboard/js/graindashboard.vendor.js') }}"></script>
</body>
</html>
