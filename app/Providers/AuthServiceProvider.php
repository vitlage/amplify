<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Model\Setting;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Model\User::class => \App\Policies\UserPolicy::class,
        \App\Model\Contact::class => \App\Policies\ContactPolicy::class,
        \App\Model\MailList::class => \App\Policies\MailListPolicy::class,
        \App\Model\Subscriber::class => \App\Policies\SubscriberPolicy::class,
        \App\Model\Segment::class => \App\Policies\SegmentPolicy::class,
        \App\Model\Layout::class => \App\Policies\LayoutPolicy::class,
        \App\Model\Template::class => \App\Policies\TemplatePolicy::class,
        \App\Model\Campaign::class => \App\Policies\CampaignPolicy::class,
        \App\Model\SendingServer::class => \App\Policies\SendingServerPolicy::class,
        \App\Model\BounceHandler::class => \App\Policies\BounceHandlerPolicy::class,
        \App\Model\FeedbackLoopHandler::class => \App\Policies\FeedbackLoopHandlerPolicy::class,
        \App\Model\SendingDomain::class => \App\Policies\SendingDomainPolicy::class,
        \App\Model\Language::class => \App\Policies\LanguagePolicy::class,
        \App\Model\CustomerGroup::class => \App\Policies\CustomerGroupPolicy::class,
        \App\Model\Customer::class => \App\Policies\CustomerPolicy::class,
        \App\Model\AdminGroup::class => \App\Policies\AdminGroupPolicy::class,
        \App\Model\Admin::class => \App\Policies\AdminPolicy::class,
        \App\Model\Setting::class => \App\Policies\SettingPolicy::class,
        \App\Model\Plan::class => \App\Policies\PlanPolicy::class,
        \App\Model\Currency::class => \App\Policies\CurrencyPolicy::class,
        \App\Model\SystemJob::class => \App\Policies\SystemJobPolicy::class,
        \App\Model\Subscription::class => \App\Policies\SubscriptionPolicy::class,
        \App\Model\PaymentMethod::class => \App\Policies\PaymentMethodPolicy::class,
        \App\Model\EmailVerificationServer::class => \App\Policies\EmailVerificationServerPolicy::class,
        \App\Model\Blacklist::class => \App\Policies\BlacklistPolicy::class,
        \App\Model\SubAccount::class => \App\Policies\SubAccountPolicy::class,
        \App\Model\Sender::class => \App\Policies\SenderPolicy::class,
        \App\Model\Automation2::class => \App\Policies\Automation2Policy::class,
        \App\Model\TrackingDomain::class => \App\Policies\TrackingDomainPolicy::class,
        \App\Model\Plugin::class => \App\Policies\PluginPolicy::class,
        \App\Model\Invoice::class => \App\Policies\InvoicePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
