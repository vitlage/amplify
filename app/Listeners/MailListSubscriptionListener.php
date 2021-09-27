<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\MailListSubscription;
use App\Mail\MailListSubscriptionNotificationMailer;

class MailListSubscriptionListener
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
    public function handle(MailListSubscription $event)
    {
        // Send notification
        Mail::to(
            json_decode(json_encode(['email' => $event->user->email, 'name' => $event->user->customer->displayName()]))
        )->send(
            new MailListSubscriptionNotificationMailer($event->subscriber)
        );
    }
}
