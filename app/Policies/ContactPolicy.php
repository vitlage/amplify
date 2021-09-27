<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Model\User;
use App\Model\Contact;

class ContactPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Contact $item)
    {
        return !isset($item->id) || $user->contact_id == $item->id;
    }
}
