<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Model\User;
use App\Model\Setting;
use App\Cashier\Cashier;
use App\Model\Invoice;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Invoice $invoice, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $invoice->isNew() || $invoice->isClaimed();
                break;
            case 'customer':
                $can = ($invoice->isNew() || $invoice->isClaimed()) && $invoice->customer_id == $user->customer->id;
                break;
        }

        return $can;
    }
}
