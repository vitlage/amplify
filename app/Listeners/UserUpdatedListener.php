<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\SystemJob as SystemJobModel;

class UserUpdatedListener
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
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        if ($event->delayed) {
            $existed = SystemJobModel::getNewJobs()
                           ->where('name', \App\Jobs\UpdateUserJob::class)
                           ->where('data', $event->customer->id)
                           ->exists();
            if (!$existed) {
                dispatch(new \App\Jobs\UpdateUserJob($event->customer));
            }
        } else {
            $event->customer->updateCache();
        }
    }
}
