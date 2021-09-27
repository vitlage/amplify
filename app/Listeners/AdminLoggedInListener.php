<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Notification\CronJob;
use App\Library\Notification\SystemUrl;
use App\Library\Notification\Subscription;
use App\Events\AdminLoggedIn;

class AdminLoggedInListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminLoggedIn  $event
     * @return void
     */
    public function handle(AdminLoggedIn $event)
    {
        // Check CronJob
        CronJob::check();

        // Check System URL
        SystemUrl::check();

        // Check for pending subscriptions
        Subscription::check();
    }
}
