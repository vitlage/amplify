<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Model\User;
use App\Model\Layout;

class LayoutPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Layout $item)
    {
        $ability = $user->admin->getPermission('layout_update');
        $can = $ability == 'yes';

        return $can;
    }
}
