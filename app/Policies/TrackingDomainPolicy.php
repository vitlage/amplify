<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Model\User;
use App\Model\TrackingDomain;
use App\Model\Plan;

class TrackingDomainPolicy
{
    use HandlesAuthorization;

    public function read(User $user, TrackingDomain $item, $role)
    {
        return true;
    }

    public function create(User $user, TrackingDomain $item, $role)
    {
        return true;
    }

    public function update(User $user, TrackingDomain $item, $role)
    {
        return $user->customer->id == $item->customer_id;
    }

    public function delete(User $user, TrackingDomain $item, $role)
    {
        return $user->customer->id == $item->customer_id;
    }
}
