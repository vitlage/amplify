<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\CampaignUpdated' => [
            'App\Listeners\CampaignUpdatedListener',
        ],
        'App\Events\MailListUpdated' => [
            'App\Listeners\MailListUpdatedListener',
        ],
        'App\Events\UserUpdated' => [
            'App\Listeners\UserUpdatedListener',
        ],
        'App\Events\CronJobExecuted' => [
            'App\Listeners\CronJobExecutedListener',
        ],
        'App\Events\AdminLoggedIn' => [
            'App\Listeners\AdminLoggedInListener',
        ],
        'App\Events\MailListSubscription' => [
            'App\Listeners\MailListSubscriptionListener',
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
